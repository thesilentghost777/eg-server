<?php

namespace App\Services;

use App\Models\SessionVente;
use App\Models\Inventaire;
use App\Models\ReceptionPointeur;
use App\Models\RetourProduit;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PdgService
{
    /**
     * Obtenir les données du dashboard
     */
     public function getDashboardData($limit = 10)
    {
        Log::info('=== DEBUT getDashboardData ===', ['limit' => $limit]);
        
        try {
            $data = [
                'receptions' => $this->getRecentReceptions($limit),
                'inventaires' => $this->getRecentInventaires($limit),
                'sessions_vente' => $this->getRecentSessionsVente($limit),
                'statistiques' => $this->getStatistiquesGlobales(),
            ];
            
            Log::info('Dashboard data récupérées avec succès', [
                'nb_receptions' => count($data['receptions']),
                'nb_inventaires' => count($data['inventaires']),
                'nb_sessions' => count($data['sessions_vente'])
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('ERREUR getDashboardData', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Récupérer les réceptions récentes
     */
        private function getRecentReceptions($limit)
    {
        Log::info('--- getRecentReceptions ---', ['limit' => $limit]);
        
        try {
            // Vérifier le nombre total de réceptions dans la BD
            $totalReceptions = ReceptionPointeur::count();
            Log::info('Total réceptions dans la BD', ['count' => $totalReceptions]);
            
            // Requête avec relations
            $receptions = ReceptionPointeur::with(['pointeur', 'producteur', 'produit', 'vendeurAssigne'])
                ->orderBy('date_reception', 'desc')
                ->limit($limit)
                ->get();
            
            Log::info('Réceptions récupérées', [
                'count' => $receptions->count(),
                'first_id' => $receptions->first()?->id,
                'last_id' => $receptions->last()?->id
            ]);
            
            // Vérifier si les relations sont chargées
            if ($receptions->isNotEmpty()) {
                $firstReception = $receptions->first();
                Log::info('Première réception - relations chargées', [
                    'pointeur_loaded' => $firstReception->relationLoaded('pointeur'),
                    'producteur_loaded' => $firstReception->relationLoaded('producteur'),
                    'produit_loaded' => $firstReception->relationLoaded('produit'),
                    'vendeurAssigne_loaded' => $firstReception->relationLoaded('vendeurAssigne'),
                    'pointeur_exists' => $firstReception->pointeur !== null,
                    'producteur_exists' => $firstReception->producteur !== null,
                    'produit_exists' => $firstReception->produit !== null,
                ]);
            }
            
            $mapped = $receptions->map(function ($reception) {
                try {
                    $data = [
                        'id' => $reception->id,
                        'pointeur' => $reception->pointeur?->name ?? 'N/A',
                        'producteur' => $reception->producteur?->name ?? 'N/A',
                        'produit' => $reception->produit?->nom ?? 'N/A',
                        'categorie' => $reception->produit?->categorie ?? 'N/A',
                        'quantite' => $reception->quantite,
                        'vendeur_assigne' => $reception->vendeurAssigne ? $reception->vendeurAssigne->name : 'Non assigné',
                        'verrou' => $reception->verrou,
                        'date_reception' => $reception->date_reception,
                        'notes' => $reception->notes,
                    ];
                    
                    return $data;
                } catch (\Exception $e) {
                    Log::error('Erreur mapping réception', [
                        'reception_id' => $reception->id,
                        'error' => $e->getMessage()
                    ]);
                    return null;
                }
            })->filter();
            
            Log::info('Réceptions mappées', ['count' => $mapped->count()]);
            
            return $mapped;
            
        } catch (\Exception $e) {
            Log::error('ERREUR getRecentReceptions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Récupérer les inventaires récents
     */
       private function getRecentInventaires($limit)
    {
        Log::info('--- getRecentInventaires ---', ['limit' => $limit]);
        
        try {
            // Vérifier le nombre total d'inventaires dans la BD
            $totalInventaires = Inventaire::count();
            Log::info('Total inventaires dans la BD', ['count' => $totalInventaires]);
            
            // Requête avec relations
            $inventaires = Inventaire::with(['vendeurSortant', 'vendeurEntrant', 'details.produit'])
                ->orderBy('date_inventaire', 'desc')
                ->limit($limit)
                ->get();
            
            Log::info('Inventaires récupérés', [
                'count' => $inventaires->count(),
                'first_id' => $inventaires->first()?->id,
                'last_id' => $inventaires->last()?->id
            ]);
            
            // Vérifier si les relations sont chargées
            if ($inventaires->isNotEmpty()) {
                $firstInventaire = $inventaires->first();
                Log::info('Premier inventaire - relations chargées', [
                    'vendeurSortant_loaded' => $firstInventaire->relationLoaded('vendeurSortant'),
                    'vendeurEntrant_loaded' => $firstInventaire->relationLoaded('vendeurEntrant'),
                    'details_loaded' => $firstInventaire->relationLoaded('details'),
                    'vendeurSortant_exists' => $firstInventaire->vendeurSortant !== null,
                    'vendeurEntrant_exists' => $firstInventaire->vendeurEntrant !== null,
                    'details_count' => $firstInventaire->details->count(),
                ]);
            }
            
            $mapped = $inventaires->map(function ($inventaire) {
                try {
                    $data = [
                        'id' => $inventaire->id,
                        'vendeur_sortant' => $inventaire->vendeurSortant?->name ?? 'N/A',
                        'vendeur_entrant' => $inventaire->vendeurEntrant?->name ?? 'N/A',
                        'categorie' => $inventaire->categorie,
                        'valide_sortant' => $inventaire->valide_sortant,
                        'valide_entrant' => $inventaire->valide_entrant,
                        'statut' => $this->getStatutInventaire($inventaire),
                        'date_inventaire' => $inventaire->date_inventaire,
                        'nombre_produits' => $inventaire->details->count(),
                    ];
                    
                    return $data;
                } catch (\Exception $e) {
                    Log::error('Erreur mapping inventaire', [
                        'inventaire_id' => $inventaire->id,
                        'error' => $e->getMessage()
                    ]);
                    return null;
                }
            })->filter();
            
            Log::info('Inventaires mappés', ['count' => $mapped->count()]);
            
            return $mapped;
            
        } catch (\Exception $e) {
            Log::error('ERREUR getRecentInventaires', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Récupérer les sessions de vente récentes
     */
       private function getRecentSessionsVente($limit)
    {
        Log::info('--- getRecentSessionsVente ---', ['limit' => $limit]);
        
        try {
            // Vérifier le nombre total de sessions dans la BD
            $totalSessions = SessionVente::count();
            Log::info('Total sessions dans la BD', ['count' => $totalSessions]);
            
            $sessions = SessionVente::with(['vendeur', 'fermeePar'])
                ->orderBy('date_ouverture', 'desc')
                ->limit($limit)
                ->get();
            
            Log::info('Sessions récupérées', [
                'count' => $sessions->count(),
                'first_id' => $sessions->first()?->id,
                'last_id' => $sessions->last()?->id
            ]);
            
            $mapped = $sessions->map(function ($session) {
                try {
                    return $this->formatSessionVente($session);
                } catch (\Exception $e) {
                    Log::error('Erreur formatage session', [
                        'session_id' => $session->id,
                        'error' => $e->getMessage()
                    ]);
                    return null;
                }
            })->filter();
            
            Log::info('Sessions mappées', ['count' => $mapped->count()]);
            
            return $mapped;
            
        } catch (\Exception $e) {
            Log::error('ERREUR getRecentSessionsVente', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Obtenir les statistiques globales
     */
     private function getStatistiquesGlobales()
    {
        Log::info('--- getStatistiquesGlobales ---');
        
        try {
            $today = Carbon::today();
            Log::info('Date du jour', ['today' => $today->toDateString()]);
            
            $sessionsOuvertes = SessionVente::where('statut', 'ouverte')->count();
            Log::info('Sessions ouvertes', ['count' => $sessionsOuvertes]);
            
            $receptionsAujourdhui = ReceptionPointeur::whereDate('date_reception', $today)->count();
            Log::info('Réceptions aujourd\'hui', ['count' => $receptionsAujourdhui]);
            
            $inventairesEnAttente = Inventaire::where(function ($query) {
                $query->where('valide_sortant', false)
                      ->orWhere('valide_entrant', false);
            })->count();
            Log::info('Inventaires en attente', ['count' => $inventairesEnAttente]);
            
            $totalVentes = $this->getTotalVentesAujourdhui();
            Log::info('Total ventes aujourd\'hui', ['total' => $totalVentes]);
            
            return [
                'sessions_ouvertes' => $sessionsOuvertes,
                'receptions_aujourdhui' => $receptionsAujourdhui,
                'inventaires_en_attente' => $inventairesEnAttente,
                'total_ventes_aujourdhui' => $totalVentes,
            ];
            
        } catch (\Exception $e) {
            Log::error('ERREUR getStatistiquesGlobales', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'sessions_ouvertes' => 0,
                'receptions_aujourdhui' => 0,
                'inventaires_en_attente' => 0,
                'total_ventes_aujourdhui' => 0,
            ];
        }
    }


    /**
     * Calculer le total des ventes du jour
     */
        private function getTotalVentesAujourdhui()
    {
        Log::info('--- getTotalVentesAujourdhui ---');
        
        try {
            $sessionsFermees = SessionVente::where('statut', 'fermee')
                ->whereDate('date_fermeture', Carbon::today())
                ->get();

            Log::info('Sessions fermées aujourd\'hui', ['count' => $sessionsFermees->count()]);

            $total = 0;
            foreach ($sessionsFermees as $session) {
                $ventes = $this->calculerVentesSession($session);
                Log::info('Ventes calculées pour session', [
                    'session_id' => $session->id,
                    'ventes' => $ventes
                ]);
                $total += $ventes;
            }

            Log::info('Total ventes calculé', ['total' => $total]);
            return round($total, 2);
            
        } catch (\Exception $e) {
            Log::error('ERREUR getTotalVentesAujourdhui', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 0;
        }
    }

/**
 * MÉTHODES AUXILIAIRES (optionnelles mais recommandées)
 */

/**
 * Obtenir le dernier inventaire validé pour un vendeur et une catégorie
 */
private function getDernierInventaireValide($vendeurId, $categorie, $date)
{
    return \App\Models\Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('categorie', $categorie)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', '<=', $date)
        ->orderBy('date_inventaire', 'desc')
        ->first();
}


/**
 * Calculer les métriques de performance d'un produit
 */
private function calculerPerformanceProduit($quantiteVendue, $totalDisponible)
{
    if ($totalDisponible <= 0) {
        return [
            'taux' => 0,
            'niveau' => 'no_data',
            'couleur' => 'gray',
        ];
    }
    
    $taux = round(($quantiteVendue / $totalDisponible) * 100, 2);
    
    if ($taux >= 80) {
        return ['taux' => $taux, 'niveau' => 'excellent', 'couleur' => 'green'];
    } elseif ($taux >= 60) {
        return ['taux' => $taux, 'niveau' => 'bon', 'couleur' => 'blue'];
    } elseif ($taux >= 40) {
        return ['taux' => $taux, 'niveau' => 'moyen', 'couleur' => 'yellow'];
    } else {
        return ['taux' => $taux, 'niveau' => 'faible', 'couleur' => 'red'];
    }
}

/**
 * CORRECTION DU FILTRE VENDEUR
 * Cette méthode récupère le flux opérationnel avec un filtre vendeur FONCTIONNEL
 */
public function getFluxOperationnel($date, $vendeurId = null, $produitId = null)
{
    $dateCarbon = Carbon::parse($date)->startOfDay();
    Log::info("=== FLUX OPERATIONNEL ===", [
        'date'       => $dateCarbon->toDateString(),
        'vendeur_id' => $vendeurId,
        'produit_id' => $produitId,
    ]);

    // ─── NOUVEAU : si aucun vendeur spécifié, détecter automatiquement
    // le vendeur entrant de l'inventaire du jour (ou J-1 non fermé)
    if (!$vendeurId) {
        $inventaireJour = \App\Models\Inventaire::where('valide_entrant', true)
            ->whereDate('date_inventaire', $dateCarbon)
            ->first();

        if (!$inventaireJour) {
            // Chercher J-1 non fermé
            $dateVeille = $dateCarbon->copy()->subDay();
            $inventaireVeille = \App\Models\Inventaire::where('valide_entrant', true)
                ->whereDate('date_inventaire', $dateVeille)
                ->first();

            if ($inventaireVeille) {
                $dejfermé = \App\Models\Inventaire::where('vendeur_sortant_id', $inventaireVeille->vendeur_entrant_id)
                    ->where('valide_sortant', true)
                    ->whereDate('date_inventaire', $dateVeille)
                    ->exists();

                if (!$dejfermé) {
                    $vendeurId = $inventaireVeille->vendeur_entrant_id;
                    Log::info("Vendeur entrant auto-détecté depuis J-1", ['vendeur_id' => $vendeurId]);
                }
            }
        } else {
            $vendeurId = $inventaireJour->vendeur_entrant_id;
            Log::info("Vendeur entrant auto-détecté depuis J", ['vendeur_id' => $vendeurId]);
        }
    }

    // ─── Traitement avec vendeurId (auto ou manuel)
    if ($vendeurId) {
        $vendeur = User::where('id', $vendeurId)
            ->where('actif', true)
            ->whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
            ->first();

        if (!$vendeur) {
            Log::warning("Vendeur {$vendeurId} non trouvé ou inactif");
            return $this->emptyFlux($dateCarbon);
        }

        $fluxVendeur = $this->getFluxParVendeur($vendeur->id, $dateCarbon, $produitId);

        if (!$fluxVendeur || !$this->hasActivity($fluxVendeur['flux'])) {
            return $this->emptyFlux($dateCarbon);
        }

        $flux = [[
            'vendeur'      => ['id' => $vendeur->id, 'nom' => $vendeur->name, 'role' => $vendeur->role],
            'produits'     => $fluxVendeur['flux'],
            'total_ventes' => $fluxVendeur['total_ventes'],
        ]];

        return [
            'flux'   => $flux,
            'resume' => [
                'total_ventes'     => round($fluxVendeur['total_ventes'], 2),
                'total_produits'   => count($fluxVendeur['flux']),
                'total_receptions' => collect($fluxVendeur['flux'])->sum('quantite_recue'),
                'date'             => $dateCarbon->format('Y-m-d'),
            ],
        ];
    }

    // ─── Aucun vendeur détecté
    Log::warning("Aucun vendeur entrant trouvé pour cette date");
    return $this->emptyFlux($dateCarbon);
}

// ─── Helpers privés ──────────────────────────────────────────

private function emptyFlux(Carbon $dateCarbon): array
{
    return [
        'flux'   => [],
        'resume' => [
            'total_ventes'     => 0,
            'total_produits'   => 0,
            'total_receptions' => 0,
            'date'             => $dateCarbon->format('Y-m-d'),
        ],
    ];
}

private function hasActivity(array $flux): bool
{
    foreach ($flux as $item) {
        if (
            ($item['quantite_trouvee']  ?? 0) > 0 ||
            ($item['quantite_recue']    ?? 0) > 0 ||
            ($item['quantite_retour']   ?? 0) > 0 ||
            ($item['quantite_restante'] ?? 0) > 0 ||
            ($item['quantite_vendue']   ?? 0) > 0
        ) {
            return true;
        }
    }
    return false;
}
    
    
    /**
     * Récupère le stock initial d'un produit pour un vendeur à une date donnée
     */
     private function getStockInitial($vendeurId, $produitId, $date)
    {
        Log::info('getStockInitial', [
            'vendeur_id' => $vendeurId,
            'produit_id' => $produitId,
            'date' => $date->toDateString()
        ]);
        
        // Chercher l'inventaire de début de journée
        $inventaire = Inventaire::whereDate('date_inventaire', $date)
            ->where('vendeur_entrant_id', $vendeurId)
            ->first();
        
        if ($inventaire) {
            Log::info('Inventaire trouvé (jour même)', ['inventaire_id' => $inventaire->id]);
            
            $detail = \App\Models\InventaireDetail::where('inventaire_id', $inventaire->id)
                ->where('produit_id', $produitId)
                ->first();
            
            if ($detail) {
                Log::info('Détail inventaire trouvé', [
                    'quantite' => $detail->quantite_restante
                ]);
                return $detail->quantite_restante;
            } else {
                Log::info('Aucun détail inventaire pour ce produit');
            }
        } else {
            Log::info('Aucun inventaire trouvé (jour même)');
        }
        
        // Si pas d'inventaire, chercher l'inventaire de la veille
        $dateVeille = Carbon::parse($date)->subDay();
        $inventaireVeille = Inventaire::whereDate('date_inventaire', $dateVeille)
            ->where('vendeur_sortant_id', $vendeurId)
            ->first();
        
        if ($inventaireVeille) {
            Log::info('Inventaire trouvé (veille)', [
                'inventaire_id' => $inventaireVeille->id,
                'date' => $dateVeille->toDateString()
            ]);
            
            $detail = \App\Models\InventaireDetail::where('inventaire_id', $inventaireVeille->id)
                ->where('produit_id', $produitId)
                ->first();
            
            if ($detail) {
                Log::info('Détail inventaire trouvé (veille)', [
                    'quantite' => $detail->quantite_restante
                ]);
                return $detail->quantite_restante;
            } else {
                Log::info('Aucun détail inventaire pour ce produit (veille)');
            }
        } else {
            Log::info('Aucun inventaire trouvé (veille)');
        }
        
        Log::info('Stock initial = 0 (aucun inventaire trouvé)');
        return 0;
    }
    
    /**
     * Récupère les statistiques globales
     */
    public function getStatistiques($dateDebut = null, $dateFin = null)
    {
        if (!$dateDebut) {
            $dateDebut = Carbon::now()->startOfMonth();
        } else {
            $dateDebut = Carbon::parse($dateDebut);
        }
        
        if (!$dateFin) {
            $dateFin = Carbon::now();
        } else {
            $dateFin = Carbon::parse($dateFin);
        }
        
        $ventes = Vente::whereBetween('date_vente', [$dateDebut, $dateFin])->get();
        
        $totalVentes = $ventes->sum('montant_total');
        $totalQuantite = $ventes->sum('quantite');
        $nombreVentes = $ventes->count();
        
        return [
            'total_ventes' => $totalVentes,
            'total_quantite' => $totalQuantite,
            'nombre_ventes' => $nombreVentes,
            'periode' => [
                'debut' => $dateDebut->format('Y-m-d'),
                'fin' => $dateFin->format('Y-m-d'),
            ],
        ];
    }
    
    /**
     * Récupère la performance des vendeurs
     */
    public function getVendeursPerformance($dateDebut = null, $dateFin = null)
    {
        if (!$dateDebut) {
            $dateDebut = Carbon::now()->startOfMonth();
        } else {
            $dateDebut = Carbon::parse($dateDebut);
        }
        
        if (!$dateFin) {
            $dateFin = Carbon::now();
        } else {
            $dateFin = Carbon::parse($dateFin);
        }
        
        $vendeurs = User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
            ->where('actif', true)
            ->get();
        
        $performances = [];
        
        foreach ($vendeurs as $vendeur) {
            $ventes = Vente::whereHas('sessionVente', function($q) use ($vendeur, $dateDebut, $dateFin) {
                $q->where('vendeur_id', $vendeur->id)
                  ->whereBetween('date_ouverture', [$dateDebut, $dateFin]);
            })->get();
            
            $sessions = SessionVente::where('vendeur_id', $vendeur->id)
                ->whereBetween('date_ouverture', [$dateDebut, $dateFin])
                ->get();
            
            $performances[] = [
                'vendeur' => [
                    'id' => $vendeur->id,
                    'nom' => $vendeur->name,
                    'role' => $vendeur->role,
                ],
                'total_ventes' => $ventes->sum('montant_total'),
                'total_quantite' => $ventes->sum('quantite'),
                'nombre_sessions' => $sessions->count(),
                'moyenne_par_session' => $sessions->count() > 0 
                    ? $ventes->sum('montant_total') / $sessions->count() 
                    : 0,
            ];
        }
        
        // Trier par total de ventes
        usort($performances, function($a, $b) {
            return $b['total_ventes'] <=> $a['total_ventes'];
        });
        
        return $performances;
    }

/**
 * Obtenir le flux par vendeur
 */
public function getFluxParVendeur($vendeurId, $date = null, $produitId = null)
{
    $date = $date ?? now()->toDateString();
    $dateCarbon = Carbon::parse($date);

    Log::info("--- Flux pour vendeur {$vendeurId} ---");

    $inventaireDebut = $this->trouverInventaireDebut($vendeurId, $dateCarbon);
    if (!$inventaireDebut) {
        Log::info("Aucun inventaire début trouvé");
        return null;
    }

    $inventaireFin = $this->trouverInventaireFin($vendeurId, $dateCarbon, $inventaireDebut);

    $dateDebut = Carbon::parse($inventaireDebut->date_inventaire)->startOfDay();
    $dateFin   = $inventaireFin
        ? Carbon::parse($inventaireFin->date_inventaire)->endOfDay()
        : $dateCarbon->copy()->endOfDay();

    $dureeHeures = $dateDebut->diffInHours($dateFin);
    Log::info("Période: {$dateDebut} → {$dateFin} ({$dureeHeures}h)");

    // Date de référence pour le prix : milieu de la plage opérationnelle
    $dateRefPrix = $dateDebut->copy()->addHours((int)($dureeHeures / 2));

    $receptions = ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
        ->whereBetween('date_reception', [$dateDebut, $dateFin])
        ->with('produit')
        ->get();

    $retours = RetourProduit::where('vendeur_id', $vendeurId)
        ->whereBetween('date_retour', [$dateDebut, $dateFin])
        ->with('produit')
        ->get();

    $produitsQuery = Produit::where('actif', true);
    if ($produitId) {
        $produitsQuery->where('id', $produitId);
    }
    $produits = $produitsQuery->get();

    // Précharger les prix historiques pour tous les produits en une seule requête
    $produitIds = $produits->pluck('id')->toArray();
    $prixHistoriques = \App\Models\PrixProduitHistorique::whereIn('produit_id', $produitIds)
        ->where('date_debut', '<=', $dateRefPrix)
        ->where(function ($q) use ($dateRefPrix) {
            $q->whereNull('date_fin')->orWhere('date_fin', '>=', $dateRefPrix);
        })
        ->orderByDesc('date_debut')
        ->get()
        ->groupBy('produit_id')
        ->map(fn($rows) => (float) $rows->first()->prix);

    $flux        = [];
    $totalVentes = 0;

    foreach ($produits as $produit) {
        $quantiteTrouvee  = 0;
        $quantiteRecue    = 0;
        $quantiteRetour   = 0;
        $quantiteRestante = 0;

        if ($inventaireDebut) {
            $detail          = $inventaireDebut->details->where('produit_id', $produit->id)->first();
            $quantiteTrouvee = $detail ? $detail->quantite_restante : 0;
        }

        $quantiteRecue  = $receptions->where('produit_id', $produit->id)->sum('quantite');
        $quantiteRetour = $retours->where('produit_id', $produit->id)->sum('quantite');

        if ($inventaireFin) {
            $detail           = $inventaireFin->details->where('produit_id', $produit->id)->first();
            $quantiteRestante = $detail ? $detail->quantite_restante : 0;
        }

        $quantiteVendue = max(0, $quantiteTrouvee + $quantiteRecue - $quantiteRetour - $quantiteRestante);

        // ─── PRIX HISTORIQUE (nouveau) ───────────────────────────────
        $prixUnitaire = $prixHistoriques[$produit->id] ?? (float) $produit->prix;
        // ─────────────────────────────────────────────────────────────

        $valeurVente  = $quantiteVendue * $prixUnitaire;
        $totalVentes += $valeurVente;

        $flux[] = [
            'produit_id'         => $produit->id,
            'produit_nom'        => $produit->nom,
            'produit_categorie'  => $produit->categorie,
            'prix_unitaire'      => $prixUnitaire,   // prix historique
            'quantite_trouvee'   => $quantiteTrouvee,
            'quantite_recue'     => $quantiteRecue,
            'quantite_retour'    => $quantiteRetour,
            'quantite_restante'  => $quantiteRestante,
            'quantite_vendue'    => $quantiteVendue,
            'valeur_vente'       => round($valeurVente, 2),
        ];
    }

    return [
        'periode' => [
            'debut'        => $dateDebut->toDateTimeString(),
            'fin'          => $dateFin->toDateTimeString(),
            'date'         => $dateCarbon->toDateString(),
            'duree_heures' => $dureeHeures,
        ],
        'flux'         => $flux,
        'total_ventes' => round($totalVentes, 2),
        'stats' => [
            'has_inventaire_debut'   => (bool) $inventaireDebut,
            'has_inventaire_fin'     => (bool) $inventaireFin,
            'total_receptions'       => $receptions->count(),
            'total_retours'          => $retours->count(),
        ],
    ];
}

/**
 * Trouver l'inventaire de début (vendeur entrant)
 * Cherche sur la date demandée (J) ou la veille (J-1)
 * S'assure que l'inventaire de la veille n'a pas été fermé la veille
 */
private function trouverInventaireDebut($vendeurId, Carbon $date)
{
    // Chercher sur J
    $inventaire = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', $date)
        ->with('details')
        ->first();
    
    if ($inventaire) {
        Log::info("Inventaire entrant trouvé sur J ({$date->toDateString()})");
        return $inventaire;
    }
    
    // Chercher sur J-1
    $dateVeille = $date->copy()->subDay();
    $inventaireVeille = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', $dateVeille)
        ->with('details')
        ->first();
    
    if (!$inventaireVeille) {
        Log::info("Aucun inventaire entrant trouvé sur J-1 ({$dateVeille->toDateString()})");
        return null;
    }
    
    // Vérifier que cet inventaire n'a pas été fermé la veille
    // (c'est-à-dire qu'il n'existe pas d'inventaire sortant pour ce vendeur sur J-1)
    $inventaireFermeVeille = Inventaire::where('vendeur_sortant_id', $vendeurId)
        ->where('valide_sortant', true)
        ->whereDate('date_inventaire', $dateVeille)
        ->exists();
    
    if ($inventaireFermeVeille) {
        Log::info("L'inventaire de J-1 a déjà été fermé la veille - Non valide pour J");
        return null;
    }
    
    Log::info("Inventaire entrant trouvé sur J-1 ({$dateVeille->toDateString()}) et non fermé - Valide pour J");
    return $inventaireVeille;
}

/**
 * Trouver l'inventaire de fin (vendeur sortant)
 * Cherche sur la date demandée (J) ou le lendemain (J+1)
 * S'assure que l'inventaire du lendemain n'a pas été ouvert le lendemain
 * Vérifie aussi la cohérence avec l'inventaire de début
 */
private function trouverInventaireFin($vendeurId, Carbon $date, $inventaireDebut)
{
    // Chercher sur J
    $inventaire = Inventaire::where('vendeur_sortant_id', $vendeurId)
        ->where('valide_sortant', true)
        ->whereDate('date_inventaire', $date)
        ->with('details')
        ->first();
    
    if ($inventaire) {
        // Vérifier la cohérence: l'inventaire de fin sur J doit correspondre à l'inventaire de début
        $dateDebutInventaire = Carbon::parse($inventaireDebut->date_inventaire);
        $dateFinInventaire = Carbon::parse($inventaire->date_inventaire);
        
        // Ils doivent être sur la même date
        if ($dateDebutInventaire->isSameDay($dateFinInventaire)) {
            Log::info("Inventaire sortant trouvé sur J ({$date->toDateString()}) - Cohérent avec l'inventaire entrant");
            return $inventaire;
        } else {
            Log::warning("Inventaire sortant sur J mais incohérent avec l'inventaire entrant (dates différentes)");
            return $inventaire;
        }
    }
    
    // Chercher sur J+1
    $dateLendemain = $date->copy()->addDay();
    $inventaireLendemain = Inventaire::where('vendeur_sortant_id', $vendeurId)
        ->where('valide_sortant', true)
        ->whereDate('date_inventaire', $dateLendemain)
        ->with('details')
        ->first();
    
    if (!$inventaireLendemain) {
        Log::info("Aucun inventaire sortant trouvé sur J+1 ({$dateLendemain->toDateString()})");
        return null;
    }
    
    // Vérifier que cet inventaire n'a pas été ouvert le lendemain
    // (c'est-à-dire qu'il n'existe pas d'inventaire entrant pour ce vendeur sur J+1)
    $inventaireOuvertLendemain = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', $dateLendemain)
        ->exists();
    
    if ($inventaireOuvertLendemain) {
        Log::info("L'inventaire de J+1 a déjà été ouvert le lendemain - Non valide pour J");
        return null;
    }
    
    // Vérifier la cohérence avec l'inventaire de début
    $dateDebutInventaire = Carbon::parse($inventaireDebut->date_inventaire);
    $dateFinInventaire = Carbon::parse($inventaireLendemain->date_inventaire);
    
    // L'inventaire de début doit être J-1 ou J, et l'inventaire de fin J+1
    // La différence doit être <= 1 jour
    $diffJours = $dateDebutInventaire->diffInDays($dateFinInventaire);
    
    if ($diffJours > 1) {
        Log::warning("Inventaire sortant sur J+1 mais trop éloigné de l'inventaire entrant ({$diffJours} jours)");
    }
    
    // Vérifier que l'inventaire de début est bien J-1 si on utilise un inventaire de fin sur J+1
    if (!$dateDebutInventaire->isSameDay($date->copy()->subDay()) && !$dateDebutInventaire->isSameDay($date)) {
        Log::warning("Inventaire sortant sur J+1 mais inventaire entrant non cohérent");
    }
    
    Log::info("Inventaire sortant trouvé sur J+1 ({$dateLendemain->toDateString()}) et non ouvert - Valide pour J");
    return $inventaireLendemain;
}
    /**
     * Obtenir la liste détaillée des sessions de vente avec filtres
     */
    public function getSessionsVenteDetaillees($filters = [])
    {
        $query = SessionVente::with(['vendeur', 'fermeePar']);

        // Filtres
        if (isset($filters['vendeur_id'])) {
            $query->where('vendeur_id', $filters['vendeur_id']);
        }

        if (isset($filters['categorie'])) {
            $query->where('categorie', $filters['categorie']);
        }

        if (isset($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (isset($filters['date_debut'])) {
            $query->whereDate('date_ouverture', '>=', $filters['date_debut']);
        }

        if (isset($filters['date_fin'])) {
            $query->whereDate('date_ouverture', '<=', $filters['date_fin']);
        }

        $sessions = $query->orderBy('date_ouverture', 'desc')->get();

        return $sessions->map(function ($session) {
            return $this->getSessionAvecVentes($session);
        });
    }

    /**
     * Obtenir une session avec le détail des ventes
     */
    public function getSessionAvecVentes(SessionVente $session)
    {
        $sessionData = $this->formatSessionVente($session);
        
        if ($session->statut === 'fermee') {
            $sessionData['details_ventes'] = $this->getDetailsVentesSession($session);
            $sessionData['ventes_totales'] = $this->calculerVentesSession($session);
        } else {
            $sessionData['details_ventes'] = [];
            $sessionData['ventes_totales'] = 0;
        }

        return $sessionData;
    }

    /**
     * Obtenir les détails des ventes d'une session
     */
    private function getDetailsVentesSession(SessionVente $session)
    {
        $vendeurId = $session->vendeur_id;
        $categorie = $session->categorie;
        $dateDebut = Carbon::parse($session->date_ouverture);
        $dateFin = $session->date_fermeture ? Carbon::parse($session->date_fermeture) : now();

        $produits = Produit::where('categorie', $categorie)->where('actif', true)->get();
        $details = [];

        foreach ($produits as $produit) {
            $stockInitial = $this->getStockInitial($vendeurId, $produit->id, $dateDebut);
            $entrees = $this->getEntrees($vendeurId, $produit->id, $dateDebut, $dateFin);
            $retours = $this->getRetours($vendeurId, $produit->id, $dateDebut, $dateFin);
            $stockFinal = $this->getStockFinal($vendeurId, $produit->id, $dateDebut, $dateFin);
            $quantiteVendue = max(0, $stockInitial + $entrees - $retours - $stockFinal);
            $montantVendu = $quantiteVendue * $produit->prix;

            if ($quantiteVendue > 0 || $stockInitial > 0 || $entrees > 0) {
                $details[] = [
                    'produit_id' => $produit->id,
                    'produit_nom' => $produit->nom,
                    'prix_unitaire' => $produit->prix,
                    'stock_initial' => $stockInitial,
                    'entrees' => $entrees,
                    'retours' => $retours,
                    'stock_final' => $stockFinal,
                    'quantite_vendue' => $quantiteVendue,
                    'montant_vendu' => round($montantVendu, 2),
                ];
            }
        }

        return $details;
    }

    /**
     * Calculer les ventes totales d'une session
     */
        private function calculerVentesSession(SessionVente $session)
    {
        try {
            $details = $this->getDetailsVentesSession($session);
            return array_sum(array_column($details, 'montant_vendu'));
        } catch (\Exception $e) {
            Log::error('Erreur calculerVentesSession', [
                'session_id' => $session->id,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Formater une session de vente
     */
        private function formatSessionVente(SessionVente $session)
    {
        return [
            'id' => $session->id,
            'vendeur' => [
                'id' => $session->vendeur->id,
                'nom' => $session->vendeur->name,
            ],
            'categorie' => $session->categorie,
            'fond_vente' => $session->fond_vente,
            'orange_money_initial' => $session->orange_money_initial,
            'mtn_money_initial' => $session->mtn_money_initial,
            'orange_money_final' => $session->orange_money_final,
            'mtn_money_final' => $session->mtn_money_final,
            'montant_verse' => $session->montant_verse,
            'manquant' => $session->manquant,
            'statut' => $session->statut,
            'date_ouverture' => $session->date_ouverture,
            'date_fermeture' => $session->date_fermeture,
            'fermee_par' => $session->fermeePar ? $session->fermeePar->name : null,
        ];
    }

    

    private function getEntrees($vendeurId, $produitId, $dateDebut, $dateFin)
    {
        return ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
            ->where('produit_id', $produitId)
            ->whereBetween('date_reception', [$dateDebut, $dateFin])
            ->sum('quantite');
    }

    private function getRetours($vendeurId, $produitId, $dateDebut, $dateFin)
    {
        return RetourProduit::where('vendeur_id', $vendeurId)
            ->where('produit_id', $produitId)
            ->whereBetween('date_retour', [$dateDebut, $dateFin])
            ->sum('quantite');
    }

    private function getStockFinal($vendeurId, $produitId, $dateDebut, $dateFin)
    {
        $inventaire = Inventaire::where('vendeur_sortant_id', $vendeurId)
            ->where('valide_sortant', true)
            ->whereBetween('date_inventaire', [$dateDebut, $dateFin])
            ->orderBy('date_inventaire', 'desc')
            ->first();

        if (!$inventaire) {
            return 0;
        }

        $detail = DB::table('inventaire_details')
            ->where('inventaire_id', $inventaire->id)
            ->where('produit_id', $produitId)
            ->first();

        return $detail ? $detail->quantite_restante : 0;
    }

    /**
     * Obtenir le statut d'un inventaire
     */
     private function getStatutInventaire(Inventaire $inventaire)
    {
        if ($inventaire->valide_sortant && $inventaire->valide_entrant) {
            return 'Validé';
        } elseif ($inventaire->valide_sortant) {
            return 'Validé par sortant';
        } elseif ($inventaire->valide_entrant) {
            return 'Validé par entrant';
        }
        return 'En attente';
    }

    /**
     * Obtenir le résumé du flux
     */
    private function getResumeFlux($flux)
    {
        $totalVentes = 0;
        $totalProduits = 0;
        
        foreach ($flux as $vendeurFlux) {
            $totalVentes += $vendeurFlux['total_ventes'];
            $totalProduits += count($vendeurFlux['produits']);
        }

        return [
            'nombre_vendeurs' => count($flux),
            'total_ventes' => round($totalVentes, 2),
            'total_produits_traites' => $totalProduits,
        ];
    }
}