<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AdminStat;
use Illuminate\Support\Facades\Auth;

class TrackStatistics
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Marquer le temps de début
        $startTime = microtime(true);
        
        // Incrémenter le compteur de requêtes
        AdminStat::incrementRequestCount();
        
        // Si l'utilisateur vient de se connecter
        if ($request->is('login') && $request->isMethod('post')) {
            AdminStat::incrementConnectionCount();
        }
        
        // Continuer avec la requête
        $response = $next($request);
        
        // Mesurer le temps de réponse
        $responseTime = round((microtime(true) - $startTime) * 1000); // En millisecondes
        AdminStat::addResponseTime($responseTime);
        
        // Si la réponse est une erreur (4xx ou 5xx)
        if ($response->getStatusCode() >= 400) {
            AdminStat::incrementErrorCount();
        }
        
        return $response;
    }
}
