<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SyncApiController extends Controller
{
    /**
     * Pull: Récupérer les données non synchronisées pour un client spécifique
     * GET /api/sync/pull
     */
    public function pull(Request $request)
    {
        try {
            $clientId = $request->header('X-Client-ID');
            $lastSync = $request->query('last_sync');

            if (!$clientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client ID manquant'
                ], 400);
            }

            $client = DB::table('clients')->where('client_id', $clientId)->first();
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client non reconnu'
                ], 403);
            }

            $lastSyncCarbon = null;
            if ($lastSync) {
                try {
                    $lastSyncCarbon = Carbon::parse($lastSync)
                        ->setTimezone('Africa/Douala')
                        ->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    \Log::warning('Erreur parsing last_sync', ['error' => $e->getMessage()]);
                }
            }

            \Log::info('=== PULL REQUEST ===', [
                'client_id' => $clientId,
                'last_sync' => $lastSyncCarbon,
                'request_time' => now('Africa/Douala')->format('Y-m-d H:i:s')
            ]);

            $data = [];

            // Users
            $data['users'] = $this->getUnsyncedData('users', $clientId, $lastSyncCarbon,
                ['id', 'name', 'numero_telephone', 'role', 'code_pin', 'actif', 'updated_at']);

            // Produits
            $data['produits'] = $this->getUnsyncedData('produits', $clientId, $lastSyncCarbon,
                ['id', 'nom', 'prix', 'categorie', 'actif', 'updated_at'],
                ['actif' => true]);

            // FIX: inclure "connecte_a" ET faire une jointure pour récupérer le nom du vendeur
            // Le mobile a besoin de vendeur_name pour afficher qui est actif.
            $data['vendeurs_actifs'] = $this->getVendeursActifs($clientId, $lastSyncCarbon);

            // Réceptions
            $data['receptions_pointeur'] = $this->getUnsyncedData('receptions_pointeur', $clientId, $lastSyncCarbon,
                ['id', 'pointeur_id', 'producteur_id', 'produit_id', 'quantite', 'vendeur_assigne_id',
                 'verrou', 'date_reception', 'notes', 'updated_at']);

            // Retours - FIX: on sélectionne explicitement les colonnes dont le mobile a besoin
            $data['retours_produits'] = $this->getUnsyncedData('retours_produits', $clientId, $lastSyncCarbon,
                ['id', 'pointeur_id', 'vendeur_id', 'produit_id', 'quantite', 'raison',
                 'description', 'verrou', 'date_retour', 'updated_at']);

            // Sessions
            $data['sessions_vente'] = $this->getUnsyncedData('sessions_vente', $clientId, $lastSyncCarbon,
                ['id', 'vendeur_id', 'categorie', 'fond_vente', 'orange_money_initial', 'mtn_money_initial',
                 'montant_verse', 'orange_money_final', 'mtn_money_final', 'manquant', 'statut',
                 'fermee_par', 'date_ouverture', 'date_fermeture', 'updated_at']);

            // Raisons de retour (toujours toutes envoyées, table légère)
            $data['raisons_retour'] = DB::table('raisons_retour')
                ->where('actif', true)
                ->select(['id', 'code', 'libelle', 'actif', 'updated_at'])
                ->get();
 
            \Log::info('PULL - Données récupérées', ['client_id' => $clientId]);

            return response()->json([
                'success' => true,
                'message' => 'Données récupérées avec succès',
                'data' => $data,
                'sync_time' => now('Africa/Douala')->toIso8601String(),
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Erreur PULL', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * FIX: Récupérer les vendeurs actifs avec le nom du vendeur (jointure users)
     * Le mobile stocke vendeur_name mais la table vendeurs_actifs n'a pas ce champ.
     */
    private function getVendeursActifs($clientId, $lastSync)
    {
        $query = DB::table('vendeurs_actifs')
            ->join('users', 'vendeurs_actifs.vendeur_id', '=', 'users.id')
            ->select(
                'vendeurs_actifs.id',
                'vendeurs_actifs.categorie',
                'vendeurs_actifs.vendeur_id',
                'users.name as vendeur_name',   // <-- nom résolu côté serveur
                'vendeurs_actifs.connecte_a',
                'vendeurs_actifs.updated_at'
            );

        $query->where(function ($q) use ($clientId, $lastSync) {
            $q->where(function ($sq) use ($clientId) {
                $sq->whereNull('vendeurs_actifs.synced_clients')
                   ->orWhereRaw("NOT JSON_CONTAINS(vendeurs_actifs.synced_clients, '\"$clientId\"')");
            })->orWhere(function ($sq) use ($lastSync) {
                if ($lastSync) {
                    $lastSyncPlusOneMin = date('Y-m-d H:i:s', strtotime($lastSync . ' +1 minutes'));
                    $sq->where('vendeurs_actifs.updated_at', '>', $lastSyncPlusOneMin);
                }
            });
        });

        return $query->get();
    }

    /**
     * Confirmation de réception par le client
     * POST /api/sync/ack
     */
    public function acknowledgement(Request $request)
    {
        try {
            $clientId = $request->header('X-Client-ID');
            $syncedData = $request->input('synced_data', []);

            if (!$clientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client ID manquant'
                ], 400);
            }

            \Log::info('=== SYNC ACK ===', [
                'client_id' => $clientId,
                'synced_count' => count($syncedData)
            ]);

            DB::beginTransaction();

            foreach ($syncedData as $item) {
                $table = $item['table'] ?? null;
                $ids = $item['ids'] ?? [];

                if ($table && !empty($ids)) {
                    $this->markAsSyncedForClient($table, $ids, $clientId);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Confirmation enregistrée'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erreur ACK', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la confirmation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function push(Request $request)
    {
        try {
            $clientId = $request->header('X-Client-ID');
            $data = $request->all();

            if (!$clientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client ID manquant',
                    'confirmed' => false
                ], 400);
            }

            \Log::info('=== SYNC PUSH ===', [
                'client_id' => $clientId,
                'data_keys' => array_keys($data)
            ]);

            $synced = [];
            $conflicts = [];
            $hasErrors = false;

            DB::beginTransaction();

            try {
                // Réceptions
                if (!empty($data['receptions'])) {
                    \Log::info('[PUSH] Traitement de ' . count($data['receptions']) . ' réceptions');
                    foreach ($data['receptions'] as $reception) {
                        $result = $this->syncReception($reception, $clientId);
                        if ($result['success']) {
                            $synced[] = [
                                'table' => 'receptions_pointeur',
                                'local_id' => $reception['local_id'] ?? null,
                                'id' => $reception['id'] ?? null,
                                'server_id' => $result['id'],
                            ];
                        } else {
                            $conflicts[] = [
                                'table' => 'receptions_pointeur',
                                'id' => $reception['id'] ?? null,
                                'local_id' => $reception['local_id'] ?? null,
                                'reason' => $result['reason'],
                            ];
                            $hasErrors = true;
                        }
                    }
                }

                // Retours
                if (!empty($data['retours'])) {
                    \Log::info('[PUSH] Traitement de ' . count($data['retours']) . ' retours');
                    foreach ($data['retours'] as $retour) {
                        $result = $this->syncRetour($retour, $clientId);
                        if ($result['success']) {
                            $synced[] = [
                                'table' => 'retours_produits',
                                'local_id' => $retour['local_id'] ?? null,
                                'id' => $retour['id'] ?? null,
                                'server_id' => $result['id'],
                            ];
                        } else {
                            $conflicts[] = [
                                'table' => 'retours_produits',
                                'id' => $retour['id'] ?? null,
                                'local_id' => $retour['local_id'] ?? null,
                                'reason' => $result['reason'],
                            ];
                            $hasErrors = true;
                        }
                    }
                }

                // Inventaires
                if (!empty($data['inventaires'])) {
                    \Log::info('[PUSH] Traitement de ' . count($data['inventaires']) . ' inventaires');
                    foreach ($data['inventaires'] as $inventaire) {
                        $result = $this->syncInventaire($inventaire, $clientId);
                        if ($result['success']) {
                            $synced[] = [
                                'table' => 'inventaires',
                                'local_id' => $inventaire['local_id'] ?? null,
                                'id' => $inventaire['id'] ?? null,
                                'server_id' => $result['id'],
                            ];
                        } else {
                            $conflicts[] = [
                                'table' => 'inventaires',
                                'id' => $inventaire['id'] ?? null,
                                'local_id' => $inventaire['local_id'] ?? null,
                                'reason' => $result['reason'],
                            ];
                            $hasErrors = true;
                        }
                    }
                }

                // Inventaire Details
                if (!empty($data['inventaire_details'])) {
                    \Log::info('[PUSH] Traitement de ' . count($data['inventaire_details']) . ' détails inventaire');
                    foreach ($data['inventaire_details'] as $detail) {
                        $result = $this->syncInventaireDetails($detail, $clientId);
                        if ($result['success']) {
                            $synced[] = [
                                'table' => 'inventaire_details',
                                'id' => $detail['id'] ?? null,
                                'server_id' => $result['id'],
                            ];
                        } else {
                            $conflicts[] = [
                                'table' => 'inventaire_details',
                                'id' => $detail['id'] ?? null,
                                'reason' => $result['reason'],
                            ];
                            $hasErrors = true;
                        }
                    }
                }

                // Sessions
                if (!empty($data['sessions'])) {
                    \Log::info('[PUSH] Traitement de ' . count($data['sessions']) . ' sessions');
                    foreach ($data['sessions'] as $session) {
                        $result = $this->syncSession($session, $clientId);
                        if ($result['success']) {
                            $synced[] = [
                                'table' => 'sessions_vente',
                                'local_id' => $session['local_id'] ?? null,
                                'id' => $session['id'] ?? null,
                                'server_id' => $result['id'],
                            ];
                        } else {
                            $conflicts[] = [
                                'table' => 'sessions_vente',
                                'id' => $session['id'] ?? null,
                                'local_id' => $session['local_id'] ?? null,
                                'reason' => $result['reason'],
                            ];
                            $hasErrors = true;
                        }
                    }
                }

                if ($hasErrors && empty($synced)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'confirmed' => false,
                        'message' => 'Échec de synchronisation',
                        'synced' => [],
                        'conflicts' => $conflicts,
                        'sync_time' => now()->toIso8601String(),
                    ], 422);
                }

                DB::commit();

                \Log::info('PUSH confirmé', [
                    'client_id' => $clientId,
                    'synced' => count($synced),
                    'conflicts' => count($conflicts),
                ]);

                return response()->json([
                    'success' => true,
                    'confirmed' => true,
                    'message' => 'Synchronisation confirmée',
                    'synced' => $synced,
                    'conflicts' => $conflicts,
                    'sync_time' => now()->toIso8601String(),
                ], 200);

            } catch (\Exception $innerException) {
                DB::rollBack();
                throw $innerException;
            }

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erreur critique PUSH', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'confirmed' => false,
                'message' => 'Erreur critique de synchronisation',
                'error' => $e->getMessage(),
                'synced' => [],
                'conflicts' => [],
            ], 500);
        }
    }

    // ========== SYNC PRIVÉES ==========

    private function syncReception($data, $clientId)
{
    try {
        $dateReception = $this->parseDate($data['date_reception'] ?? null);

        $commonData = [
            'pointeur_id'       => $data['pointeur_id'],
            'producteur_id'     => $data['producteur_id'],
            'produit_id'        => $data['produit_id'],
            'quantite'          => $data['quantite'],
            'vendeur_assigne_id'=> $data['vendeur_assigne_id'] ?? null,
            'verrou'            => $data['verrou'] ?? false,
            'date_reception'    => $dateReception,
            'notes'             => $data['notes'] ?? null,
        ];

        if (isset($data['id']) && is_numeric($data['id']) && $data['id'] > 0) {
            $existing = DB::table('receptions_pointeur')->find($data['id']);
            if ($existing) {
                if ($existing->verrou) {
                    return ['success' => false, 'reason' => 'Enregistrement verrouillé'];
                }
                $syncedClients = json_decode($existing->synced_clients ?? '[]', true);
                if (!in_array($clientId, $syncedClients)) {
                    $syncedClients[] = $clientId;
                }
                $commonData['synced_clients'] = json_encode($syncedClients);
                $commonData['updated_at'] = now();
                // NE PAS écraser date_reception sur update
                unset($commonData['date_reception']);
                DB::table('receptions_pointeur')->where('id', $data['id'])->update($commonData);
                return ['success' => true, 'id' => $data['id']];
            }
        }

        $commonData['synced_clients'] = json_encode([$clientId]);
        $commonData['created_at'] = now();
        $commonData['updated_at'] = now();
        $id = DB::table('receptions_pointeur')->insertGetId($commonData);
        return ['success' => true, 'id' => $id];

    } catch (\Exception $e) {
        \Log::error('syncReception error', ['error' => $e->getMessage()]);
        return ['success' => false, 'reason' => $e->getMessage()];
    }
}

    private function syncRetour($data, $clientId)
{
    try {
        \Log::info('[SYNC RETOUR] Début', ['client_id' => $clientId, 'payload' => $data]);

        $dateRetour = $this->parseDate($data['date_retour'] ?? null);

        $vendeurId = $data['vendeur_id'] ?? null;
        if ($vendeurId === null) {
            \Log::error('[SYNC RETOUR] vendeur_id manquant ou null', ['data' => $data]);
            return ['success' => false, 'reason' => 'vendeur_id obligatoire pour un retour'];
        }

        $commonData = [
            'pointeur_id' => $data['pointeur_id'] ?? null,
            'vendeur_id'  => $vendeurId,
            'produit_id'  => $data['produit_id'] ?? null,
            'quantite'    => $data['quantite'] ?? null,
            'raison'      => $data['raison'] ?? null,
            'description' => $data['description'] ?? null,
            'verrou'      => $data['verrou'] ?? false,
            'date_retour' => $dateRetour,
        ];

        if (isset($data['id']) && is_numeric($data['id']) && $data['id'] > 0) {
            $existing = DB::table('retours_produits')->find($data['id']);
            if ($existing) {
                if ($existing->verrou) {
                    return ['success' => false, 'reason' => 'Verrouillé'];
                }
                $syncedClients = json_decode($existing->synced_clients ?? '[]', true);
                if (!in_array($clientId, $syncedClients)) {
                    $syncedClients[] = $clientId;
                }
                $commonData['synced_clients'] = json_encode($syncedClients);
                $commonData['updated_at'] = now();
                // NE PAS écraser date_retour sur update
                unset($commonData['date_retour']);
                DB::table('retours_produits')->where('id', $data['id'])->update($commonData);
                \Log::info('[SYNC RETOUR] Mise à jour réussie', ['id' => $data['id']]);
                return ['success' => true, 'id' => $data['id']];
            }
        }

        $commonData['synced_clients'] = json_encode([$clientId]);
        $commonData['created_at'] = now();
        $commonData['updated_at'] = now();
        $id = DB::table('retours_produits')->insertGetId($commonData);
        \Log::info('[SYNC RETOUR] Création réussie', ['id' => $id]);
        return ['success' => true, 'id' => $id];

    } catch (\Exception $e) {
        \Log::error('[SYNC RETOUR] Exception', [
            'message' => $e->getMessage(),
            'data' => $data,
        ]);
        return ['success' => false, 'reason' => $e->getMessage()];
    }
}

   private function syncInventaire($data, $clientId)
    {
    try {
        \Log::info('[SYNC INVENTAIRE] Début', [
            'client_id' => $clientId,
            'local_id'  => $data['local_id'] ?? null,
            'id'        => $data['id'] ?? null,
        ]);

        $vendeurSortant = DB::table('users')->find($data['vendeur_sortant_id'] ?? null);
        if (!$vendeurSortant) {
            return ['success' => false, 'reason' => 'Vendeur sortant introuvable'];
        }

        $categorie = ($vendeurSortant->role === 'vendeur_patisserie') ? 'patisserie' : 'boulangerie';

        $commonData = [
            'vendeur_sortant_id' => $data['vendeur_sortant_id'] ?? null,
            'vendeur_entrant_id' => $data['vendeur_entrant_id'] ?? null,
            'categorie'          => $categorie,
            'valide_sortant'     => true,
            'valide_entrant'     => true,
            'date_inventaire'    => $this->parseDate($data['date_inventaire'] ?? null),
        ];

        // Mise à jour si l'inventaire existe déjà
        if (isset($data['id']) && is_numeric($data['id']) && $data['id'] > 0) {
            $existing = DB::table('inventaires')->find($data['id']);
            if ($existing) {
                $syncedClients = json_decode($existing->synced_clients ?? '[]', true);
                if (!in_array($clientId, $syncedClients)) {
                    $syncedClients[] = $clientId;
                }
                $commonData['synced_clients'] = json_encode($syncedClients);
                $commonData['updated_at'] = now();
                DB::table('inventaires')->where('id', $data['id'])->update($commonData);

                // ✅ Mettre à jour vendeurs_actifs
                DB::table('vendeurs_actifs')
                    ->where('categorie', $categorie)
                    ->update([
                        'vendeur_id' => $data['vendeur_entrant_id'],
                        'connecte_a' => now(),
                        'updated_at' => now(),
                    ]);

                return ['success' => true, 'id' => $data['id']];
            }
        }

        // Anti-doublon
        $recentDuplicate = DB::table('inventaires')
            ->where('vendeur_sortant_id', $data['vendeur_sortant_id'])
            ->where('vendeur_entrant_id', $data['vendeur_entrant_id'])
            ->whereRaw("JSON_CONTAINS(synced_clients, '\"" . addslashes($clientId) . "\"')")
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->first();

        if ($recentDuplicate) {
            // ✅ Mettre à jour vendeurs_actifs même en cas de doublon
            DB::table('vendeurs_actifs')
                ->where('categorie', $categorie)
                ->update([
                    'vendeur_id' => $data['vendeur_entrant_id'],
                    'connecte_a' => now(),
                    'updated_at' => now(),
                ]);

            return ['success' => true, 'id' => $recentDuplicate->id];
        }

        // Création
        $commonData['synced_clients'] = json_encode([$clientId]);
        $commonData['created_at'] = now();
        $commonData['updated_at'] = now();
        $id = DB::table('inventaires')->insertGetId($commonData);

        // ✅ Mettre à jour vendeurs_actifs après création
        DB::table('vendeurs_actifs')
            ->where('categorie', $categorie)
            ->update([
                'vendeur_id' => $data['vendeur_entrant_id'],
                'connecte_a' => now(),
                'updated_at' => now(),
            ]);

        \Log::info('[SYNC INVENTAIRE] Créé + vendeur actif mis à jour', [
            'inventaire_id' => $id,
            'categorie'     => $categorie,
            'vendeur_entrant_id' => $data['vendeur_entrant_id'],
        ]);

        return ['success' => true, 'id' => $id];

    } catch (\Exception $e) {
        \Log::error('[SYNC INVENTAIRE] Exception', ['message' => $e->getMessage()]);
        return ['success' => false, 'reason' => $e->getMessage()];
    }
    }

    private function syncInventaireDetails($data, $clientId)
    {
        try {
            if (empty($data['produit_id'])) {
                return ['success' => false, 'reason' => 'produit_id manquant'];
            }

            $inventaire_id = null;

            if (!empty($data['inventaire_id'])) {
                $inventaire_id = $data['inventaire_id'];
            } elseif (!empty($data['inventaire_local_id'])) {
                $inventaire = DB::table('inventaires')
                    ->whereRaw("JSON_CONTAINS(synced_clients, '\"$clientId\"')")
                    ->orderBy('updated_at', 'desc')
                    ->first();
                if ($inventaire) {
                    $inventaire_id = $inventaire->id;
                } else {
                    return ['success' => false, 'reason' => 'Inventaire parent introuvable'];
                }
            } else {
                return ['success' => false, 'reason' => 'inventaire_id manquant'];
            }

            $inventaire = DB::table('inventaires')->find($inventaire_id);
            if (!$inventaire) {
                return ['success' => false, 'reason' => 'Inventaire parent introuvable'];
            }

            $produit = DB::table('produits')->find($data['produit_id']);
            if (!$produit) {
                return ['success' => false, 'reason' => 'Produit introuvable'];
            }

            $commonData = [
                'inventaire_id'    => $inventaire_id,
                'produit_id'       => $data['produit_id'],
                'quantite_restante'=> $data['quantite_restante'] ?? 0,
            ];

            $existing = DB::table('inventaire_details')
                ->where('inventaire_id', $inventaire_id)
                ->where('produit_id', $data['produit_id'])
                ->first();

            if ($existing) {
                $syncedClients = json_decode($existing->synced_clients ?? '[]', true);
                if (!in_array($clientId, $syncedClients)) {
                    $syncedClients[] = $clientId;
                }
                $commonData['synced_clients'] = json_encode($syncedClients);
                $commonData['updated_at'] = now();
                DB::table('inventaire_details')->where('id', $existing->id)->update($commonData);
                return ['success' => true, 'id' => $existing->id];
            }

            $commonData['synced_clients'] = json_encode([$clientId]);
            $commonData['created_at'] = now();
            $commonData['updated_at'] = now();
            $id = DB::table('inventaire_details')->insertGetId($commonData);
            return ['success' => true, 'id' => $id];

        } catch (\Exception $e) {
            \Log::error('[SYNC INVENTAIRE DETAILS] Exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'reason' => $e->getMessage()];
        }
    }

    private function syncSession($data, $clientId)
    {
        try {
            $dateOuverture = $this->parseDate($data['date_ouverture'] ?? null);
            $dateFermeture = isset($data['date_fermeture']) ? $this->parseDate($data['date_fermeture']) : null;

            $commonData = [
                'vendeur_id'           => $data['vendeur_id'],
                'categorie'            => $data['categorie'],
                'fond_vente'           => $data['fond_vente'] ?? 0,
                'orange_money_initial' => $data['orange_money_initial'] ?? 0,
                'mtn_money_initial'    => $data['mtn_money_initial'] ?? 0,
                'montant_verse'        => $data['montant_verse'] ?? null,
                'orange_money_final'   => $data['orange_money_final'] ?? null,
                'mtn_money_final'      => $data['mtn_money_final'] ?? null,
                'manquant'             => $data['manquant'] ?? null,
                'statut'               => $data['statut'] ?? 'ouverte',
                'fermee_par'           => $data['fermee_par'] ?? null,
                'date_ouverture'       => $dateOuverture,
                'date_fermeture'       => $dateFermeture,
            ];

            if (isset($data['id']) && is_numeric($data['id']) && $data['id'] > 0) {
                $existing = DB::table('sessions_vente')->find($data['id']);
                if ($existing) {
                    $syncedClients = json_decode($existing->synced_clients ?? '[]', true);
                    if (!in_array($clientId, $syncedClients)) {
                        $syncedClients[] = $clientId;
                    }
                    $commonData['synced_clients'] = json_encode($syncedClients);
                    $commonData['updated_at'] = now();
                    DB::table('sessions_vente')->where('id', $data['id'])->update($commonData);
                    return ['success' => true, 'id' => $data['id']];
                }
            }

            $commonData['synced_clients'] = json_encode([$clientId]);
            $commonData['created_at'] = now();
            $commonData['updated_at'] = now();
            $id = DB::table('sessions_vente')->insertGetId($commonData);
            return ['success' => true, 'id' => $id];

        } catch (\Exception $e) {
            return ['success' => false, 'reason' => $e->getMessage()];
        }
    }

    // ========== HELPERS ==========

    private function getUnsyncedData($table, $clientId, $lastSync, $columns, $additionalWhere = null, $inventaireIds = null)
    {
        $query = DB::table($table);

        if ($additionalWhere) {
            foreach ($additionalWhere as $key => $value) {
                $query->where($key, $value);
            }
        }

        if ($table === 'users') {
            $query->where('role', '!=', 'pdg');
        }

        if ($inventaireIds !== null) {
            $query->whereIn('inventaire_id', $inventaireIds);
        }

        $query->where(function ($q) use ($clientId, $lastSync) {
            $q->where(function ($sq) use ($clientId) {
                $sq->whereNull('synced_clients')
                   ->orWhereRaw("NOT JSON_CONTAINS(synced_clients, '\"$clientId\"')");
            })->orWhere(function ($sq) use ($lastSync) {
                if ($lastSync) {
                    $lastSyncPlusOneMin = date('Y-m-d H:i:s', strtotime($lastSync . ' +1 minutes'));
                    $sq->where('updated_at', '>', $lastSyncPlusOneMin);
                }
            });
        });

        return $query->select($columns)->get();
    }

    private function markAsSyncedForClient($table, $ids, $clientId)
    {
        if (empty($ids)) return;

        foreach ($ids as $id) {
            $record = DB::table($table)->find($id);
            if (!$record) continue;

            $syncedClients = json_decode($record->synced_clients ?? '[]', true);
            if (!in_array($clientId, $syncedClients)) {
                $syncedClients[] = $clientId;
                DB::table($table)
                    ->where('id', $id)
                    ->update([
                        'synced_clients' => json_encode($syncedClients),
                        'updated_at' => now()
                    ]);
            }
        }
    }

    private function parseDate($dateString)
    {
        if (!$dateString) {
            return Carbon::now('Africa/Douala')->format('Y-m-d H:i:s');
        }
        try {
            return Carbon::parse($dateString)
                ->setTimezone('Africa/Douala')
                ->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return Carbon::now('Africa/Douala')->format('Y-m-d H:i:s');
        }
    }

    public function status(Request $request)
    {
        return response()->json(['success' => true, 'status' => 'ok']);
    }
}