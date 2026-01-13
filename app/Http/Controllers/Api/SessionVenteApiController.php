<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SessionVenteService;
use App\Http\Requests\CreateSessionRequest;
use App\Http\Requests\FermerSessionRequest;
use Illuminate\Http\Request;

class SessionVenteApiController extends Controller
{
    protected $sessionVenteService;

    public function __construct(SessionVenteService $sessionVenteService)
    {
        $this->sessionVenteService = $sessionVenteService;
    }

    /**
     * Ouvrir une session de vente
     * POST /api/sessions-vente/ouvrir
     */
    public function ouvrir(CreateSessionRequest $request)
    {
        try {
            $session = $this->sessionVenteService->ouvrirSession(
                $request->validated(),
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Session ouverte avec succès',
                'data' => $session,
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Fermer une session de vente
     * POST /api/sessions-vente/{id}/fermer
     */
    public function fermer(FermerSessionRequest $request, $id)
    {
        try {
            // Vérifier les permissions: seul le PDG peut fermer une session
            if ($request->user()->role !== 'pdg') {
                return response()->json([
                    'success' => false,
                    'message' => 'Seul le PDG peut fermer une session',
                ], 403);
            }

            $result = $this->sessionVenteService->fermerSession(
                $id,
                $request->validated(),
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Session fermée avec succès',
                'data' => [
                    'session' => $result['session'],
                    'ventes_totales' => $result['ventes_totales'],
                    'details_calcul' => $result['details_calcul'],
                ],
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Obtenir la session active du vendeur connecté
     * GET /api/sessions-vente/active
     */
    public function getActive(Request $request)
    {
        try {
            $session = $this->sessionVenteService->getSessionActive($request->user()->id);

            if (!$session) {
                return response()->json([
                    'success' => true,
                    'message' => 'Aucune session active',
                    'data' => null,
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $session,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Obtenir l'historique des sessions
     * GET /api/sessions-vente/historique
     */
    public function historique(Request $request)
    {
        try {
            $filters = $request->only(['statut', 'date_debut', 'date_fin']);
            
            // Si vendeur, voir seulement ses sessions
            $vendeurId = $request->user()->role === 'vendeur' 
                ? $request->user()->id 
                : null;

            $sessions = $this->sessionVenteService->getHistorique($vendeurId, $filters);

            return response()->json([
                'success' => true,
                'data' => $sessions,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Obtenir les détails d'une session spécifique
     * GET /api/sessions-vente/{id}
     */
    public function show(Request $request, $id)
    {
        try {
            $session = \App\Models\SessionVente::with(['vendeur', 'fermeePar'])->findOrFail($id);

            // Si vendeur, vérifier qu'il peut voir cette session
            if ($request->user()->role === 'vendeur' && $session->vendeur_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $session,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Obtenir un aperçu des ventes en cours (avant fermeture)
     * GET /api/sessions-vente/{id}/apercu-ventes
     */
    public function apercuVentes(Request $request, $id)
    {
        try {
            $session = \App\Models\SessionVente::findOrFail($id);

            // Vérifier les permissions
            if ($request->user()->role === 'vendeur' && $session->vendeur_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé',
                ], 403);
            }

            /*if ($session->statut === 'fermee') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette session est déjà fermée',
                ], 400);
            }*/

            // Calculer temporairement les ventes pour aperçu
            $ventesTotales = $this->sessionVenteService->calculerVentesTotales($session);
            $details = $this->sessionVenteService->getDetailsCalcul($session);

            return response()->json([
                'success' => true,
                'data' => [
                    'ventes_totales_estimees' => $ventesTotales,
                    'details_par_produit' => $details,
                    'fond_vente' => $session->fond_vente,
                    'date_ouverture' => $session->date_ouverture,
                ],
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}