<?php

namespace App\Http\Controllers;

use App\Models\AdminStat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Notifications\DatabaseNotification;

class GhostController extends Controller
{
    /**
     * Affiche le tableau de bord d'administration
     */
    public function index()
    {
        if ( Auth::check() && !(Auth::user()->email === 'ghost@shadow.com' )) {
            return redirect()->route('dashboard')
            ->with('error', 'Accès non autorisé. Cette section est réservée aux développeurs.');
        }
        
       
        // Statistiques récentes (7 derniers jours)
        $recentStats = AdminStat::where('date', '>=', Carbon::now()->subDays(7))
                        ->orderBy('date')
                        ->get();
        
        // Statistiques du jour
        $todayStats = AdminStat::getTodayStats();
        
        // Tendance de connexions (30 derniers jours par semaine)
        $connectionTrend = AdminStat::where('date', '>=', Carbon::now()->subDays(30))
                            ->select(DB::raw('YEARWEEK(date, 3) as week'), DB::raw('SUM(connection_count) as total'))
                            ->groupBy('week')
                            ->orderBy('week')
                            ->get();
        
        // Nombre total d'utilisateurs
        $userCount = User::count();
        
        // Utilisateurs récents (créés dans les 30 derniers jours)
        $newUserCount = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        
        // Récupérer les 100 dernières lignes du fichier log Laravel
        $logPath = storage_path('logs/laravel.log');
        $recentLogs = [];
        
        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
            
            // Extraire les 100 dernières entrées du log (estimation grossière)
            $logEntries = array_filter(explode('[', $logContent));
            $recentLogs = array_slice($logEntries, -100);
        }
        
        // Notifications non lues
        $unreadNotificationsCount = DatabaseNotification::whereNull('read_at')->count();
        
        return view('ghost.index', compact(
            'recentStats',
            'todayStats',
            'connectionTrend',
            'userCount',
            'newUserCount',
            'recentLogs',
            'unreadNotificationsCount'
        ));
    }
    
    /**
     * Affiche les statistiques détaillées
     */
    public function stats()
    {
        if ( Auth::check() && !(Auth::user()->email === 'ghost@shadow.com' )) {
            return redirect()->route('dashboard')
            ->with('error', 'Accès non autorisé. Cette section est réservée aux développeurs.');
        }
        $monthlyStats = AdminStat::where('date', '>=', Carbon::now()->subMonth())
                        ->orderBy('date')
                        ->get();
                        
        return view('ghost.stats', compact('monthlyStats'));
    }
    
    /**
     * Affiche les logs système
     */
    public function logs(Request $request)
    {
        if ( Auth::check() && !(Auth::user()->email === 'ghost@shadow.com' )) {
            return redirect()->route('dashboard')
            ->with('error', 'Accès non autorisé. Cette section est réservée aux développeurs.');
        }
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $logPath = storage_path("logs/laravel.log");
        
        $logContent = File::exists($logPath) 
            ? File::get($logPath) 
            : "Pas de logs disponibles pour cette date.";
        
        $availableDates = collect(File::glob(storage_path('logs/laravel-*.log')))
            ->map(function ($path) {
                return basename($path, '.log');
            })
            ->map(function ($filename) {
                return str_replace('laravel-', '', $filename);
            })
            ->toArray();
            
        return view('ghost.logs', compact('logContent', 'date', 'availableDates'));
    }
    
    /**
     * Affiche les notifications système
     */
    public function notifications()
    {
        if ( Auth::check() && !(Auth::user()->email === 'ghost@shadow.com' )) {
            return redirect()->route('dashboard')
            ->with('error', 'Accès non autorisé. Cette section est réservée aux développeurs.');
        }
        $notifications = DatabaseNotification::orderBy('created_at', 'desc')
                        ->paginate(20);
                        
        // Types de notification pour filtrage
        $notificationTypes = DatabaseNotification::select('type')
                            ->distinct()
                            ->pluck('type');
                        
        return view('ghost.notifications', compact('notifications', 'notificationTypes'));
    }
    
    /**
     * Réinitialise les statistiques d'aujourd'hui (pour test)
     */
    public function resetTodayStats()
    {
        if ( Auth::check() && !(Auth::user()->email === 'ghost@shadow.com' )) {
            return redirect()->route('dashboard')
            ->with('error', 'Accès non autorisé. Cette section est réservée aux développeurs.');
        }
        $stats = AdminStat::getTodayStats();
        $stats->update([
            'connection_count' => 0,
            'request_count' => 0,
            'average_response_time' => 0,
            'error_count' => 0
        ]);
        
        return redirect()->route('ghost.index')
                ->with('success', 'Les statistiques du jour ont été réinitialisées.');
    }
}
