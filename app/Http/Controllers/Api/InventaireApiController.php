<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InventaireService;
use Illuminate\Http\Request;

class InventaireApiController extends Controller
{
    protected $inventaireService;

    public function __construct(InventaireService $inventaireService)
    {
        $this->inventaireService = $inventaireService;
    }

    public function creer(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'vendeur_sortant_id' => 'required|exists:users,id',
            'vendeur_entrant_id' => 'required|exists:users,id|different:vendeur_sortant_id',
            'code_pin_sortant' => 'required|string|size:6',
            'code_pin_entrant' => 'required|string|size:6',
            'produits' => 'required|array|min:1',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite_restante' => 'required|integer|min:0',
        ]);

        try {
            $inventaire = $this->inventaireService->creerInventaire($validated);

            return response()->json([
                'success' => true,
                'message' => 'Inventaire créé et validé avec succès par les deux parties',
                'data' => $inventaire,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function mesInventaires(Request $request)
    {
        try {
            $inventaires = $this->inventaireService->getInventairesParVendeur(
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'data' => $inventaires,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function enCours(Request $request)
    {
        try {
            $inventaire = $this->inventaireService->getInventaireEnCours(
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'data' => $inventaire,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}