<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PdgService;
use App\Models\ReceptionPointeur;
use App\Models\Inventaire;
use App\Models\InventaireDetail;
use App\Models\User;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'verrou' => 'nullable|in:0,1',
        ]);

        $query = ReceptionPointeur::with([
            'pointeur:id,name',
            'producteur:id,name',
            'produit:id,nom,prix,categorie',
            'vendeurAssigne:id,name'
        ]);

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
            $query->where('verrou', (bool)$validated['verrou']);
        }

        $receptions = $query->orderBy('date_reception', 'desc')->paginate(50);
        $receptions->appends($request->all());

        $produits = Produit::where('actif', true)->orderBy('categorie')->orderBy('nom')->get();
        $pointeurs = User::where('role', 'pointeur')->where('actif', true)->orderBy('name')->get();
        $producteurs = User::where('role', 'producteur')->where('actif', true)->orderBy('name')->get();
        
        // CORRECTION: Récupérer les vendeurs avec les bons rôles
        $vendeurs = User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
            ->where('actif', true)
            ->orderBy('name')
            ->get();

        $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';

        return view('receptions.index', compact(
            'receptions',
            'produits',
            'pointeurs',
            'producteurs',
            'vendeurs',
            'isFrench'
        ));
    } catch (\Exception $e) {
        Log::error('Erreur filtrage réceptions', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Erreur lors du filtrage des réceptions');
    }
}

    public function editReception($id)
{
    try {
        $reception = ReceptionPointeur::with(['pointeur', 'producteur', 'produit', 'vendeurAssigne'])->findOrFail($id);
        
        if ($reception->verrou) {
            return back()->with('error', 'Cette réception est verrouillée et ne peut pas être modifiée');
        }

        $produits = Produit::where('actif', true)->orderBy('nom')->get();
        
        // CORRECTION: Récupérer tous les vendeurs (boulangerie ET patisserie)
        $vendeurs = User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
            ->where('actif', true)
            ->orderBy('name')
            ->get();
        
        $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';

        // Plus besoin de pointeurs et producteurs puisqu'on ne peut pas les modifier
        return view('receptions.edit', compact('reception', 'produits', 'vendeurs', 'isFrench'));
    } catch (\Exception $e) {
        Log::error('Erreur édition réception', ['id' => $id, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors du chargement de la réception');
    }
}

public function updateReception(Request $request, $id)
{
    try {
        $reception = ReceptionPointeur::findOrFail($id);
        
        if ($reception->verrou) {
            return back()->with('error', 'Cette réception est verrouillée et ne peut pas être modifiée');
        }

        // CORRECTION: Valider uniquement les champs modifiables
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'vendeur_assigne_id' => 'nullable|exists:users,id',
            'date_reception' => 'required|date',
        ]);

        $reception->update($validated);

        return redirect()->route('pdg.receptions')
            ->with('success', 'Réception mise à jour avec succès');
    } catch (\Exception $e) {
        Log::error('Erreur mise à jour réception', ['id' => $id, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la mise à jour de la réception')->withInput();
    }
}

    public function imprimerReceptions(Request $request)
    {
        try {
            $validated = $request->validate([
                'pointeur_id' => 'nullable|exists:users,id',
                'producteur_id' => 'nullable|exists:users,id',
                'vendeur_id' => 'nullable|exists:users,id',
                'produit_id' => 'nullable|exists:produits,id',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
            ]);

            $query = ReceptionPointeur::with(['pointeur', 'producteur', 'produit', 'vendeurAssigne']);

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

            $receptions = $query->orderBy('date_reception', 'desc')->get();
            $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';

            return view('pdg.receptions-imprimer', compact('receptions', 'validated', 'isFrench'));
        } catch (\Exception $e) {
            Log::error('Erreur impression réceptions', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la préparation de l\'impression');
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

            $query = Inventaire::with(['vendeurSortant', 'vendeurEntrant', 'details.produit']);

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
            $vendeurs = User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])->where('actif', true)->orderBy('name')->get();
            $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
            
            return view('pdg.inventaires', compact('inventaires', 'vendeurs', 'isFrench'));
        } catch (\Exception $e) {
            Log::error('Erreur filtrage inventaires', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du filtrage des inventaires');
        }
    }

    public function editInventaire($id)
    {
        try {
            $inventaire = Inventaire::with(['vendeurSortant', 'vendeurEntrant', 'details.produit'])->findOrFail($id);
            $produits = Produit::where('actif', true)->where('categorie', $inventaire->categorie)->orderBy('nom')->get();
            $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';

            return view('pdg.inventaires-edit', compact('inventaire', 'produits', 'isFrench'));
        } catch (\Exception $e) {
            Log::error('Erreur édition inventaire', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du chargement de l\'inventaire');
        }
    }

    public function updateInventaire(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $inventaire = Inventaire::findOrFail($id);
            
            $validated = $request->validate([
                'details' => 'required|array',
                'details.*.produit_id' => 'required|exists:produits,id',
                'details.*.quantite_restante' => 'required|integer|min:0',
            ]);

            // Supprimer les anciens détails
            $inventaire->details()->delete();

            // Créer les nouveaux détails
            foreach ($validated['details'] as $detail) {
                InventaireDetail::create([
                    'inventaire_id' => $inventaire->id,
                    'produit_id' => $detail['produit_id'],
                    'quantite_restante' => $detail['quantite_restante'],
                ]);
            }

            DB::commit();

            return redirect()->route('pdg.inventaires')
                ->with('success', 'Inventaire mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour inventaire', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la mise à jour de l\'inventaire')->withInput();
        }
    }

    public function imprimerInventaires(Request $request)
    {
        try {
            $validated = $request->validate([
                'vendeur_sortant_id' => 'nullable|exists:users,id',
                'vendeur_entrant_id' => 'nullable|exists:users,id',
                'categorie' => 'nullable|in:boulangerie,patisserie',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date',
            ]);

            $query = Inventaire::with(['vendeurSortant', 'vendeurEntrant', 'details.produit']);

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

            $inventaires = $query->orderBy('date_inventaire', 'desc')->get();
            $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';

            return view('pdg.inventaires-imprimer', compact('inventaires', 'validated', 'isFrench'));
        } catch (\Exception $e) {
            Log::error('Erreur impression inventaires', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la préparation de l\'impression');
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
            $vendeurs = User::where('actif', true)
                ->whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
                ->orderBy('name')
                ->get();
            
            $produits = Produit::where('actif', true)
                ->orderBy('nom')
                ->get();
            
            $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
            
            return view('pdg.flux-operationnel-form', compact('vendeurs', 'produits', 'isFrench'));
        } catch (\Exception $e) {
            Log::error('Erreur formulaire flux opérationnel', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du chargement du formulaire');
        }
    }

    /**
     * Affichage du flux opérationnel
     * CORRIGÉ: Meilleure gestion de la date et des paramètres
     */
    public function fluxOperationnel(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'date' => 'nullable|date',
                'vendeur_id' => 'nullable|exists:users,id',
                'produit_id' => 'nullable|exists:produits,id',
            ]);
            
            // Date par défaut = aujourd'hui si non spécifiée
            $selectedDate = $validated['date'] ?? now()->format('Y-m-d');
            $selectedVendeur = $validated['vendeur_id'] ?? null;
            $selectedProduit = $validated['produit_id'] ?? null;
            
            // Debug log
            Log::info('Flux opérationnel demandé', [
                'date' => $selectedDate,
                'vendeur_id' => $selectedVendeur,
                'produit_id' => $selectedProduit
            ]);
            
            // Récupération du flux
            $flux = $this->pdgService->getFluxOperationnel(
                $selectedDate,
                $selectedVendeur,
                $selectedProduit
            );
            
            // Debug log
            Log::info('Flux récupéré', [
                'nombre_vendeurs' => count($flux['flux'] ?? []),
                'total_ventes' => $flux['resume']['total_ventes'] ?? 0
            ]);
            
            // Liste des vendeurs et produits pour les filtres
            $vendeurs = User::where('actif', true)
                ->whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
                ->orderBy('name')
                ->get();
            
            $produits = Produit::where('actif', true)
                ->orderBy('nom')
                ->get();
            
            $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
            
            return view('pdg.flux-operationnel', compact(
                'flux', 
                'vendeurs', 
                'produits', 
                'selectedDate', 
                'selectedVendeur', 
                'selectedProduit',
                'isFrench'
            ));
            
        } catch (\Exception $e) {
            Log::error('Erreur flux opérationnel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erreur lors de la récupération du flux opérationnel: ' . $e->getMessage());
        }
    }

    /**
     * Impression du flux opérationnel
     */
    public function imprimerFlux(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'nullable|date',
                'vendeur_id' => 'nullable|exists:users,id',
                'produit_id' => 'nullable|exists:produits,id',
            ]);
            
            $date = $validated['date'] ?? now()->format('Y-m-d');
            
            $flux = $this->pdgService->getFluxOperationnel(
                $date,
                $validated['vendeur_id'] ?? null,
                $validated['produit_id'] ?? null
            );
            
            $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
            
            return view('pdg.flux-imprimer', compact('flux', 'validated', 'isFrench'));
        } catch (\Exception $e) {
            Log::error('Erreur impression flux', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la préparation du flux pour impression');
        }
    }

    /**
     * Statistiques globales
     */
    public function statistiques(Request $request)
    {
        try {
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            
            $stats = $this->pdgService->getStatistiques($dateDebut, $dateFin);
            $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
            
            return view('pdg.statistiques', compact('stats', 'isFrench'));
        } catch (\Exception $e) {
            Log::error('Erreur statistiques', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la récupération des statistiques');
        }
    }

    /**
     * Performance des vendeurs
     */
    public function vendeursPerformance(Request $request)
    {
        try {
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            
            $performances = $this->pdgService->getVendeursPerformance($dateDebut, $dateFin);
            $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
            
            return view('pdg.vendeurs-performance', compact('performances', 'isFrench'));
        } catch (\Exception $e) {
            Log::error('Erreur performance vendeurs', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la récupération de la performance des vendeurs');
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

}
