<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateReceptionRequest;
use App\Services\ReceptionService;
use Illuminate\Http\Request;

class ReceptionApiController extends Controller
{
    protected $receptionService;

    public function __construct(ReceptionService $receptionService)
    {
        $this->receptionService = $receptionService;
    }

    public function store(CreateReceptionRequest $request)
    {
        try {
            $reception = $this->receptionService->createReception(
                $request->validated(),
                $request->user()->id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Réception enregistrée avec succès',
                'data' => $reception,
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
            $reception = $this->receptionService->updateReception(
                $id,
                $request->all(),
                $request->user()->id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Réception modifiée avec succès',
                'data' => $reception,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function mesReceptions(Request $request)
    {
        try {
            $date = $request->query('date');
            $receptions = $this->receptionService->getReceptionsParPointeur(
                $request->user()->id,
                $date
            );
            
            return response()->json([
                'success' => true,
                'data' => $receptions,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function receptionsVendeur(Request $request)
    {
        try {
            $date = $request->query('date');
            $receptions = $this->receptionService->getReceptionsParVendeur(
                $request->user()->id,
                $date
            );
            
            return response()->json([
                'success' => true,
                'data' => $receptions,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
