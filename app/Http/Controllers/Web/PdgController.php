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
            'pointeur_id'   => 'nullable|exists:users,id',
            'producteur_id' => 'nullable|exists:users,id',
            'vendeur_id'    => 'nullable|exists:users,id',
            'produit_id'    => 'nullable|exists:produits,id',
            'date_debut'    => 'nullable|date',
            'date_fin'      => 'nullable|date|after_or_equal:date_debut',
            'verrou'        => 'nullable|in:0,1',
        ]);

        // Persister les filtres en session
        if ($request->isMethod('get') && $request->hasAny(['pointeur_id','producteur_id','vendeur_id','produit_id','date_debut','date_fin','verrou'])) {
            session(['pdg_receptions_filters' => $request->only(['pointeur_id','producteur_id','vendeur_id','produit_id','date_debut','date_fin','verrou'])]);
        }
        $filters = session('pdg_receptions_filters', []);

        $query = ReceptionPointeur::with([
            'pointeur:id,name',
            'producteur:id,name',
            'produit:id,nom,prix,categorie',
            'vendeurAssigne:id,name',
        ]);

        if (!empty($filters['pointeur_id']))   $query->where('pointeur_id', $filters['pointeur_id']);
        if (!empty($filters['producteur_id'])) $query->where('producteur_id', $filters['producteur_id']);
        if (!empty($filters['vendeur_id']))    $query->where('vendeur_assigne_id', $filters['vendeur_id']);
        if (!empty($filters['produit_id']))    $query->where('produit_id', $filters['produit_id']);
        if (!empty($filters['date_debut']))    $query->whereDate('date_reception', '>=', $filters['date_debut']);
        if (!empty($filters['date_fin']))      $query->whereDate('date_reception', '<=', $filters['date_fin']);
        if (isset($filters['verrou']) && $filters['verrou'] !== '') {
            $query->where('verrou', (bool)$filters['verrou']);
        }

        // Récupérer TOUTES les réceptions filtrées pour fusion
        $rawReceptions = $query->orderBy('date_reception', 'desc')->get();

        // Fusion : même produit + vendeur_assigne + date (jour) + pointeur → additionner quantités
        $grouped = $rawReceptions->groupBy(function ($r) {
            return implode('_', [
                $r->produit_id,
                $r->vendeur_assigne_id ?? 'null',
                $r->date_reception->format('Y-m-d'),
                $r->pointeur_id,
            ]);
        });

        $receptionsFusionnees = $grouped->map(function ($group) {
            $first = $group->first();
            $first->quantite_fusionnee = $group->sum('quantite');
            $first->ids_fusionnes      = $group->pluck('id')->toArray();
            $first->est_fusion         = $group->count() > 1;
            return $first;
        })->values();

        // Trier : prix décroissant, puis nom produit alphabétique, puis date desc
        $receptionsFusionnees = $receptionsFusionnees->sortBy([
        fn($a, $b) => $a->produit->id <=> $b->produit->id,
        fn($a, $b) => $b->date_reception <=> $a->date_reception,
        ])->values();

        // Pagination manuelle
        $perPage   = 50;
        $page      = $request->input('page', 1);
        $total     = $receptionsFusionnees->count();
        $items     = $receptionsFusionnees->slice(($page - 1) * $perPage, $perPage)->values();
        $receptions = new \Illuminate\Pagination\LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $produits = Produit::where('actif', true)->orderBy('id')->get();

        $pointeurs  = User::where('role', 'pointeur')->where('actif', true)->orderBy('name')->get();
        $producteurs = User::where('role', 'producteur')->where('actif', true)->orderBy('name')->get();
        $vendeurs   = User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
                          ->where('actif', true)->orderBy('name')->get();

        $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
        $currentFilters = $filters;

        return view('receptions.index', compact(
            'receptions', 'produits', 'pointeurs', 'producteurs', 'vendeurs', 'isFrench', 'currentFilters'
        ));
    } catch (\Exception $e) {
        Log::error('Erreur filtrage réceptions', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return back()->with('error', 'Erreur lors du filtrage des réceptions');
    }
}

