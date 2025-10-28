<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\FluxProduitService;
use Illuminate\Http\Request;

class FluxProduitController extends Controller
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
            $flux = $this->fluxProduitService->getFluxParVendeur($request->user()->id, $date);
            
            return view('flux.mon-flux', compact('flux', 'date'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function fluxVendeur($vendeurId, Request $request)
    {
        try {
            $date = $request->query('date');
            $flux = $this->fluxProduitService->getFluxParVendeur($vendeurId, $date);
            
            return view('flux.vendeur', compact('flux', 'vendeurId', 'date'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function fluxTous(Request $request)
    {
        try {
            $date = $request->query('date');
            $flux = $this->fluxProduitService->getFluxTous($date);
            
            return view('flux.tous', compact('flux', 'date'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
