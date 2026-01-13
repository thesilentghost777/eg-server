<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GhostController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProduitController;
use App\Http\Controllers\Web\SessionVenteController;
use App\Http\Controllers\Web\FluxProduitController;
use App\Http\Controllers\Web\PdgController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\RetourProduitController;
use App\Http\Controllers\Web\VerrouillageController;



Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/inscription', [AuthController::class, 'showInscription'])->name('inscription');
Route::post('/inscription', [AuthController::class, 'inscription'])->name('inscription.post');

Route::get('/easygest-bp', function () {
    return view('landing.easygest-bp');
})->name('landing.easygest-bp');

Route::get('/', function () {
    return view('welcome');
});



Route::get('/test-error', function () {
    // Déclenche une exception volontaire pour tester ton système d'erreur
    throw new \Exception('Ceci est une erreur de test !');
});

Route::middleware('auth','track_statistic')->group(function () {
    Route::post('/lang/fr', [LanguageController::class, 'setFrench'])->name('lang.fr');
    Route::post('/lang/en', [LanguageController::class, 'setEnglish'])->name('lang.en');
   
    Route::get('/ghost', [GhostController::class, 'index'])->name('ghost.index');
    Route::get('/stats', [GhostController::class, 'stats'])->name('ghost.stats');
    Route::get('/logs', [GhostController::class, 'logs'])->name('ghost.logs');
    Route::get('/notifications', [GhostController::class, 'notifications'])->name('ghost.notifications');
    Route::post('/reset-today-stats', [GhostController::class, 'resetTodayStats'])->name('ghost.reset-today-stats');
});



// Routes protégées
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
     // Gestion des retours
    Route::get('/retours', [RetourProduitController::class, 'index'])->name('retours.index');
    Route::get('/retours/{retour}', [RetourProduitController::class, 'show'])->name('retours.show');
    Route::get('/retours/{retour}/edit', [RetourProduitController::class, 'edit'])->name('retours.edit');
    Route::put('/retours/{retour}', [RetourProduitController::class, 'update'])->name('retours.update');
    Route::delete('/retours/{retour}', [RetourProduitController::class, 'destroy'])->name('retours.destroy');

    // Gestion du verrouillage
    Route::get('/verrouillage', [VerrouillageController::class, 'index'])->name('verrouillage.index');
    Route::post('/verrouillage/verrouiller', [VerrouillageController::class, 'verrouiller'])->name('verrouillage.verrouiller');
    Route::post('/verrouillage/deverrouiller', [VerrouillageController::class, 'deverrouiller'])->name('verrouillage.deverrouiller');
    
    // Produits
    Route::prefix('produits')->name('produits.')->group(function () {
        Route::get('/', [ProduitController::class, 'index'])->name('index');
        Route::get('/create', [ProduitController::class, 'create'])->name('create');
        Route::post('/', [ProduitController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProduitController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProduitController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProduitController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [ProduitController::class, 'toggleActif'])->name('toggle');
        Route::get('/categorie/{categorie}', [ProduitController::class, 'parCategorie'])->name('categorie');
    });
    
    // Sessions de vente
    Route::prefix('sessions-vente')->name('sessions.')->group(function () {
    Route::get('/', [SessionVenteController::class, 'index'])->name('index');
    Route::get('/historique', [SessionVenteController::class, 'historique'])->name('historique');
    Route::get('/active', [SessionVenteController::class, 'showActive'])->name('active');
    Route::get('/{id}', [SessionVenteController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SessionVenteController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SessionVenteController::class, 'update'])->name('update');
    Route::get('/{id}/fermer', [SessionVenteController::class, 'showFermer'])->name('fermer.form');
    Route::post('/{id}/fermer', [SessionVenteController::class, 'fermer'])->name('fermer');

   
});

    
    // Flux produits (pour vendeurs)
    Route::prefix('flux')->name('flux.')->group(function () {
        Route::get('/vendeur/{vendeurId}', [FluxProduitController::class, 'fluxVendeur'])->name('vendeur');
        Route::get('/tous', [FluxProduitController::class, 'fluxTous'])->name('tous');
    });
    
    // Routes PDG
    Route::middleware(['role:pdg'])->prefix('pdg')->name('pdg.')->group(function () {
        Route::get('/dashboard', [PdgController::class, 'dashboard'])->name('dashboard');
        Route::get('/receptions', [PdgController::class, 'receptions'])->name('receptions');
        Route::get('/inventaires', [PdgController::class, 'inventaires'])->name('inventaires');
        Route::get('/sessions-vente', [PdgController::class, 'sessionsVente'])->name('sessions');
        Route::get('/flux-operationnel', [PdgController::class, 'fluxOperationnel'])->name('flux');        
        Route::get('/flux-operationnel/form', [PdgController::class, 'fluxOperationnelForm'])->name('flux.form');
        Route::get('/flux-operationnel/imprimer', [PdgController::class, 'imprimerFlux'])->name('flux.imprimer');
        Route::get('/sessions-detaillees', [PdgController::class, 'sessionsDetaillees'])->name('sessions.detaillees');
        Route::get('/sessions/{id}/imprimer', [PdgController::class, 'imprimerSession'])->name('session.imprimer');
        Route::get('/sessions-detaillees/imprimer', [PdgController::class, 'imprimerSessionsDetaillees'])->name('sessions.imprimer');
        Route::get('/statistiques', [PdgController::class, 'statistiques'])->name('statistiques');
        Route::get('/vendeurs-performance', [PdgController::class, 'vendeursPerformance'])->name('vendeurs.performance');
    });
});

// Routes pour la gestion des utilisateurs (PDG uniquement)
Route::middleware(['auth', 'role:pdg'])->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/role/{role}', [UserController::class, 'parRole'])->name('users.parRole');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/{id}/toggle', [UserController::class, 'toggleActif'])->name('users.toggleActif');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/producteurs', [UserController::class, 'producteurs'])->name('users.producteurs');
});