public function resetReceptionsFilters()
{
    session()->forget('pdg_receptions_filters');
    return redirect()->route('pdg.receptions');
}

   public function editReception(Request $request, $id)
{
    try {
        $reception = ReceptionPointeur::with(['pointeur', 'producteur', 'produit', 'vendeurAssigne'])->findOrFail($id);

        if ($reception->verrou) {
            return back()->with('error', 'Cette réception est verrouillée et ne peut pas être modifiée');
        }

        // IDs fusionnés transmis depuis la vue liste
        $idsFusionnes = $request->input('ids_fusionnes')
            ? explode(',', $request->input('ids_fusionnes'))
            : [$id];

        // Quantité totale fusionnée
        $quantiteFusionnee = ReceptionPointeur::whereIn('id', $idsFusionnes)->sum('quantite');

        $produits = Produit::where('actif', true)->orderByDesc('prix')->orderBy('nom')->get();
        $vendeurs = User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
                        ->where('actif', true)->orderBy('name')->get();

        $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';

        return view('receptions.edit', compact(
            'reception', 'produits', 'vendeurs', 'isFrench', 'idsFusionnes', 'quantiteFusionnee'
        ));
    } catch (\Exception $e) {
        Log::error('Erreur édition réception', ['id' => $id, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors du chargement de la réception');
    }
}

public function updateReception(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $reception = ReceptionPointeur::findOrFail($id);

        if ($reception->verrou) {
            return back()->with('error', 'Cette réception est verrouillée et ne peut pas être modifiée');
        }

        $validated = $request->validate([
            'produit_id'         => 'required|exists:produits,id',
            'quantite'           => 'required|integer|min:1',
            'vendeur_assigne_id' => 'nullable|exists:users,id',
            'date_reception'     => 'required|date',
            'ids_fusionnes'      => 'nullable|string',
        ]);

        // Supprimer les entrées fusionnées sauf celle qu'on modifie
        if (!empty($validated['ids_fusionnes'])) {
            $idsFusionnes = array_filter(
                explode(',', $validated['ids_fusionnes']),
                fn($i) => (int)$i !== (int)$id
            );
            if (!empty($idsFusionnes)) {
                ReceptionPointeur::whereIn('id', $idsFusionnes)->delete();
            }
        }

        $reception->update([
            'produit_id'         => $validated['produit_id'],
            'quantite'           => $validated['quantite'],
            'vendeur_assigne_id' => $validated['vendeur_assigne_id'] ?? null,
            'date_reception'     => $validated['date_reception'],
        ]);

        DB::commit();

        return redirect()->route('pdg.receptions')->with('success', 'Réception mise à jour avec succès');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur mise à jour réception', ['id' => $id, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la mise à jour de la réception')->withInput();
    }
}

public function destroyReception(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $reception = ReceptionPointeur::findOrFail($id);

        if ($reception->verrou) {
            return back()->with('error', 'Cette réception est verrouillée et ne peut pas être supprimée');
        }

        // Supprimer toutes les entrées fusionnées
        $idsFusionnes = $request->input('ids_fusionnes')
            ? explode(',', $request->input('ids_fusionnes'))
            : [$id];

        ReceptionPointeur::whereIn('id', $idsFusionnes)->where('verrou', false)->delete();

        DB::commit();

        return redirect()->route('pdg.receptions')->with('success', 'Réception supprimée avec succès');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur suppression réception', ['id' => $id, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la suppression de la réception');
    }
}


