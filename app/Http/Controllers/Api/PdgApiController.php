<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PdgService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PdgApiController extends Controller
{
    protected $pdgService;

    public function __construct(PdgService $pdgService)
    {
        $this->pdgService = $pdgService;
    }

    /**
     * Obtenir les données du dashboard PDG
     * GET /api/pdg/dashboard
     */
    public function dashboard(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            
            $data = $this->pdgService->getDashboardData($limit);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dashboard PDG', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données du dashboard',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Filtrer les réceptions
     * GET /api/pdg/receptions
     */
    public function getReceptions(Request $request)
    {
        try {
            $validated = $request->validate([
                'pointeur_id' => 'nullable|exists:users,id',
                'producteur_id' => 'nullable|exists:users,id',
                'vendeur_id' => 'nullable|exists:users,id',
                'produit_id' => 'nullable|exists:produits,id',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
                'verrou' => 'nullable|boolean',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            $query = \App\Models\ReceptionPointeur::with([
                'pointeur', 
                'producteur', 
                'produit', 
                'vendeurAssigne'
            ]);

            // Appliquer les filtres
            if (isset($validated['pointeur_id'])) {
                $query->where('pointeur_id', $validated['pointeur_id']);
            }

            if (isset($validated['producteur_id'])) {
                $query->where('producteur_id', $validated['producteur_id']);
            }

            if (isset($validated['vendeur_id'])) {
                $query->where('vendeur_assigne_id', $validated['vendeur_id']);
            }

            if (isset($validated['produit_id'])) {
                $query->where('produit_id', $validated['produit_id']);
            }

            if (isset($validated['date_debut'])) {
                $query->whereDate('date_reception', '>=', $validated['date_debut']);
            }

            if (isset($validated['date_fin'])) {
                $query->whereDate('date_reception', '<=', $validated['date_fin']);
            }

            if (isset($validated['verrou'])) {
                $query->where('verrou', $validated['verrou']);
            }

            $perPage = $validated['per_page'] ?? 20;
            $receptions = $query->orderBy('date_reception', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $receptions,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur filtrage réceptions', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du filtrage des réceptions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Filtrer les inventaires
     * GET /api/pdg/inventaires
     */
    public function getInventaires(Request $request)
    {
        try {
            $validated = $request->validate([
                'vendeur_sortant_id' => 'nullable|exists:users,id',
                'vendeur_entrant_id' => 'nullable|exists:users,id',
                'categorie' => 'nullable|in:boulangerie,patisserie',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
                'statut' => 'nullable|in:valide,en_attente',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            $query = \App\Models\Inventaire::with([
                'vendeurSortant', 
                'vendeurEntrant', 
                'details.produit'
            ]);

            // Appliquer les filtres
            if (isset($validated['vendeur_sortant_id'])) {
                $query->where('vendeur_sortant_id', $validated['vendeur_sortant_id']);
            }

            if (isset($validated['vendeur_entrant_id'])) {
                $query->where('vendeur_entrant_id', $validated['vendeur_entrant_id']);
            }

            if (isset($validated['categorie'])) {
                $query->where('categorie', $validated['categorie']);
            }

            if (isset($validated['date_debut'])) {
                $query->whereDate('date_inventaire', '>=', $validated['date_debut']);
            }

            if (isset($validated['date_fin'])) {
                $query->whereDate('date_inventaire', '<=', $validated['date_fin']);
            }

            if (isset($validated['statut'])) {
                if ($validated['statut'] === 'valide') {
                    $query->where('valide_sortant', true)
                          ->where('valide_entrant', true);
                } else {
                    $query->where(function ($q) {
                        $q->where('valide_sortant', false)
                          ->orWhere('valide_entrant', false);
                    });
                }
            }

            $perPage = $validated['per_page'] ?? 20;
            $inventaires = $query->orderBy('date_inventaire', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $inventaires,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur filtrage inventaires', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du filtrage des inventaires',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Filtrer les sessions de vente
     * GET /api/pdg/sessions-vente
     */
    public function getSessionsVente(Request $request)
    {
        try {
            $validated = $request->validate([
                'vendeur_id' => 'nullable|exists:users,id',
                'categorie' => 'nullable|in:boulangerie,patisserie',
                'statut' => 'nullable|in:ouverte,fermee',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            $query = \App\Models\SessionVente::with(['vendeur', 'fermeePar']);

            // Appliquer les filtres
            if (isset($validated['vendeur_id'])) {
                $query->where('vendeur_id', $validated['vendeur_id']);
            }

            if (isset($validated['categorie'])) {
                $query->where('categorie', $validated['categorie']);
            }

            if (isset($validated['statut'])) {
                $query->where('statut', $validated['statut']);
            }

            if (isset($validated['date_debut'])) {
                $query->whereDate('date_ouverture', '>=', $validated['date_debut']);
            }

            if (isset($validated['date_fin'])) {
                $query->whereDate('date_ouverture', '<=', $validated['date_fin']);
            }

            $perPage = $validated['per_page'] ?? 20;
            $sessions = $query->orderBy('date_ouverture', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $sessions,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur filtrage sessions de vente', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du filtrage des sessions de vente',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir le flux opérationnel détaillé pour une date
     * GET /api/pdg/flux-operationnel
     * Paramètres: date (required), vendeur_id (optional), produit_id (optional)
     */
    public function getFluxOperationnel(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'vendeur_id' => 'nullable|exists:users,id',
                'produit_id' => 'nullable|exists:produits,id',
            ]);

            $flux = $this->pdgService->getFluxOperationnel(
                $validated['date'],
                $validated['vendeur_id'] ?? null,
                $validated['produit_id'] ?? null
            );

            return response()->json([
                'success' => true,
                'data' => $flux,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur flux opérationnel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du flux opérationnel',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir le flux détaillé pour impression
     * GET /api/pdg/flux-operationnel/imprimer
     */
    public function imprimerFluxOperationnel(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'vendeur_id' => 'nullable|exists:users,id',
                'produit_id' => 'nullable|exists:produits,id',
            ]);

            $flux = $this->pdgService->getFluxOperationnel(
                $validated['date'],
                $validated['vendeur_id'] ?? null,
                $validated['produit_id'] ?? null
            );

            // Formater pour l'impression
            $fluxImpression = [
                'titre' => 'Flux Opérationnel Détaillé',
                'date' => $validated['date'],
                'date_impression' => now()->format('d/m/Y H:i'),
                'filtres' => [
                    'vendeur' => $validated['vendeur_id'] ?? 'Tous',
                    'produit' => $validated['produit_id'] ?? 'Tous',
                ],
                'data' => $flux,
            ];

            return response()->json([
                'success' => true,
                'data' => $fluxImpression,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur impression flux', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la préparation du flux pour impression',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir les sessions de vente détaillées avec les ventes
     * GET /api/pdg/sessions-vente-detaillees
     */
    public function getSessionsVenteDetaillees(Request $request)
    {
        try {
            $validated = $request->validate([
                'vendeur_id' => 'nullable|exists:users,id',
                'categorie' => 'nullable|in:boulangerie,patisserie',
                'statut' => 'nullable|in:ouverte,fermee',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
            ]);

            $sessions = $this->pdgService->getSessionsVenteDetaillees($validated);

            return response()->json([
                'success' => true,
                'data' => $sessions,
                'total' => $sessions->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur sessions détaillées', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sessions détaillées',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir une session spécifique avec détails pour impression
     * GET /api/pdg/sessions-vente/{id}/imprimer
     */
    public function imprimerSessionVente($id)
    {
        try {
            $session = \App\Models\SessionVente::with(['vendeur', 'fermeePar'])
                ->findOrFail($id);

            $sessionDetaillée = $this->pdgService->getSessionAvecVentes($session);

            // Formater pour l'impression
            $sessionImpression = [
                'titre' => 'Détails de la Session de Vente',
                'date_impression' => now()->format('d/m/Y H:i'),
                'session' => $sessionDetaillée,
                'resume_financier' => $this->calculerResumeFinancier($sessionDetaillée),
            ];

            return response()->json([
                'success' => true,
                'data' => $sessionImpression,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur impression session', [
                'session_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la préparation de la session pour impression',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Obtenir toutes les sessions détaillées pour impression
     * GET /api/pdg/sessions-vente-detaillees/imprimer
     */
    public function imprimerSessionsVenteDetaillees(Request $request)
    {
        try {
            $validated = $request->validate([
                'vendeur_id' => 'nullable|exists:users,id',
                'categorie' => 'nullable|in:boulangerie,patisserie',
                'statut' => 'nullable|in:ouverte,fermee',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
            ]);

            $sessions = $this->pdgService->getSessionsVenteDetaillees($validated);

            // Calculer les totaux globaux
            $totauxGlobaux = [
                'nombre_sessions' => $sessions->count(),
                'total_ventes' => $sessions->sum('ventes_totales'),
                'total_manquant' => $sessions->where('statut', 'fermee')->sum('manquant'),
                'total_verse' => $sessions->where('statut', 'fermee')->sum('montant_verse'),
            ];

            // Formater pour l'impression
            $rapport = [
                'titre' => 'Rapport Détaillé des Sessions de Vente',
                'date_impression' => now()->format('d/m/Y H:i'),
                'filtres' => [
                    'vendeur' => $validated['vendeur_id'] ?? 'Tous',
                    'categorie' => $validated['categorie'] ?? 'Toutes',
                    'statut' => $validated['statut'] ?? 'Tous',
                    'periode' => [
                        'debut' => $validated['date_debut'] ?? 'Début',
                        'fin' => $validated['date_fin'] ?? 'Aujourd\'hui',
                    ],
                ],
                'totaux' => $totauxGlobaux,
                'sessions' => $sessions,
            ];

            return response()->json([
                'success' => true,
                'data' => $rapport,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur impression sessions détaillées', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la préparation du rapport',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques globales pour le PDG
     * GET /api/pdg/statistiques
     */
    public function getStatistiques(Request $request)
    {
        try {
            $validated = $request->validate([
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
            ]);

            $dateDebut = $validated['date_debut'] ?? now()->startOfMonth()->toDateString();
            $dateFin = $validated['date_fin'] ?? now()->toDateString();

            $stats = [
                'sessions_ouvertes' => \App\Models\SessionVente::where('statut', 'ouverte')->count(),
                'sessions_fermees_periode' => \App\Models\SessionVente::where('statut', 'fermee')
                    ->whereBetween('date_fermeture', [$dateDebut, $dateFin])
                    ->count(),
                'ventes_periode' => $this->calculerVentesPeriode($dateDebut, $dateFin),
                'receptions_periode' => \App\Models\ReceptionPointeur::whereBetween('date_reception', [$dateDebut, $dateFin])
                    ->sum('quantite'),
                'retours_periode' => \App\Models\RetourProduit::whereBetween('date_retour', [$dateDebut, $dateFin])
                    ->sum('quantite'),
                'inventaires_en_attente' => \App\Models\Inventaire::where(function ($query) {
                    $query->where('valide_sortant', false)
                          ->orWhere('valide_entrant', false);
                })->count(),
                'vendeurs_actifs' => \App\Models\User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
                    ->whereHas('sessionsVenteActuelles', function ($query) {
                        $query->where('statut', 'ouverte');
                    })
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'periode' => [
                    'debut' => $dateDebut,
                    'fin' => $dateFin,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur statistiques PDG', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des statistiques',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtenir la liste des vendeurs avec leurs performances
     * GET /api/pdg/vendeurs-performance
     */
    public function getVendeursPerformance(Request $request)
    {
        try {
            $validated = $request->validate([
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
            ]);

            $dateDebut = $validated['date_debut'] ?? now()->startOfMonth()->toDateString();
            $dateFin = $validated['date_fin'] ?? now()->toDateString();

            $vendeurs = \App\Models\User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
                ->with(['sessionsVente' => function ($query) use ($dateDebut, $dateFin) {
                    $query->where('statut', 'fermee')
                          ->whereBetween('date_fermeture', [$dateDebut, $dateFin]);
                }])
                ->get()
                ->map(function ($vendeur) {
                    $sessions = $vendeur->sessionsVente;
                    $ventesTotales = 0;
                    $manquantTotal = 0;

                    foreach ($sessions as $session) {
                        $ventesTotales += $this->pdgService->getSessionAvecVentes($session)['ventes_totales'] ?? 0;
                        $manquantTotal += $session->manquant ?? 0;
                    }

                    return [
                        'id' => $vendeur->id,
                        'nom' => $vendeur->name,
                        'role' => $vendeur->role,
                        'nombre_sessions' => $sessions->count(),
                        'ventes_totales' => round($ventesTotales, 2),
                        'manquant_total' => round($manquantTotal, 2),
                        'taux_manquant' => $ventesTotales > 0 ? round(($manquantTotal / $ventesTotales) * 100, 2) : 0,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $vendeurs,
                'periode' => [
                    'debut' => $dateDebut,
                    'fin' => $dateFin,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur performance vendeurs', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des performances',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Méthodes helper privées
     */
    private function calculerResumeFinancier($session)
    {
        if ($session['statut'] !== 'fermee') {
            return null;
        }

        $ventesTotales = $session['ventes_totales'] ?? 0;
        $fondVente = $session['fond_vente'] ?? 0;
        $montantVerse = $session['montant_verse'] ?? 0;
        $diffOM = ($session['orange_money_final'] ?? 0) - ($session['orange_money_initial'] ?? 0);
        $diffMTN = ($session['mtn_money_final'] ?? 0) - ($session['mtn_money_initial'] ?? 0);
        $manquant = $session['manquant'] ?? 0;

        return [
            'ventes_totales' => round($ventesTotales, 2),
            'fond_vente' => round($fondVente, 2),
            'attendu' => round($ventesTotales + $fondVente, 2),
            'montant_verse' => round($montantVerse, 2),
            'difference_om' => round($diffOM, 2),
            'difference_mtn' => round($diffMTN, 2),
            'total_compte' => round($montantVerse + $diffOM + $diffMTN, 2),
            'manquant' => round($manquant, 2),
        ];
    }

    private function calculerVentesPeriode($dateDebut, $dateFin)
    {
        $sessions = \App\Models\SessionVente::where('statut', 'fermee')
            ->whereBetween('date_fermeture', [$dateDebut, $dateFin])
            ->get();

        $total = 0;
        foreach ($sessions as $session) {
            $sessionDetaillée = $this->pdgService->getSessionAvecVentes($session);
            $total += $sessionDetaillée['ventes_totales'] ?? 0;
        }

        return round($total, 2);
    }
}