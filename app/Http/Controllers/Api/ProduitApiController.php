<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProduitRequest;
use App\Services\ProduitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PrixProduitHistorique;

class ProduitApiController extends Controller
{
    protected $produitService;

    public function __construct(ProduitService $produitService)
    {
        $this->produitService = $produitService;
    }

    public function index(Request $request)
    {
        try {
            $actifOnly = $request->query('actif_only', true);
            $produits = $this->produitService->getAllProduits($actifOnly);
            
            return response()->json([
                'success' => true,
                'data' => $produits,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function parCategorie($categorie, Request $request)
    {
        try {
            $actifOnly = $request->query('actif_only', true);
            $produits = $this->produitService->getProduitsParCategorie($categorie, $actifOnly);
            
            return response()->json([
                'success' => true,
                'data' => $produits,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function store(CreateProduitRequest $request)
    {
        try {
            $produit = $this->produitService->createProduit($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Produit créé avec succès',
                'data' => $produit,
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

 public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nom'       => 'required|string|max:255',
        'prix'      => 'required|numeric|min:0',
        'categorie' => 'required|in:boulangerie,patisserie',
        'actif'     => 'sometimes|boolean',
    ]);

    try {
        DB::beginTransaction();

        // Mise à jour du produit via le service (ou directement)
        $produit = $this->produitService->updateProduit($id, $validated);

        // Si le prix a changé, on gère l'historique
        if ($produit->wasChanged('prix')) {
            // 1. Clôturer l'historique actif
            PrixProduitHistorique::where('produit_id', $produit->id)
                ->whereNull('date_fin')
                ->update(['date_fin' => now()]);

            // 2. Créer la nouvelle version du prix
            PrixProduitHistorique::create([
                'produit_id' => $produit->id,
                'prix'       => $produit->prix,
                'date_debut' => now(),
                'date_fin'   => null,
            ]);
        }

        DB::commit();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit modifié avec succès',
                'data'    => $produit
            ]);
        }

        return redirect()->route('produits.index')
            ->with('success', 'Produit modifié avec succès');

    } catch (\Exception $e) {
        DB::rollBack();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }

        return back()
            ->withInput()
            ->withErrors(['error' => $e->getMessage()]);
    }
}

    public function toggleActif($id)
    {
        try {
            $produit = $this->produitService->toggleActif($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Statut modifié avec succès',
                'data' => $produit,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->produitService->deleteProduit($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé avec succès',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
