<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProduitApiController;
use App\Http\Controllers\Api\ReceptionApiController;
use App\Http\Controllers\Api\RetourApiController;
use App\Http\Controllers\Api\InventaireApiController;
use App\Http\Controllers\Api\SessionVenteApiController;
use App\Http\Controllers\Api\FluxProduitApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\PdgApiController;
use App\Http\Controllers\Api\SyncApiController;
/*
|--------------------------------------------------------------------------
| API Routes - Boulangerie Pâtisserie
|--------------------------------------------------------------------------
*/

//route de test
Route::get('/test', function () {
    return response()->json(['message' => 'The ghost is in the shadow.']);
});

 Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'EasyGestBP API is healthy'
    ], 200);
});

//route de synchronisation
Route::prefix('sync')->group(function () {
        Route::get('/pull', [SyncApiController::class, 'pull']);
        Route::post('/push', [SyncApiController::class, 'push']);
        Route::get('/status', [SyncApiController::class, 'status']);
        Route::post('/ack', [SyncApiController::class, 'acknowledgement']);
    });


// Routes publiques
Route::prefix('auth')->group(function () {
    Route::post('/inscription', [AuthApiController::class, 'inscription']);
    Route::post('/connexion', [AuthApiController::class, 'connexion']);
});

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/deconnexion', [AuthApiController::class, 'deconnexion']);
        Route::get('/me', [AuthApiController::class, 'me']);
    });

   

    // Produits (PDG uniquement pour créer/modifier)
    Route::prefix('produits')->group(function () {
        Route::get('/', [ProduitApiController::class, 'index']);
        Route::get('/categorie/{categorie}', [ProduitApiController::class, 'parCategorie']);
        Route::post('/', [ProduitApiController::class, 'store'])->middleware('role:pdg');
        Route::put('/{id}', [ProduitApiController::class, 'update'])->middleware('role:pdg');
        Route::post('/{id}/toggle-actif', [ProduitApiController::class, 'toggleActif'])->middleware('role:pdg');
        Route::delete('/{id}', [ProduitApiController::class, 'destroy'])->middleware('role:pdg');
    });

    // Réceptions (Pointeur uniquement)
    Route::middleware('role:pointeur')->prefix('receptions')->group(function () {
        Route::post('/', [ReceptionApiController::class, 'store']);
        Route::put('/{id}', [ReceptionApiController::class, 'update']);
        Route::get('/mes-receptions', [ReceptionApiController::class, 'mesReceptions']);
    });

    // Réceptions pour vendeurs (lecture seule)
    Route::get('/vendeur/receptions', [ReceptionApiController::class, 'receptionsVendeur'])
        ->middleware('role:vendeur_boulangerie,vendeur_patisserie');

    // Retours (Pointeur uniquement)
    Route::middleware('role:pointeur')->prefix('retours')->group(function () {
        Route::post('/', [RetourApiController::class, 'store']);
        Route::put('/{id}', [RetourApiController::class, 'update']);
    });

    // Retours pour vendeurs (lecture seule)
    Route::get('/vendeur/retours', [RetourApiController::class, 'retoursVendeur'])
        ->middleware('role:vendeur_boulangerie,vendeur_patisserie');

    // Inventaires (Vendeurs uniquement)
    Route::middleware('role:vendeur_boulangerie,vendeur_patisserie')->prefix('inventaires')->group(function () {
        Route::post('/creer', [InventaireApiController::class, 'creer']);
        Route::get('/mes-inventaires', [InventaireApiController::class, 'mesInventaires']);
        Route::get('/en-cours', [InventaireApiController::class, 'enCours']);
    });

    // Sessions de vente
    Route::prefix('sessions-vente')->group(function () {
        // Vendeurs
        Route::middleware('role:vendeur_boulangerie,vendeur_patisserie')->group(function () {
            Route::post('/ouvrir', [SessionVenteApiController::class, 'ouvrir']);
            Route::get('/active', [SessionVenteApiController::class, 'getActive']);
            Route::get('/historique', [SessionVenteApiController::class, 'historique']);
        });
        
        // PDG
        Route::middleware('role:pdg')->group(function () {
            Route::get('/{id}', [SessionVenteApiController::class, 'show']);
            Route::get('/{id}/apercu-ventes', [SessionVenteApiController::class, 'apercuVentes']);
            Route::post('/{id}/fermer', [SessionVenteApiController::class, 'fermer']);
        });
    });

    // Flux de produits
    Route::prefix('flux')->group(function () {
        // Vendeurs voient leur propre flux
        Route::get('/mon-flux', [FluxProduitApiController::class, 'monFlux'])
            ->middleware('role:vendeur_boulangerie,vendeur_patisserie');
        
        // PDG voit tous les flux
        Route::middleware('role:pdg')->group(function () {
            Route::get('/vendeur/{vendeurId}', [FluxProduitApiController::class, 'fluxVendeur']);
            Route::get('/tous', [FluxProduitApiController::class, 'fluxTous']);
        });
    });

    // Gestion des utilisateurs (PDG uniquement)
    Route::middleware('role:pdg')->prefix('users')->group(function () {
        Route::get('/', [UserApiController::class, 'index']);
        Route::get('/role/{role}', [UserApiController::class, 'parRole']);
        Route::get('/producteurs', [UserApiController::class, 'producteurs']);
        Route::post('/', [UserApiController::class, 'store']);
        Route::put('/{id}', [UserApiController::class, 'update']);
        Route::post('/{id}/toggle-actif', [UserApiController::class, 'toggleActif']);
        Route::delete('/{id}', [UserApiController::class, 'destroy']);
    });

    Route::prefix('pdg')->middleware(['auth:sanctum', 'role:pdg'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PdgApiController::class, 'dashboard']);
    
    // Filtrage des données
    Route::get('/receptions', [PdgApiController::class, 'getReceptions']);
    Route::get('/inventaires', [PdgApiController::class, 'getInventaires']);
    Route::get('/sessions-vente', [PdgApiController::class, 'getSessionsVente']);
    
    // Flux opérationnel
    Route::get('/flux-operationnel', [PdgApiController::class, 'getFluxOperationnel']);
    Route::get('/flux-operationnel/imprimer', [PdgApiController::class, 'imprimerFluxOperationnel']);
    
    // Sessions de vente détaillées
    Route::get('/sessions-vente-detaillees', [PdgApiController::class, 'getSessionsVenteDetaillees']);
    Route::get('/sessions-vente-detaillees/imprimer', [PdgApiController::class, 'imprimerSessionsVenteDetaillees']);
    Route::get('/sessions-vente/{id}/imprimer', [PdgApiController::class, 'imprimerSessionVente']);
    
    // Statistiques et analyses
    Route::get('/statistiques', [PdgApiController::class, 'getStatistiques']);
    Route::get('/vendeurs-performance', [PdgApiController::class, 'getVendeursPerformance']);
});
});

