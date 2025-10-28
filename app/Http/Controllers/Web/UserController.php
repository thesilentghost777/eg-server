<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
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
            
            return view('users.index', compact('users'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function parRole($role)
    {
        try {
            $users = $this->userService->getUsersByRole($role);
            
            return view('users.index', compact('users', 'role'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('users.create');
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
            
            return redirect()->route('users.index')
                ->with('success', 'Utilisateur créé avec succès');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            
            return view('users.edit', compact('user'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $this->userService->updateUser($id, $request->all());
            
            return redirect()->route('users.index')
                ->with('success', 'Utilisateur modifié avec succès');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function toggleActif($id)
    {
        try {
            $user = $this->userService->toggleActif($id);
            
            return redirect()->back()
                ->with('success', 'Statut modifié avec succès');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
            
            return redirect()->route('users.index')
                ->with('success', 'Utilisateur supprimé avec succès');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function producteurs()
    {
        try {
            $users = $this->userService->getProducteurs();
            
            return view('users.producteurs', compact('users'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
