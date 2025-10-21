<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdminStat extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date',
        'connection_count',
        'request_count',
        'average_response_time',
        'error_count'
    ];
    
    protected $casts = [
        'date' => 'date',
    ];
    
    /**
     * Récupère ou crée les statistiques pour la date du jour
     */
    public static function getTodayStats()
    {
        $today = Carbon::today()->toDateString();
        
        $stats = self::firstOrCreate(
            ['date' => $today],
            [
                'connection_count' => 0,
                'request_count' => 0,
                'average_response_time' => 0,
                'error_count' => 0
            ]
        );
        
        return $stats;
    }
    
    /**
     * Incrémente le compteur de connexions
     */
    public static function incrementConnectionCount()
    {
        $stats = self::getTodayStats();
        $stats->increment('connection_count');
        return $stats;
    }
    
    /**
     * Incrémente le compteur de requêtes
     */
    public static function incrementRequestCount()
    {
        $stats = self::getTodayStats();
        $stats->increment('request_count');
        return $stats;
    }
    
    /**
     * Ajoute un temps de réponse et recalcule la moyenne
     */
    public static function addResponseTime(int $responseTime)
    {
        $stats = self::getTodayStats();
        
        $newAvg = $stats->request_count > 0
            ? (($stats->average_response_time * ($stats->request_count - 1)) + $responseTime) / $stats->request_count
            : $responseTime;
            
        $stats->average_response_time = round($newAvg);
        $stats->save();
        
        return $stats;
    }
    
    /**
     * Incrémente le compteur d'erreurs
     */
    public static function incrementErrorCount()
    {
        $stats = self::getTodayStats();
        $stats->increment('error_count');
        return $stats;
    }
}
