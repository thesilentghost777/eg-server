<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProduitRequest;
use App\Services\ProduitService;
use Illuminate\Http\Request;

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

    public function update(CreateProduitRequest $request, $id)
    {
        try {
            $produit = $this->produitService->updateProduit($id, $request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Produit modifié avec succès',
                'data' => $produit,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
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
