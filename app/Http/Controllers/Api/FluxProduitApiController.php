<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FluxProduitService;
use Illuminate\Http\Request;

class FluxProduitApiController extends Controller
{
    protected $fluxProduitService;

    public function __construct(FluxProduitService $fluxProduitService)
    {
        $this->fluxProduitService = $fluxProduitService;
    }

    public function monFlux(Request $request)
    {
        try {
            $date = $request->query('date');
            $flux = $this->fluxProduitService->getFluxParVendeur(
                $request->user()->id,
                $date
            );
            
            return response()->json([
                'success' => true,
                'data' => $flux,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function fluxVendeur($vendeurId, Request $request)
    {
        try {
            $date = $request->query('date');
            $flux = $this->fluxProduitService->getFluxParVendeur(
                $vendeurId,
                $date
            );
            
            return response()->json([
                'success' => true,
                'data' => $flux,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function fluxTous(Request $request)
    {
        try {
            $date = $request->query('date');
            $flux = $this->fluxProduitService->getFluxTous($date);
            
            return response()->json([
                'success' => true,
                'data' => $flux,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
