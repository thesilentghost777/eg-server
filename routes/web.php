<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GhostController;
use App\Http\Controllers\LanguageController;



Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

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

require __DIR__.'/auth.php';
