<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PdgService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Produit;

class PdgController extends Controller
{
    protected $pdgService;

    public function __construct(PdgService $pdgService)
    {
        $this->pdgService = $pdgService;
    }

    public function dashboard(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            $data = $this->pdgService->getDashboardData($limit);
            
            return view('pdg.dashboard', compact('data'));
        } catch (\Exception $e) {
            Log::error('Erreur dashboard PDG', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la récupération des données du dashboard');
        }
    }

    public function receptions(Request $request)
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
            ]);

            $query = \App\Models\ReceptionPointeur::with(['pointeur', 'producteur', 'produit', 'vendeurAssigne']);

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

            $receptions = $query->orderBy('date_reception', 'desc')->paginate(20);
            
            return view('pdg.receptions', compact('receptions'));
        } catch (\Exception $e) {
            Log::error('Erreur filtrage réceptions', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du filtrage des réceptions');
        }
    }

    public function inventaires(Request $request)
    {
        try {
            $validated = $request->validate([
                'vendeur_sortant_id' => 'nullable|exists:users,id',
                'vendeur_entrant_id' => 'nullable|exists:users,id',
                'categorie' => 'nullable|in:boulangerie,patisserie',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
                'statut' => 'nullable|in:valide,en_attente',
            ]);

            $query = \App\Models\Inventaire::with(['vendeurSortant', 'vendeurEntrant', 'details.produit']);

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
                    $query->where('valide_sortant', true)->where('valide_entrant', true);
                } else {
                    $query->where(function ($q) {
                        $q->where('valide_sortant', false)->orWhere('valide_entrant', false);
                    });
                }
            }

            $inventaires = $query->orderBy('date_inventaire', 'desc')->paginate(20);
            
            return view('pdg.inventaires', compact('inventaires'));
        } catch (\Exception $e) {
            Log::error('Erreur filtrage inventaires', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du filtrage des inventaires');
        }
    }

    public function sessionsVente(Request $request)
    {
        try {
            $validated = $request->validate([
                'vendeur_id' => 'nullable|exists:users,id',
                'categorie' => 'nullable|in:boulangerie,patisserie',
                'statut' => 'nullable|in:ouverte,fermee',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
            ]);

            $query = \App\Models\SessionVente::with(['vendeur', 'fermeePar']);

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

            $sessions = $query->orderBy('date_ouverture', 'desc')->paginate(20);
            
            return view('pdg.sessions', compact('sessions'));
        } catch (\Exception $e) {
            Log::error('Erreur filtrage sessions', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du filtrage des sessions de vente');
        }
    }

    public function fluxOperationnelForm()
{
    try {
        // Récupérer tous les vendeurs actifs (boulangerie et pâtisserie)
        $vendeurs = User::where('actif', true)
            ->whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
            ->orderBy('name')
            ->get();
        
        // Récupérer tous les produits actifs
        $produits = Produit::where('actif', true)
            ->orderBy('nom')
            ->get();
        
        return view('pdg.flux-operationnel-form', compact('vendeurs', 'produits'));
    } catch (\Exception $e) {
        Log::error('Erreur formulaire flux opérationnel', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors du chargement du formulaire');
    }
}

public function fluxOperationnel(Request $request)
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
        
        // Récupérer les données pour réafficher le formulaire
        $vendeurs = User::where('actif', true)
            ->whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
            ->orderBy('name')
            ->get();
        
        $produits = Produit::where('actif', true)
            ->orderBy('nom')
            ->get();
        
        // Conserver les valeurs sélectionnées
        $selectedDate = $validated['date'];
        $selectedVendeur = $validated['vendeur_id'] ?? null;
        $selectedProduit = $validated['produit_id'] ?? null;
        
        return view('pdg.flux-operationnel', compact(
            'flux', 
            'vendeurs', 
            'produits', 
            'selectedDate', 
            'selectedVendeur', 
            'selectedProduit'
        ));
    } catch (\Exception $e) {
        Log::error('Erreur flux opérationnel', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la récupération du flux opérationnel');
    }
}

    public function imprimerFlux(Request $request)
{
    try {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'vendeur_id' => 'nullable|exists:users,id',
            'produit_id' => 'nullable|exists:produits,id',
        ]);
        
        // Si aucune date n'est fournie, utiliser la date du jour
        $date = $validated['date'] ?? now()->format('Y-m-d');
        
        $flux = $this->pdgService->getFluxOperationnel(
            $date,
            $validated['vendeur_id'] ?? null,
            $validated['produit_id'] ?? null
        );
        
        return view('pdg.flux-imprimer', compact('flux', 'validated'));
    } catch (\Exception $e) {
        Log::error('Erreur impression flux', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la préparation du flux pour impression');
    }
}

    public function sessionsDetaillees(Request $request)
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

            return view('pdg.sessions-detaillees', compact('sessions'));
        } catch (\Exception $e) {
            Log::error('Erreur sessions détaillées', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la récupération des sessions détaillées');
        }
    }

    public function imprimerSession($id)
    {
        try {
            $session = \App\Models\SessionVente::with(['vendeur', 'fermeePar'])->findOrFail($id);
            $sessionDetaillée = $this->pdgService->getSessionAvecVentes($session);

            return view('pdg.session-imprimer', compact('sessionDetaillée'));
        } catch (\Exception $e) {
            Log::error('Erreur impression session', ['session_id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la préparation de la session pour impression');
        }
    }

    public function imprimerSessionsDetaillees(Request $request)
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

            return view('pdg.sessions-imprimer', compact('sessions', 'validated'));
        } catch (\Exception $e) {
            Log::error('Erreur impression sessions détaillées', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la préparation du rapport');
        }
    }

    public function statistiques(Request $request)
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
                'receptions_periode' => \App\Models\ReceptionPointeur::whereBetween('date_reception', [$dateDebut, $dateFin])
                    ->sum('quantite'),
                'retours_periode' => \App\Models\RetourProduit::whereBetween('date_retour', [$dateDebut, $dateFin])
                    ->sum('quantite'),
                'inventaires_en_attente' => \App\Models\Inventaire::where(function ($query) {
                    $query->where('valide_sortant', false)->orWhere('valide_entrant', false);
                })->count(),
            ];

            return view('pdg.statistiques', compact('stats', 'dateDebut', 'dateFin'));
        } catch (\Exception $e) {
            Log::error('Erreur statistiques PDG', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du calcul des statistiques');
        }
    }

    public function vendeursPerformance(Request $request)
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
                    $query->where('statut', 'fermee')->whereBetween('date_fermeture', [$dateDebut, $dateFin]);
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

            return view('pdg.vendeurs-performance', compact('vendeurs', 'dateDebut', 'dateFin'));
        } catch (\Exception $e) {
            Log::error('Erreur performance vendeurs', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du calcul des performances');
        }
    }
}