public function retours(Request $request)
{
    try {
        $raisons = \App\Models\RaisonRetour::orderBy('id')->get();

        // Palette de couleurs pour assignation aléatoire mais stable (basée sur le code)
        $colorPalette = [
            'bg-red-100 text-red-800',
            'bg-orange-100 text-orange-800',
            'bg-yellow-100 text-yellow-800',
            'bg-blue-100 text-blue-800',
            'bg-purple-100 text-purple-800',
            'bg-pink-100 text-pink-800',
            'bg-indigo-100 text-indigo-800',
            'bg-teal-100 text-teal-800',
            'bg-green-100 text-green-800',
            'bg-cyan-100 text-cyan-800',
            'bg-lime-100 text-lime-800',
            'bg-rose-100 text-rose-800',
            'bg-violet-100 text-violet-800',
            'bg-sky-100 text-sky-800',
            'bg-amber-100 text-amber-800',
            'bg-emerald-100 text-emerald-800',
        ];

        // Construire les tableaux dynamiquement
        // L'index est basé sur crc32 du code pour être stable (même couleur à chaque chargement)
        $raisonLabels = [];
        $raisonColors = [];
        foreach ($raisons as $raison) {
            $raisonLabels[$raison->code] = $raison->libelle;
            $colorIndex = abs(crc32($raison->code)) % count($colorPalette);
            $raisonColors[$raison->code] = $colorPalette[$colorIndex];
        }

        $raisonCodes = $raisons->pluck('code')->toArray();

        $validated = $request->validate([
            'vendeur_id'  => 'nullable|exists:users,id',
            'produit_id'  => 'nullable|exists:produits,id',
            'date_debut'  => 'nullable|date',
            'date_fin'    => 'nullable|date|after_or_equal:date_debut',
            'raison'      => 'nullable|string',
            'pointeur_id' => 'nullable|exists:users,id',
            'verrou'      => 'nullable|in:0,1',
        ]);

        if ($request->isMethod('get') && $request->hasAny(['vendeur_id','produit_id','date_debut','date_fin','raison','pointeur_id','verrou'])) {
            session(['pdg_retours_filters' => $request->only(['vendeur_id','produit_id','date_debut','date_fin','raison','pointeur_id','verrou'])]);
        }
        $filters = session('pdg_retours_filters', []);

        $query = \App\Models\RetourProduit::with([
            'pointeur:id,name',
            'vendeur:id,name',
            'produit:id,nom,prix,categorie',
        ]);

        if (!empty($filters['vendeur_id']))  $query->where('vendeur_id', $filters['vendeur_id']);
        if (!empty($filters['produit_id']))  $query->where('produit_id', $filters['produit_id']);
        if (!empty($filters['date_debut']))  $query->whereDate('date_retour', '>=', $filters['date_debut']);
        if (!empty($filters['date_fin']))    $query->whereDate('date_retour', '<=', $filters['date_fin']);
        if (!empty($filters['raison']))      $query->where('raison', $filters['raison']);
        if (!empty($filters['pointeur_id'])) $query->where('pointeur_id', $filters['pointeur_id']);
        if (isset($filters['verrou']) && $filters['verrou'] !== '') {
            $query->where('verrou', (bool)$filters['verrou']);
        }

        $rawRetours = $query->orderBy('date_retour', 'desc')->get();

        $grouped = $rawRetours->groupBy(function ($r) {
            return implode('_', [
                $r->produit_id,
                $r->vendeur_id,
                $r->date_retour->format('Y-m-d'),
                $r->pointeur_id,
            ]);
        });

        $retoursFusionnes = $grouped->map(function ($group) {
            $first = $group->first();
            $first->quantite_fusionnee = $group->sum('quantite');
            $first->ids_fusionnes      = $group->pluck('id')->toArray();
            $first->est_fusion         = $group->count() > 1;
            return $first;
        })->values();

        $retoursFusionnes = $retoursFusionnes->sortBy([
            fn($a, $b) => $a->produit->id <=> $b->produit->id,
            fn($a, $b) => $b->date_retour <=> $a->date_retour,
        ])->values();

        $perPage = 50;
        $page    = $request->input('page', 1);
        $total   = $retoursFusionnes->count();
        $items   = $retoursFusionnes->slice(($page - 1) * $perPage, $perPage)->values();
        $retours = new \Illuminate\Pagination\LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $produits  = Produit::where('actif', true)->orderBy('id')->get();
        $vendeurs  = User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
                         ->where('actif', true)->orderBy('name')->get();
        $pointeurs = User::where('role', 'pointeur')->where('actif', true)->orderBy('name')->get();

        $isFrench       = app()->getLocale() === 'fr' || session('langue') === 'fr';
        $currentFilters = $filters;

        return view('pdg.retours', compact(
            'retours', 'produits', 'vendeurs', 'pointeurs', 'isFrench', 'currentFilters',
            'raisons', 'raisonLabels', 'raisonColors'
        ));
    } catch (\Exception $e) {
        Log::error('Erreur filtrage retours PDG', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors du filtrage des retours');
    }
}


public function resetRetoursFilters()
{
    session()->forget('pdg_retours_filters');
    return redirect()->route('pdg.retours');
}

public function editRetour(Request $request, $id)
{
    try {
        $retour = \App\Models\RetourProduit::with(['pointeur', 'vendeur', 'produit'])->findOrFail($id);

        if ($retour->verrou) {
            return back()->with('error', 'Ce retour est verrouillé et ne peut pas être modifié');
        }

        $idsFusionnes = $request->input('ids_fusionnes')
            ? explode(',', $request->input('ids_fusionnes'))
            : [$id];

        $quantiteFusionnee = \App\Models\RetourProduit::whereIn('id', $idsFusionnes)->sum('quantite');

        $retour->ids_fusionnes      = $idsFusionnes;
        $retour->quantite_fusionnee = $quantiteFusionnee;
        $retour->est_fusion         = count($idsFusionnes) > 1;

        $produits = Produit::where('actif', true)->orderByDesc('prix')->orderBy('nom')->get();
        $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';

        return view('pdg.retours-edit', compact('retour', 'produits', 'isFrench', 'idsFusionnes', 'quantiteFusionnee'));
    } catch (\Exception $e) {
        Log::error('Erreur édition retour', ['id' => $id, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors du chargement du retour');
    }
}

public function updateRetour(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $retour = \App\Models\RetourProduit::findOrFail($id);

        if ($retour->verrou) {
            return back()->with('error', 'Ce retour est verrouillé et ne peut pas être modifié');
        }

        $validated = $request->validate([
            'produit_id'    => 'required|exists:produits,id',
            'quantite'      => 'required|integer|min:1',
            'raison'        => 'required|in:perime,abime,autre',
            'description'   => 'nullable|string|max:500',
            'date_retour'   => 'required|date',
            'ids_fusionnes' => 'nullable|string',
        ]);

        // Supprimer les entrées fusionnées sauf celle modifiée
        if (!empty($validated['ids_fusionnes'])) {
            $idsFusionnes = array_filter(
                explode(',', $validated['ids_fusionnes']),
                fn($i) => (int)$i !== (int)$id
            );
            if (!empty($idsFusionnes)) {
                \App\Models\RetourProduit::whereIn('id', $idsFusionnes)->delete();
            }
        }

        $retour->update([
            'produit_id'  => $validated['produit_id'],
            'quantite'    => $validated['quantite'],
            'raison'      => $validated['raison'],
            'description' => $validated['description'] ?? null,
            'date_retour' => $validated['date_retour'],
        ]);

        DB::commit();

        return redirect()->route('pdg.retours')->with('success', 'Retour mis à jour avec succès');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur mise à jour retour', ['id' => $id, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la mise à jour du retour')->withInput();
    }
}

public function destroyRetour(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $retour = \App\Models\RetourProduit::findOrFail($id);

        if ($retour->verrou) {
            return back()->with('error', 'Ce retour est verrouillé et ne peut pas être supprimé');
        }

        $idsFusionnes = $request->input('ids_fusionnes')
            ? explode(',', $request->input('ids_fusionnes'))
            : [$id];

        \App\Models\RetourProduit::whereIn('id', $idsFusionnes)->where('verrou', false)->delete();

        DB::commit();

        return redirect()->route('pdg.retours')->with('success', 'Retour supprimé avec succès');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur suppression retour', ['id' => $id, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la suppression du retour');
    }
}


