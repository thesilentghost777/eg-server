<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InscriptionRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthApiController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function inscription(InscriptionRequest $request)
    {
        try {
            $user = $this->authService->inscription($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie',
                'data' => $user,
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function connexion(Request $request)
    {
        $request->validate([
            'numero_telephone' => 'required|string',
            'code_pin' => 'required|string',
        ]);

        try {
            $result = $this->authService->connexion(
                $request->numero_telephone,
                $request->code_pin
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => $result,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    public function deconnexion(Request $request)
    {
        try {
            $this->authService->deconnexion($request->user());
            
            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }
}
