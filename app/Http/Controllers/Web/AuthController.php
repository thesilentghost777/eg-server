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
    Log::info('--- Début de la méthode inscription ---');

    try {
        Log::info('Données reçues pour inscription', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'numero_telephone' => 'required|string|unique:users,numero_telephone',
            'role' => 'required|in:pdg,pointeur,vendeur_boulangerie,vendeur_patisserie,producteur',
            'code_pin' => 'required|string|min:4',
            'code_pdg' => 'required_if:role,pdg',
            'preferred_language' => 'nullable|string|in:fr,en',
        ]);

        Log::info('Validation réussie', $validated);

        
        $user = $this->authService->inscription2($validated);

        Log::info('Utilisateur créé avec succès', [
            'user_id' => $user->id ?? null,
            'role' => $user->role ?? null,
            'telephone' => $user->numero_telephone ?? null,
        ]);

        Log::info('--- Fin normale de la méthode inscription ---');

        return redirect()->route('login')->with('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('Erreur de validation lors de l’inscription', [
            'errors' => $e->errors(),
            'input' => $request->all(),
        ]);
        return back()->withInput()->withErrors($e->errors());
    } catch (\Throwable $e) {
        Log::error('Erreur inattendue lors de l’inscription', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
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

            // Connecter l'utilisateur via session web
            Auth::login($result['user']);

            return redirect()->route('pdg.dashboard')->with('success', 'Connexion réussie !');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Déconnexion réussie !');
    }
}