public function raisonsRetour()
{
    $raisons  = \App\Models\RaisonRetour::orderBy('id')->get();
    $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
    return view('pdg.raisons-retour.index', compact('raisons', 'isFrench'));
}

public function createRaisonRetour()
{
    $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
    return view('pdg.raisons-retour.form', compact('isFrench'));
}

public function storeRaisonRetour(Request $request)
{
    $request->validate([
        'libelle' => 'required|string|max:100',
        'code'    => 'required|string|max:50|unique:raisons_retour,code|regex:/^[a-z0-9_]+$/',
    ], [
        'code.regex'  => 'Le code ne doit contenir que des lettres minuscules, chiffres et underscores.',
        'code.unique' => 'Ce code existe déjà.',
    ]);

    \App\Models\RaisonRetour::create([
        'code'    => $request->code,
        'libelle' => $request->libelle,
        'actif'   => true,
    ]);

    return redirect()->route('pdg.raisons-retour.index')
        ->with('success', 'Raison de retour créée avec succès.');
}

public function editRaisonRetour($id)
{
    $raison   = \App\Models\RaisonRetour::findOrFail($id);
    $isFrench = app()->getLocale() === 'fr' || session('langue') === 'fr';
    return view('pdg.raisons-retour.form', compact('raison', 'isFrench'));
}

