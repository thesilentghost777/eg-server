<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\InscriptionRequest;
use App\Http\Requests\ConnexionRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AuthApiController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Inscription d'un nouvel utilisateur
     */
    public function inscription(InscriptionRequest $request)
    {
        try {
            $result = $this->authService->inscription($request->validated());
            $user = $result['user'];
            $clientId = $result['client_id'];
            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info("Inscription réussie", [
                'user_id' => $user->id,
                'client_id' => $clientId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie',
                'user' => $user,
                'token' => $token,
                'client_id' => $clientId, // ✅ Client ID renvoyé
            ], 201);
        } catch (\Exception $e) {
            Log::error("Erreur inscription: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Connexion d'un utilisateur
     */
    public function connexion(ConnexionRequest $request)
    {
        try {
            $result = $this->authService->connexion(
                $request->numero_telephone,
                $request->code_pin
            );

            Log::info("Connexion réussie pour l'utilisateur ID: " . $result['user']->id, [
                'client_id' => $result['client_id'] ?? 'N/A'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'user' => $result['user'],
                'token' => $result['token'],
                'client_id' => $result['client_id'], // ✅ Client ID renvoyé
            ], 200);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la connexion: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Déconnexion d'un utilisateur
     */
    public function deconnexion(Request $request)
    {
        try {
            $this->authService->deconnexion($request->user());
            Log::info("Déconnexion réussie pour l'utilisateur ID: " . $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie',
            ], 200);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la déconnexion: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion',
            ], 500);
        }
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ], 200);
    }
}