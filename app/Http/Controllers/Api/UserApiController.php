<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();
            
            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function parRole($role)
    {
        try {
            $users = $this->userService->getUsersByRole($role);
            
            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'numero_telephone' => 'required|string|unique:users,numero_telephone',
            'role' => 'required|in:pdg,pointeur,vendeur_boulangerie,vendeur_patisserie,producteur',
            'code_pin' => 'required|string|size:6',
        ]);

        try {
            $user = $this->userService->createUser($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'data' => $user,
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
            $user = $this->userService->updateUser($id, $request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur modifié avec succès',
                'data' => $user,
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
            $user = $this->userService->toggleActif($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Statut modifié avec succès',
                'data' => $user,
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
            $this->userService->deleteUser($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function producteurs()
    {
        try {
            $users = $this->userService->getProducteurs();
            
            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