public function updateRaisonRetour(Request $request, $id)
{
    $raison = \App\Models\RaisonRetour::findOrFail($id);

    $request->validate([
        'libelle' => 'required|string|max:100',
        'code'    => 'required|string|max:50|regex:/^[a-z0-9_]+$/|unique:raisons_retour,code,' . $id,
    ]);

    $raison->update([
        'code'    => $request->code,
        'libelle' => $request->libelle,
    ]);

    return redirect()->route('pdg.raisons-retour.index')
        ->with('success', 'Raison de retour mise à jour.');
}

public function toggleRaisonRetour($id)
{
    $raison = \App\Models\RaisonRetour::findOrFail($id);
    $raison->update(['actif' => !$raison->actif]);
    return back()->with('success', 'Statut mis à jour.');
}

public function destroyRaisonRetour($id)
{
    $raison = \App\Models\RaisonRetour::findOrFail($id);

    // Vérifier si des retours utilisent cette raison
    $count = \Illuminate\Support\Facades\DB::table('retours_produits')
        ->where('raison', $raison->code)
        ->count();

    if ($count > 0) {
        return back()->with('error', "Impossible de supprimer : {$count} retour(s) utilisent cette raison. Désactivez-la plutôt.");
    }

    $raison->delete();
    return back()->with('success', 'Raison supprimée.');
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

    public function addInventaireDetail(Request $request, $id)
{
    try {
        $inventaire = Inventaire::findOrFail($id);

        $validated = $request->validate([
            'produit_id'        => 'required|exists:produits,id',
            'quantite_restante' => 'required|integer|min:0',
        ]);

        // Vérifier que le produit n'est pas déjà dans cet inventaire
        $existe = InventaireDetail::where('inventaire_id', $id)
            ->where('produit_id', $validated['produit_id'])
            ->exists();

        if ($existe) {
            return back()->with('error', 'Ce produit est déjà dans cet inventaire.')->withInput();
        }

        InventaireDetail::create([
            'inventaire_id'     => $id,
            'produit_id'        => $validated['produit_id'],
            'quantite_restante' => $validated['quantite_restante'],
        ]);

        return redirect()->route('pdg.inventaires.edit', $id)
            ->with('success', 'Produit ajouté à l\'inventaire avec succès.');
    } catch (\Exception $e) {
        Log::error('Erreur ajout détail inventaire', ['id' => $id, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de l\'ajout du produit.')->withInput();
    }
}

public function destroyInventaireDetail(Request $request, $id, $detailId)
{
    try {
        $detail = InventaireDetail::where('inventaire_id', $id)->findOrFail($detailId);
        $detail->delete();

        return redirect()->route('pdg.inventaires.edit', $id)
            ->with('success', 'Produit retiré de l\'inventaire avec succès.');
    } catch (\Exception $e) {
        Log::error('Erreur suppression détail inventaire', ['id' => $id, 'detailId' => $detailId, 'error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la suppression du produit.');
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

public function manquants(Request $request)
{
    try {
        $mois  = $request->input('mois',  now()->month);
        $annee = $request->input('annee', now()->year);

        $dateDebut = \Carbon\Carbon::createFromDate($annee, $mois, 1)->startOfDay();
        $dateFin   = $dateDebut->copy()->endOfMonth();
        $nbJours   = $dateFin->day;

        $vendeurs = User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
            ->where('actif', true)
            ->orderBy('name')
            ->get();

        $manquantsDB = \App\Models\Manquant::with(['vendeur', 'validePar'])
            ->whereBetween('date_manquant', [$dateDebut->toDateString(), $dateFin->toDateString()])
            ->get()
            ->groupBy(fn($m) => $m->date_manquant->format('Y-m-d'));

        $jours = [];
        for ($d = 1; $d <= $nbJours; $d++) {
            $date    = \Carbon\Carbon::createFromDate($annee, $mois, $d);
            $dateStr = $date->format('Y-m-d');

            $manquantsJour = $manquantsDB->get($dateStr, collect());

            $totalTrouve   = 0;
            $totalEntree   = 0;
            $totalVerse    = 0;
            $totalManquant = 0;

            $lignesVendeurs = [];
            foreach ($manquantsJour as $m) {
                $totalTrouve += $m->total_ventes;
                $totalEntree += $m->fond_caisse;
                $totalVerse  += $m->total_verse;
                // ── On n'additionne que les vrais manquants (positifs) ──
                if ($m->montant_manquant > 0) {
                    $totalManquant += $m->montant_manquant;
                }

                $lignesVendeurs[] = [
                    'id'               => $m->id,
                    'vendeur_id'       => $m->vendeur_id,
                    'vendeur_nom'      => $m->vendeur?->name ?? 'N/A',
                    'total_ventes'     => $m->total_ventes,
                    'fond_caisse'      => $m->fond_caisse,
                    'versement_1'      => $m->versement_1,
                    'versement_2'      => $m->versement_2,
                    'versement_3'      => $m->versement_3,
                    'versement_extra'  => $m->versement_extra,
                    'om_final'         => $m->om_final,
                    'momo_final'       => $m->momo_final,
                    'total_verse'      => $m->total_verse,
                    'montant_manquant' => $m->montant_manquant,
                    'type'             => $m->type,
                    'notes'            => $m->notes,
                    'valide_par'       => $m->validePar?->name,
                    'updated_at'       => $m->updated_at,
                ];
            }

            $jours[] = [
                'date'           => $dateStr,
                'jour'           => $d,
                'jour_semaine'   => $date->locale('fr')->isoFormat('ddd'),
                'est_weekend'    => $date->isWeekend(),
                'est_futur'      => $date->isFuture(),
                'total_trouve'   => $totalTrouve,
                'total_entree'   => $totalEntree,
                'total_verse'    => $totalVerse,
                'total_manquant' => $totalManquant,
                'vendeurs'       => $lignesVendeurs,
            ];
        }

        // ── Totaux par vendeur : on ne somme que les manquants positifs ──
        $totauxVendeurs = [];
        foreach ($vendeurs as $v) {
            $manquantsVendeur = \App\Models\Manquant::where('vendeur_id', $v->id)
                ->whereBetween('date_manquant', [$dateDebut->toDateString(), $dateFin->toDateString()])
                ->get();

            // Somme uniquement des lignes où montant_manquant > 0
            $totalManquantVendeur = $manquantsVendeur
                ->filter(fn($m) => $m->montant_manquant > 0)
                ->sum('montant_manquant');

            $totauxVendeurs[] = [
                'vendeur_id'     => $v->id,
                'vendeur_nom'    => $v->name,
                'total_manquant' => $totalManquantVendeur,
                'total_ventes'   => $manquantsVendeur->sum('total_ventes'),
                'total_verse'    => $manquantsVendeur->sum('total_verse'),
                'nb_jours'       => $manquantsVendeur->count(),
                'details'        => $manquantsVendeur->map(fn($m) => [
                    'date'             => $m->date_manquant->format('d/m'),
                    'total_ventes'     => $m->total_ventes,
                    'fond_caisse'      => $m->fond_caisse,
                    'total_verse'      => $m->total_verse,
                    'montant_manquant' => $m->montant_manquant,
                    'type'             => $m->type,
                ])->toArray(),
            ];
        }

        usort($totauxVendeurs, fn($a, $b) => $b['total_manquant'] <=> $a['total_manquant']);

        $moisPrecedent = $dateDebut->copy()->subMonth();
        $moisSuivant   = $dateDebut->copy()->addMonth();
        $isFrench      = app()->getLocale() === 'fr' || session('langue') === 'fr';

        return view('pdg.manquants', compact(
            'jours', 'vendeurs', 'totauxVendeurs',
            'mois', 'annee', 'dateDebut', 'dateFin',
            'moisPrecedent', 'moisSuivant',
            'isFrench'
        ));
    } catch (\Exception $e) {
        Log::error('Erreur vue manquants', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return back()->with('error', 'Erreur lors de la récupération des manquants');
    }
}

/**
 * Valider (créer ou écraser) un manquant depuis la vue manquants
 * ou depuis la vue flux-opérationnel
 */
public function validerManquant(Request $request)
{
    try {
        $validated = $request->validate([
            'vendeur_id'      => 'required|exists:users,id',
            'date_manquant'   => 'required|date',
            'total_ventes'    => 'required|numeric|min:0',
            'fond_caisse'     => 'nullable|numeric|min:0',
            'versement_1'     => 'nullable|numeric|min:0',
            'versement_2'     => 'nullable|numeric|min:0',
            'versement_3'     => 'nullable|numeric|min:0',
            'versement_extra' => 'nullable|numeric|min:0',
            'om_final'        => 'nullable|numeric|min:0',
            'momo_final'      => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string|max:500',
        ]);

        $fondCaisse     = $validated['fond_caisse']     ?? 0;
        $versement1     = $validated['versement_1']     ?? 0;
        $versement2     = $validated['versement_2']     ?? 0;
        $versement3     = $validated['versement_3']     ?? 0;
        $versementExtra = $validated['versement_extra'] ?? 0;
        $omFinal        = $validated['om_final']        ?? 0;
        $momoFinal      = $validated['momo_final']      ?? 0;

        $totalVerse   = $versement1 + $versement2 + $versement3 + $versementExtra + $omFinal + $momoFinal;
        $attenduNet   = $validated['total_ventes'] + $fondCaisse;
        $montantManquant = $attenduNet - $totalVerse;

        // updateOrCreate écrase automatiquement si même vendeur + même date
        \App\Models\Manquant::updateOrCreate(
            [
                'vendeur_id'    => $validated['vendeur_id'],
                'date_manquant' => $validated['date_manquant'],
            ],
            [
                'total_ventes'    => $validated['total_ventes'],
                'fond_caisse'     => $fondCaisse,
                'versement_1'     => $versement1,
                'versement_2'     => $versement2,
                'versement_3'     => $versement3,
                'versement_extra' => $versementExtra,
                'om_final'        => $omFinal,
                'momo_final'      => $momoFinal,
                'total_verse'     => $totalVerse,
                'montant_manquant'=> $montantManquant,
                'notes'           => $validated['notes'] ?? null,
                'valide_par'      => auth()->id(),
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success'          => true,
                'montant_manquant' => $montantManquant,
                'total_verse'      => $totalVerse,
                'message'          => 'Manquant enregistré avec succès',
            ]);
        }

        $mois  = \Carbon\Carbon::parse($validated['date_manquant'])->month;
        $annee = \Carbon\Carbon::parse($validated['date_manquant'])->year;

        return redirect()
            ->route('pdg.manquants', ['mois' => $mois, 'annee' => $annee])
            ->with('success', 'Manquant enregistré avec succès');

    } catch (\Exception $e) {
        Log::error('Erreur validation manquant', ['error' => $e->getMessage()]);

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return back()->with('error', 'Erreur lors de l\'enregistrement du manquant');
    }
}

/**
 * Supprimer un manquant
 */
public function destroyManquant($id)
{
    try {
        $manquant = \App\Models\Manquant::findOrFail($id);
        $mois     = $manquant->date_manquant->month;
        $annee    = $manquant->date_manquant->year;
        $manquant->delete();

        return redirect()
            ->route('pdg.manquants', ['mois' => $mois, 'annee' => $annee])
            ->with('success', 'Manquant supprimé avec succès');
    } catch (\Exception $e) {
        Log::error('Erreur suppression manquant', ['error' => $e->getMessage()]);
        return back()->with('error', 'Erreur lors de la suppression du manquant');
    }
}

public function checkManquant(Request $request)
{
    $vendeurId    = $request->input('vendeur_id');
    $date         = $request->input('date');
 
    if (!$vendeurId || !$date) {
        return response()->json(['exists' => false]);
    }
 
    $manquant = \App\Models\Manquant::where('vendeur_id', $vendeurId)
        ->whereDate('date_manquant', $date)
        ->first();
 
    return response()->json([
        'exists'   => (bool) $manquant,
        'manquant' => $manquant ? $manquant->toArray() : null,
    ]);
}


}
