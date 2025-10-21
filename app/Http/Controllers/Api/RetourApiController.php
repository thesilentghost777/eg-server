<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRetourRequest;
use App\Services\RetourService;
use Illuminate\Http\Request;

class RetourApiController extends Controller
{
    protected $retourService;

    public function __construct(RetourService $retourService)
    {
        $this->retourService = $retourService;
    }

    public function store(CreateRetourRequest $request)
    {
        try {
            $retour = $this->retourService->createRetour(
                $request->validated(),
                $request->user()->id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Retour enregistré avec succès',
                'data' => $retour,
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
        try {
            $retour = $this->retourService->updateRetour(
                $id,
                $request->all(),
                $request->user()->id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Retour modifié avec succès',
                'data' => $retour,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function retoursVendeur(Request $request)
    {
        try {
            $date = $request->query('date');
            $retours = $this->retourService->getRetoursParVendeur(
                $request->user()->id,
                $date
            );
            
            return response()->json([
                'success' => true,
                'data' => $retours,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
