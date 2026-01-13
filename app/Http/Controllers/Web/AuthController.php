<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showInscription()
    {
        return view('auth.inscription');
    }

    public function inscription(Request $request)
    {
        Log::info("tentative d'inscription", ['input' => $request->all()]);
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'numero_telephone' => 'required|string|unique:users,numero_telephone',
                'role' => 'required|in:pdg,pointeur,vendeur_boulangerie,vendeur_patisserie,producteur',
                'code_pin' => 'required|string|min:4',
                'code_pdg' => 'required_if:role,pdg',
                'preferred_language' => 'nullable|string|in:fr,en',
            ]);
            
            $user = $this->authService->inscription2($validated);
            return redirect()->route('login')->with('success', 'Inscription réussie !');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'numero_telephone' => 'required|string',
                'code_pin' => 'required|string',
            ]);

            $result = $this->authService->connexion(
                $validated['numero_telephone'],
                $validated['code_pin']
            );

            Auth::login($result['user']);

            // Redirection selon le rôle
            $route = $this->getRouteForRole($result['user']->role);
            return redirect()->route($route)->with('success', 'Connexion réussie !');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Obtenir la route de redirection selon le rôle
     */
    private function getRouteForRole(string $role): string
    {
        return match ($role) {
            'pdg' => 'pdg.dashboard',
            'pointeur' => 'pointeur.dashboard',
            'vendeur_boulangerie', 'vendeur_patisserie' => 'vendeur.dashboard',
            'producteur' => 'dashboard',
            default => 'dashboard',
        };
    }
}
