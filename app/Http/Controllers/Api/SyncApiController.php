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

            // Vérifier que le client existe
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

            // Vendeurs actifs
            $data['vendeurs_actifs'] = $this->getUnsyncedData('vendeurs_actifs', $clientId, $lastSyncCarbon,
                ['id', 'categorie', 'vendeur_id', 'connecte_a', 'updated_at']);

            // Réceptions
            $data['receptions_pointeur'] = $this->getUnsyncedData('receptions_pointeur', $clientId, $lastSyncCarbon,
                ['id', 'pointeur_id', 'producteur_id', 'produit_id', 'quantite', 'vendeur_assigne_id', 
                 'verrou', 'date_reception', 'notes', 'updated_at']);

            // Retours
            $data['retours_produits'] = $this->getUnsyncedData('retours_produits', $clientId, $lastSyncCarbon,
                ['id', 'pointeur_id', 'vendeur_id', 'produit_id', 'quantite', 'raison', 
                 'description', 'verrou', 'date_retour', 'updated_at']);

            //on n'envoie plus les inventaires et les sessions dans les pull
            /*
            // Inventaires
            $data['inventaires'] = $this->getUnsyncedData('inventaires', $clientId, $lastSyncCarbon,
                ['id', 'vendeur_sortant_id', 'vendeur_entrant_id', 'categorie', 
                 'valide_sortant', 'valide_entrant', 'date_inventaire', 'updated_at']);

            // Détails inventaire
            if (!empty($data['inventaires']) && count($data['inventaires']) > 0) {
                $inventaireIds = collect($data['inventaires'])->pluck('id');
                $data['inventaire_details'] = $this->getUnsyncedData('inventaire_details', $clientId, $lastSyncCarbon,
                    ['id', 'inventaire_id', 'produit_id', 'quantite_restante', 'updated_at'],
                    null, $inventaireIds->toArray());
            } else {
                $data['inventaire_details'] = [];
            }
            */

            // Sessions
            $data['sessions_vente'] = $this->getUnsyncedData('sessions_vente', $clientId, $lastSyncCarbon,
                ['id', 'vendeur_id', 'categorie', 'fond_vente', 'orange_money_initial', 'mtn_money_initial',
                 'montant_verse', 'orange_money_final', 'mtn_money_final', 'manquant', 'statut',
                 'fermee_par', 'date_ouverture', 'date_fermeture', 'updated_at']);

            
            \Log::info('PULL - Données récupérées', [
                'client_id' => $clientId,
                'data' => $data
            ]);

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

    /**
     * Push: Recevoir les modifications du client
     * POST /api/sync/push
     */
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
                foreach ($data['receptions'] as $reception) {
                    $result = $this->syncReception($reception, $clientId);
                    if ($result['success']) {
                        $synced[] = [
                            'table' => 'receptions_pointeur',
                            'id' => $reception['id'] ?? null,
                            'server_id' => $result['id'],
                        ];
                    } else {
                        $conflicts[] = [
                            'table' => 'receptions_pointeur',
                            'id' => $reception['id'] ?? null,
                            'reason' => $result['reason'],
                        ];
                        $hasErrors = true;
                    }
                }
            }

            // Retours
            if (!empty($data['retours'])) {
                foreach ($data['retours'] as $retour) {
                    $result = $this->syncRetour($retour, $clientId);
                    if ($result['success']) {
                        $synced[] = [
                            'table' => 'retours_produits',
                            'id' => $retour['id'] ?? null,
                            'server_id' => $result['id'],
                        ];
                    } else {
                        $conflicts[] = [
                            'table' => 'retours_produits',
                            'id' => $retour['id'] ?? null,
                            'reason' => $result['reason'],
                        ];
                        $hasErrors = true;
                    }
                }
            }

            // Inventaires
            if (!empty($data['inventaires'])) {
                foreach ($data['inventaires'] as $inventaire) {
                    $result = $this->syncInventaire($inventaire, $clientId);
                    if ($result['success']) {
                        $synced[] = [
                            'table' => 'inventaires',
                            'id' => $inventaire['id'] ?? null,
                            'server_id' => $result['id'],
                        ];
                    } else {
                        $conflicts[] = [
                            'table' => 'inventaires',
                            'id' => $inventaire['id'] ?? null,
                            'reason' => $result['reason'],
                        ];
                        $hasErrors = true;
                    }
                }
            }

            // Inventaire Details
            if (!empty($data['inventaire_details'])) {
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
                foreach ($data['sessions'] as $session) {
                    $result = $this->syncSession($session, $clientId);
                    if ($result['success']) {
                        $synced[] = [
                            'table' => 'sessions_vente',
                            'id' => $session['id'] ?? null,
                            'server_id' => $result['id'],
                        ];
                    } else {
                        $conflicts[] = [
                            'table' => 'sessions_vente',
                            'id' => $session['id'] ?? null,
                            'reason' => $result['reason'],
                        ];
                        $hasErrors = true;
                    }
                }
            }

            // Si des erreurs critiques, rollback
            if ($hasErrors && empty($synced)) {
                DB::rollBack();
                
                \Log::warning('PUSH échoué - aucune donnée synchronisée', [
                    'client_id' => $clientId,
                    'conflicts' => count($conflicts)
                ]);

                return response()->json([
                    'success' => false,
                    'confirmed' => false,
                    'message' => 'Échec de synchronisation',
                    'synced' => [],
                    'conflicts' => $conflicts,
                    'sync_time' => now()->toIso8601String(),
                ], 422);
            }

            // Commit uniquement si tout est OK
            DB::commit();

            \Log::info('PUSH confirmé', [
                'client_id' => $clientId,
                'synced' => count($synced),
                'conflicts' => count($conflicts)
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

/**
 * Synchroniser un détail d'inventaire
 */
/**
 * Synchroniser un détail d'inventaire
 */
private function syncInventaireDetails($data, $clientId)
{
    try {
        \Log::info('[SYNC INVENTAIRE DETAILS] Début de la synchronisation', [
            'client_id' => $clientId,
            'payload' => $data
        ]);

        // 🔹 Validation : produit_id obligatoire
        if (empty($data['produit_id'])) {
            \Log::error('[SYNC INVENTAIRE DETAILS] produit_id manquant', [
                'payload' => $data
            ]);
            return ['success' => false, 'reason' => 'produit_id manquant'];
        }

        // 🔹 NOUVELLE LOGIQUE : Résoudre inventaire_id à partir de inventaire_local_id
        $inventaire_id = null;
        
        if (!empty($data['inventaire_id'])) {
            // Cas 1 : inventaire_id est fourni directement
            $inventaire_id = $data['inventaire_id'];
            \Log::debug('[SYNC INVENTAIRE DETAILS] inventaire_id fourni', [
                'inventaire_id' => $inventaire_id
            ]);
        } elseif (!empty($data['inventaire_local_id'])) {
            // Cas 2 : inventaire_local_id fourni, on cherche l'inventaire correspondant pour ce client
            \Log::debug('[SYNC INVENTAIRE DETAILS] Résolution via inventaire_local_id', [
                'inventaire_local_id' => $data['inventaire_local_id']
            ]);
            
            // Chercher l'inventaire créé par ce client dans cette session de sync
            // On cherche le dernier inventaire créé/mis à jour pour ce client
            $inventaire = DB::table('inventaires')
                ->whereRaw("JSON_CONTAINS(synced_clients, '\"$clientId\"')")
                ->orderBy('updated_at', 'desc')
                ->first();
            
            if ($inventaire) {
                $inventaire_id = $inventaire->id;
                \Log::info('[SYNC INVENTAIRE DETAILS] Inventaire résolu', [
                    'inventaire_local_id' => $data['inventaire_local_id'],
                    'inventaire_id' => $inventaire_id
                ]);
            } else {
                \Log::error('[SYNC INVENTAIRE DETAILS] Inventaire parent introuvable via local_id', [
                    'inventaire_local_id' => $data['inventaire_local_id'],
                    'client_id' => $clientId
                ]);
                return ['success' => false, 'reason' => 'Inventaire parent introuvable'];
            }
        } else {
            \Log::error('[SYNC INVENTAIRE DETAILS] Ni inventaire_id ni inventaire_local_id fourni', [
                'payload' => $data
            ]);
            return ['success' => false, 'reason' => 'inventaire_id ou inventaire_local_id manquant'];
        }

        // 🔹 Vérification de l'existence de l'inventaire
        $inventaire = DB::table('inventaires')->find($inventaire_id);
        
        if (!$inventaire) {
            \Log::error('[SYNC INVENTAIRE DETAILS] Inventaire parent introuvable', [
                'inventaire_id' => $inventaire_id
            ]);
            return ['success' => false, 'reason' => 'Inventaire parent introuvable'];
        }

        // 🔹 Vérification de l'existence du produit
        $produit = DB::table('produits')->find($data['produit_id']);
        
        if (!$produit) {
            \Log::error('[SYNC INVENTAIRE DETAILS] Produit introuvable', [
                'produit_id' => $data['produit_id']
            ]);
            return ['success' => false, 'reason' => 'Produit introuvable'];
        }

        // 🔹 Construction des données communes
        $commonData = [
            'inventaire_id' => $inventaire_id,
            'produit_id' => $data['produit_id'],
            'quantite_restante' => $data['quantite_restante'] ?? 0,
        ];

        \Log::debug('[SYNC INVENTAIRE DETAILS] Données communes préparées', $commonData);

        // 🔹 Vérification d'un doublon (même inventaire + même produit)
        $existing = DB::table('inventaire_details')
            ->where('inventaire_id', $inventaire_id)
            ->where('produit_id', $data['produit_id'])
            ->first();

        if ($existing) {
            \Log::info('[SYNC INVENTAIRE DETAILS] Enregistrement existant trouvé, mise à jour', [
                'id' => $existing->id
            ]);

            // Gestion de la synchronisation client
            $syncedClients = json_decode($existing->synced_clients ?? '[]', true);
            if (!in_array($clientId, $syncedClients)) {
                $syncedClients[] = $clientId;
                \Log::debug('[SYNC INVENTAIRE DETAILS] Ajout du client à synced_clients', [
                    'id' => $existing->id,
                    'synced_clients' => $syncedClients
                ]);
            }

            $commonData['synced_clients'] = json_encode($syncedClients);
            $commonData['updated_at'] = now();

            DB::table('inventaire_details')
                ->where('id', $existing->id)
                ->update($commonData);

            \Log::info('[SYNC INVENTAIRE DETAILS] Mise à jour réussie', [
                'id' => $existing->id,
                'client_id' => $clientId
            ]);

            return ['success' => true, 'id' => $existing->id];
        }

        // 🔹 Création d'un nouveau détail d'inventaire
        $commonData['synced_clients'] = json_encode([$clientId]);
        $commonData['created_at'] = now();
        $commonData['updated_at'] = now();

        \Log::info('[SYNC INVENTAIRE DETAILS] Création d\'un nouveau détail', $commonData);

        $id = DB::table('inventaire_details')->insertGetId($commonData);

        \Log::info('[SYNC INVENTAIRE DETAILS] Création réussie', [
            'id' => $id,
            'client_id' => $clientId
        ]);

        return ['success' => true, 'id' => $id];

    } catch (\Exception $e) {
        \Log::error('[SYNC INVENTAIRE DETAILS] Exception critique', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'data' => $data,
            'client_id' => $clientId
        ]);

        return ['success' => false, 'reason' => $e->getMessage()];
    }
}
    // ========== MÉTHODES PRIVÉES ==========

    /**
     * Récupérer les données non synchronisées pour un client
     */
private function getUnsyncedData($table, $clientId, $lastSync, $columns, $additionalWhere = null, $inventaireIds = null)
{
    $query = DB::table($table);
    
    // Filtre additionnel (ex: actif = true)
    if ($additionalWhere) {
        foreach ($additionalWhere as $key => $value) {
            $query->where($key, $value);
        }
    }
    
    // ✅ Filtre : ignorer les utilisateurs dont le rôle est "pdg"
    if ($table === 'users') {
        $query->where('role', '!=', 'pdg');
    }
    
    // Filtre pour inventaire_details
    if ($inventaireIds !== null) {
        $query->whereIn('inventaire_id', $inventaireIds);
    }
    
    // ✅ NOUVELLE LOGIQUE: Non synced OU updated_at > last_sync + 1 minutes
    $query->where(function($q) use ($clientId, $lastSync) {
        // Condition 1: Non synced par le client (synced_clients null ou ne contient pas ce client_id)
        $q->where(function($sq) use ($clientId) {
            $sq->whereNull('synced_clients')
               ->orWhereRaw("NOT JSON_CONTAINS(synced_clients, '\"$clientId\"')");
        })
        // OU Condition 2: updated_at > last_sync + 5 minutes
        ->orWhere(function($sq) use ($lastSync) {
            if ($lastSync) {
                // Ajoute 1 minutes à last_sync
                $lastSyncPlusOneMin = date('Y-m-d H:i:s', strtotime($lastSync . ' +1 minutes'));
                $sq->where('updated_at', '>', $lastSyncPlusOneMin);
            }
        });
    });
    
    return $query->select($columns)->get();
}

    /**
     * Marquer comme synchronisé pour un client spécifique
     */
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

    /**
     * Synchroniser une réception
     */
    private function syncReception($data, $clientId)
    {
        try {
            $dateReception = $this->parseDate($data['date_reception'] ?? null);

            $commonData = [
                'pointeur_id' => $data['pointeur_id'],
                'producteur_id' => $data['producteur_id'],
                'produit_id' => $data['produit_id'],
                'quantite' => $data['quantite'],
                'vendeur_assigne_id' => $data['vendeur_assigne_id'] ?? null,
                'verrou' => $data['verrou'] ?? false,
                'date_reception' => $dateReception,
                'notes' => $data['notes'] ?? null,
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

                    DB::table('receptions_pointeur')
                        ->where('id', $data['id'])
                        ->update($commonData);

                    return ['success' => true, 'id' => $data['id']];
                }
            }

            // Création
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

    /**
     * Synchroniser un retour
     */
    private function syncRetour($data, $clientId)
{
    try {
        // 🔹 Étape 1 : Log des données brutes reçues
        \Log::info('[SYNC RETOUR] Début de la synchronisation', [
            'client_id' => $clientId,
            'payload' => $data
        ]);

        // 🔹 Étape 2 : Validation de la date
        $dateRetour = $this->parseDate($data['date_retour'] ?? null);
        \Log::debug('[SYNC RETOUR] Date retour parsée', ['date_retour' => $dateRetour]);

        // 🔹 Étape 3 : Construction des données communes
        $commonData = [
            'pointeur_id'  => $data['pointeur_id'] ?? null,
            'vendeur_id'   => $data['vendeur_id'] ?? null,
            'produit_id'   => $data['produit_id'] ?? null,
            'quantite'     => $data['quantite'] ?? null,
            'raison'       => $data['raison'] ?? null,
            'description'  => $data['description'] ?? null,
            'verrou'       => $data['verrou'] ?? false,
            'date_retour'  => $dateRetour,
        ];

        \Log::debug('[SYNC RETOUR] Données communes préparées', $commonData);

        // 🔹 Étape 4 : Cas mise à jour d’un retour existant
        if (isset($data['id']) && is_numeric($data['id']) && $data['id'] > 0) {
            \Log::info('[SYNC RETOUR] Tentative de mise à jour', ['id' => $data['id']]);

            $existing = DB::table('retours_produits')->find($data['id']);

            if ($existing) {
                if ($existing->verrou) {
                    \Log::warning('[SYNC RETOUR] Enregistrement verrouillé, mise à jour annulée', [
                        'id' => $data['id']
                    ]);
                    return ['success' => false, 'reason' => 'Verrouillé'];
                }

                // 🔹 Gestion de la synchronisation client
                $syncedClients = json_decode($existing->synced_clients ?? '[]', true);
                if (!in_array($clientId, $syncedClients)) {
                    $syncedClients[] = $clientId;
                    \Log::debug('[SYNC RETOUR] Ajout du client à synced_clients', [
                        'id' => $data['id'],
                        'synced_clients' => $syncedClients
                    ]);
                }

                $commonData['synced_clients'] = json_encode($syncedClients);
                $commonData['updated_at'] = now();

                DB::table('retours_produits')->where('id', $data['id'])->update($commonData);
                \Log::info('[SYNC RETOUR] Mise à jour réussie', [
                    'id' => $data['id'],
                    'client_id' => $clientId
                ]);

                return ['success' => true, 'id' => $data['id']];
            } else {
                \Log::warning('[SYNC RETOUR] Aucun enregistrement trouvé avec cet ID', [
                    'id' => $data['id']
                ]);
            }
        }

        // 🔹 Étape 5 : Cas création d’un nouveau retour
        $commonData['synced_clients'] = json_encode([$clientId]);
        $commonData['created_at'] = now();
        $commonData['updated_at'] = now();

        \Log::info('[SYNC RETOUR] Création d’un nouveau retour', $commonData);

        $id = DB::table('retours_produits')->insertGetId($commonData);

        \Log::info('[SYNC RETOUR] Création réussie', [
            'id' => $id,
            'client_id' => $clientId
        ]);

        return ['success' => true, 'id' => $id];

    } catch (\Exception $e) {
        // 🔹 Étape 6 : Log d’erreur détaillé
        \Log::error('[SYNC RETOUR] Exception critique', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'data' => $data,
            'client_id' => $clientId
        ]);

        return ['success' => false, 'reason' => $e->getMessage()];
    }
}

    /**
     * Synchroniser un inventaire
     */
    private function syncInventaire($data, $clientId)
{
    try {
        // 🔹 Étape 1 : Log des données brutes reçues
        \Log::info('[SYNC INVENTAIRE] Début de la synchronisation', [
            'client_id' => $clientId,
            'payload' => $data
        ]);

        // 🔹 Étape 2 : Récupération du vendeur sortant pour déterminer la catégorie
        $vendeurSortant = DB::table('users')->find($data['vendeur_sortant_id'] ?? null);
        
        if (!$vendeurSortant) {
            \Log::error('[SYNC INVENTAIRE] Vendeur sortant introuvable', [
                'vendeur_sortant_id' => $data['vendeur_sortant_id'] ?? null
            ]);
            return ['success' => false, 'reason' => 'Vendeur sortant introuvable'];
        }

        // 🔹 Étape 3 : Détermination de la catégorie basée sur le rôle du vendeur sortant
        $categorie = ($vendeurSortant->role === 'vendeur_patisserie') ? 'patisserie' : 'boulangerie';
        
        \Log::debug('[SYNC INVENTAIRE] Catégorie déterminée', [
            'role_vendeur' => $vendeurSortant->role,
            'categorie' => $categorie
        ]);

        // 🔹 Étape 4 : Construction des données communes
        $commonData = [
            'vendeur_sortant_id' => $data['vendeur_sortant_id'] ?? null,
            'vendeur_entrant_id' => $data['vendeur_entrant_id'] ?? null,
            'categorie'          => $categorie,
            'valide_sortant'     => true, // Mis à true pendant l'opération
            'valide_entrant'     => true, // Mis à true pendant l'opération
            'date_inventaire'    => now(), // Généré côté backend
        ];

        \Log::debug('[SYNC INVENTAIRE] Données communes préparées', $commonData);

        // 🔹 Étape 5 : Cas mise à jour d'un inventaire existant
        if (isset($data['id']) && is_numeric($data['id']) && $data['id'] > 0) {
            \Log::info('[SYNC INVENTAIRE] Tentative de mise à jour', ['id' => $data['id']]);

            $existing = DB::table('inventaires')->find($data['id']);

            if ($existing) {
                // 🔹 Gestion de la synchronisation client
                $syncedClients = json_decode($existing->synced_clients ?? '[]', true);
                if (!in_array($clientId, $syncedClients)) {
                    $syncedClients[] = $clientId;
                    \Log::debug('[SYNC INVENTAIRE] Ajout du client à synced_clients', [
                        'id' => $data['id'],
                        'synced_clients' => $syncedClients
                    ]);
                }

                $commonData['synced_clients'] = json_encode($syncedClients);
                $commonData['updated_at'] = now();

                DB::table('inventaires')->where('id', $data['id'])->update($commonData);
                \Log::info('[SYNC INVENTAIRE] Mise à jour réussie', [
                    'id' => $data['id'],
                    'client_id' => $clientId
                ]);

                return ['success' => true, 'id' => $data['id']];
            } else {
                \Log::warning('[SYNC INVENTAIRE] Aucun enregistrement trouvé avec cet ID', [
                    'id' => $data['id']
                ]);
            }
        }

        // 🔹 Étape 6 : Cas création d'un nouveau inventaire
        $commonData['synced_clients'] = json_encode([$clientId]);
        $commonData['created_at'] = now();
        $commonData['updated_at'] = now();

        Log::info('[SYNC INVENTAIRE] Création dun nouveau inventaire', $commonData);

        $id = DB::table('inventaires')->insertGetId($commonData);

        Log::info('[SYNC INVENTAIRE] Création réussie', [
            'id' => $id,
            'client_id' => $clientId
        ]);

        return ['success' => true, 'id' => $id];

    } catch (\Exception $e) {
        // 🔹 Étape 7 : Log d'erreur détaillé
        \Log::error('[SYNC INVENTAIRE] Exception critique', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'data' => $data,
            'client_id' => $clientId
        ]);

        return ['success' => false, 'reason' => $e->getMessage()];
    }
}


    /**
     * Synchroniser une session
     */
    private function syncSession($data, $clientId)
    {
        try {
            $dateOuverture = $this->parseDate($data['date_ouverture'] ?? null);
            $dateFermeture = isset($data['date_fermeture']) ? $this->parseDate($data['date_fermeture']) : null;

            $commonData = [
                'vendeur_id' => $data['vendeur_id'],
                'categorie' => $data['categorie'],
                'fond_vente' => $data['fond_vente'] ?? 0,
                'orange_money_initial' => $data['orange_money_initial'] ?? 0,
                'mtn_money_initial' => $data['mtn_money_initial'] ?? 0,
                'montant_verse' => $data['montant_verse'] ?? null,
                'orange_money_final' => $data['orange_money_final'] ?? null,
                'mtn_money_final' => $data['mtn_money_final'] ?? null,
                'manquant' => $data['manquant'] ?? null,
                'statut' => $data['statut'] ?? 'ouverte',
                'fermee_par' => $data['fermee_par'] ?? null,
                'date_ouverture' => $dateOuverture,
                'date_fermeture' => $dateFermeture,
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

    /**
     * Synchroniser une vente
     */
    private function syncVente($data, $clientId)
    {
        try {
            $dateVente = $this->parseDate($data['date_vente'] ?? null);

            $commonData = [
                'session_vente_id' => $data['session_vente_id'],
                'produit_id' => $data['produit_id'],
                'quantite' => $data['quantite'],
                'prix_unitaire' => $data['prix_unitaire'],
                'montant_total' => $data['montant_total'],
                'mode_paiement' => $data['mode_paiement'] ?? 'cash',
                'date_vente' => $dateVente,
            ];

            if (isset($data['id']) && is_numeric($data['id']) && $data['id'] > 0) {
                $existing = DB::table('ventes')->find($data['id']);
                
                if ($existing) {
                    $syncedClients = json_decode($existing->synced_clients ?? '[]', true);
                    if (!in_array($clientId, $syncedClients)) {
                        $syncedClients[] = $clientId;
                    }

                    $commonData['synced_clients'] = json_encode($syncedClients);
                    $commonData['updated_at'] = now();

                    DB::table('ventes')->where('id', $data['id'])->update($commonData);
                    return ['success' => true, 'id' => $data['id']];
                }
            }

            $commonData['synced_clients'] = json_encode([$clientId]);
            $commonData['created_at'] = now();
            $commonData['updated_at'] = now();

            $id = DB::table('ventes')->insertGetId($commonData);
            return ['success' => true, 'id' => $id];

        } catch (\Exception $e) {
            return ['success' => false, 'reason' => $e->getMessage()];
        }
    }

    /**
     * Parser une date ISO 8601 vers format MySQL
     */
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
}
