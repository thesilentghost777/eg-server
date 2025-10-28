<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ProduitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProduitController extends Controller
{
    protected $produitService;

    public function __construct(ProduitService $produitService)
    {
        $this->produitService = $produitService;
    }

    public function index(Request $request)
    {
        $categorie = $request->query('categorie', 'all');
        $statut = $request->query('statut', 'all');
        $search = $request->query('search', '');
        
        // Récupérer tous les produits
        $actifOnly = false;
        $produitsCollection = $this->produitService->getAllProduits($actifOnly);
        
        // Appliquer les filtres
        $produitsCollection = $produitsCollection->filter(function($produit) use ($categorie, $statut, $search) {
            // Filtre par catégorie
            if ($categorie !== 'all' && $produit->categorie !== $categorie) {
                return false;
            }
            
            // Filtre par statut
            if ($statut === 'actif' && !$produit->actif) {
                return false;
            }
            if ($statut === 'inactif' && $produit->actif) {
                return false;
            }
            
            // Filtre par recherche
            if (!empty($search)) {
                $searchLower = strtolower($search);
                $nomLower = strtolower($produit->nom);
                if (strpos($nomLower, $searchLower) === false) {
                    return false;
                }
            }
            
            return true;
        });
        
        // Paginer les résultats
        $perPage = 12;
        $currentPage = $request->query('page', 1);
        
        $produits = new \Illuminate\Pagination\LengthAwarePaginator(
            $produitsCollection->forPage($currentPage, $perPage),
            $produitsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        return view('produits.index', compact('produits', 'categorie', 'statut', 'search'));
    }

    public function parCategorie($categorie, Request $request)
    {
        $actifOnly = $request->query('actif_only', true);
        $produits = $this->produitService->getProduitsParCategorie($categorie, $actifOnly);
        
        return view('produits.categorie', compact('produits', 'categorie'));
    }

    public function create()
    {
        return view('produits.create');
    }

    public function store(Request $request)
    {
        try {
            \Log::info('=== DÉBUT CRÉATION PRODUIT ===');
            \Log::info('Données reçues:', $request->all());

            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prix' => 'required|numeric|min:0',
                'categorie' => 'required|in:boulangerie,patisserie',
                'actif' => 'boolean',
            ]);

            \Log::info('Données validées:', $validated);

            $produit = $this->produitService->createProduit($validated);

            \Log::info('Produit créé avec succès:', ['id' => $produit->id ?? 'N/A']);

            $isFrench = true;

            $response = [
                'success' => true,
                'message' => $isFrench 
                    ? 'Produit créé avec succès.' 
                    : 'Product created successfully.',
                'data' => $produit
            ];

            \Log::info('Réponse envoyée:', $response);
            \Log::info('=== FIN CRÉATION PRODUIT ===');

            return response()->json($response, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erreur de validation:', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $produit = $this->produitService->getProduitById($id);
        return view('produits.edit', compact('produit'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prix' => 'required|numeric|min:0',
                'categorie' => 'required|in:boulangerie,patisserie',
                'actif' => 'boolean',
            ]);

            $produit = $this->produitService->updateProduit($id, $validated);
            
            $isFrench = true;
            
            // Si c'est une requête AJAX, retourner JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $isFrench 
                        ? 'Produit modifié avec succès' 
                        : 'Product updated successfully',
                    'data' => $produit
                ]);
            }
            
            // Sinon, redirection classique
            return redirect()->route('produits.index')->with('success', 'Produit modifié avec succès');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation.',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ✅ CORRECTION: Retourner du JSON au lieu d'une redirection
    public function toggleActif($id)
    {
        try {
            $produit = $this->produitService->toggleActif($id);
            
            $isFrench = true;
            
            return response()->json([
                'success' => true,
                'message' => $isFrench 
                    ? 'Statut modifié avec succès' 
                    : 'Status changed successfully',
                'data' => $produit
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ CORRECTION: Retourner du JSON au lieu d'une redirection
    public function destroy($id)
    {
        try {
            $this->produitService->deleteProduit($id);
            
            $isFrench = true;
            
            return response()->json([
                'success' => true,
                'message' => $isFrench 
                    ? 'Produit supprimé avec succès' 
                    : 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}