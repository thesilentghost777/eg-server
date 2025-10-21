<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Automatiquement préfixé avec /api
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'track_statistic' => \App\Http\Middleware\TrackStatistics::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configuration du reporting personnalisé
        $exceptions->report(function (Throwable $e) {
            // Log l'erreur dans notre système personnalisé
            app(\App\Services\ErrorLogService::class)->logError($e);
        });
        
        // Configuration du rendu personnalisé
        $exceptions->render(function (Throwable $e, $request) {
            // Gestion des erreurs serveur (5xx)
            if (method_exists($e, 'getStatusCode')) {
                $statusCode = $e->getStatusCode();
                
                if ($statusCode >= 500) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'error' => 'Erreur serveur interne',
                            'message' => config('app.debug') ? $e->getMessage() : 'Une erreur s\'est produite',
                            'status' => $statusCode
                        ], $statusCode);
                    }
                    
                    if (view()->exists('errors.server')) {
                        return response()->view('errors.server', [
                            'exception' => $e,
                            'statusCode' => $statusCode
                        ], $statusCode);
                    }
                }
            }
            
            // Laisser Laravel gérer les autres cas
            return null;
        });
    })
    ->create();
