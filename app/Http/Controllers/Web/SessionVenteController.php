<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\SessionVenteService;
use Illuminate\Http\Request;

class SessionVenteController extends Controller
{
    protected $sessionVenteService;

    public function __construct(SessionVenteService $sessionVenteService)
    {
        $this->sessionVenteService = $sessionVenteService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['statut', 'date_debut', 'date_fin']);
        $vendeurId = $request->user()->role === 'vendeur' ? $request->user()->id : null;
        $sessions = $this->sessionVenteService->getHistorique($vendeurId, $filters);
        
        return view('sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function ouvrir(Request $request)
    {
        try {
            $validated = $request->validate([
                'fond_vente' => 'required|numeric|min:0',
                'orange_money_initial' => 'required|numeric|min:0',
                'mtn_money_initial' => 'required|numeric|min:0',
            ]);

            $session = $this->sessionVenteService->ouvrirSession($validated, $request->user()->id);

            return redirect()->route('sessions.active')->with('success', 'Session ouverte avec succès');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function showActive(Request $request)
    {
        try {
            $session = $this->sessionVenteService->getSessionActive($request->user()->id);
            
            if (!$session) {
                return redirect()->route('sessions.create')->with('info', 'Aucune session active. Ouvrez-en une nouvelle.');
            }

            return view('sessions.active', compact('session'));
        } catch (\Exception $e) {
            return redirect()->route('sessions.index')->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $session = $this->sessionVenteService->getSessionById($id);
            return view('sessions.show', compact('session'));
        } catch (\Exception $e) {
            return redirect()->route('sessions.index')->with('error', $e->getMessage());
        }
    }

    public function showFermer($id)
    {
        $session = $this->sessionVenteService->getSessionById($id);
        return view('sessions.fermer', compact('session'));
    }

    public function fermer(Request $request, $id)
    {
        try {
            // Vérifier les permissions
            if ($request->user()->role !== 'pdg') {
                return back()->with('error', 'Seul le PDG peut fermer une session');
            }

            $validated = $request->validate([
                'montant_verse' => 'required|numeric|min:0',
                'orange_money_final' => 'required|numeric|min:0',
                'mtn_money_final' => 'required|numeric|min:0',
            ]);

            $result = $this->sessionVenteService->fermerSession($id, $validated, $request->user()->id);

            return redirect()->route('sessions.show', $id)->with('success', 'Session fermée avec succès');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function historique(Request $request)
    {
        $filters = $request->only(['statut', 'date_debut', 'date_fin']);
        $vendeurId = $request->user()->role === 'vendeur' ? $request->user()->id : null;
        $sessions = $this->sessionVenteService->getHistorique($vendeurId, $filters);
        
        return view('sessions.historique', compact('sessions'));
    }

    public function edit(Request $request, $id)
{
    if ($request->user()->role !== 'pdg') {
        return redirect()->route('sessions.index')->with('error', 'Accès non autorisé');
    }
    
    $session = $this->sessionVenteService->getSessionById($id);
    return view('sessions.edit', compact('session'));
}

public function update(Request $request, $id)
{
    try {
        if ($request->user()->role !== 'pdg') {
            return back()->with('error', 'Seul le PDG peut modifier une session');
        }

        $validated = $request->validate([
            'fond_vente' => 'required|numeric|min:0',
            'orange_money_initial' => 'required|numeric|min:0',
            'mtn_money_initial' => 'required|numeric|min:0',
            'montant_verse' => 'nullable|numeric|min:0',
            'orange_money_final' => 'nullable|numeric|min:0',
            'mtn_money_final' => 'nullable|numeric|min:0',
        ]);

        $session = $this->sessionVenteService->modifierSession($id, $validated, $request->user()->id);

        return redirect()->route('sessions.show', $id)->with('success', 'Session modifiée avec succès');
    } catch (\Exception $e) {
        return back()->withInput()->with('error', $e->getMessage());
    }
}

}
