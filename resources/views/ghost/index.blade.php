@extends('ghost.layout')

@section('admin-content')
<h3 class="text-xl font-bold text-gray-800 mb-4">Vue d'ensemble du système</h3>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <h4 class="text-sm font-semibold text-gray-500 mb-1">Connexions aujourd'hui</h4>
        <p class="text-2xl font-bold">{{ $todayStats->connection_count }}</p>
        <div class="text-xs text-gray-500 mt-1">
            {{ now()->format('d/m/Y') }}
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow">
        <h4 class="text-sm font-semibold text-gray-500 mb-1">Requêtes aujourd'hui</h4>
        <p class="text-2xl font-bold">{{ $todayStats->request_count }}</p>
        <div class="text-xs text-gray-500 mt-1">
            Temps moyen: {{ $todayStats->average_response_time }} ms
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow">
        <h4 class="text-sm font-semibold text-gray-500 mb-1">Erreurs aujourd'hui</h4>
        <p class="text-2xl font-bold @if($todayStats->error_count > 0) text-red-600 @endif">
            {{ $todayStats->error_count }}
        </p>
        <div class="text-xs text-gray-500 mt-1">
            Taux: {{ $todayStats->request_count > 0 ? round(($todayStats->error_count / $todayStats->request_count) * 100, 2) : 0 }}%
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow">
        <h4 class="text-sm font-semibold text-gray-500 mb-1">Utilisateurs</h4>
        <p class="text-2xl font-bold">{{ $userCount }}</p>
        <div class="text-xs text-gray-500 mt-1">
            +{{ $newUserCount }} dans les 30 derniers jours
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <h4 class="text-sm font-semibold text-gray-700 mb-4">Statistiques récentes (7 jours)</h4>
        <div class="h-64">
            <canvas id="recentStatsChart"></canvas>
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow">
        <h4 class="text-sm font-semibold text-gray-700 mb-4">Tendance des connexions (30 jours)</h4>
        <div class="h-64">
            <canvas id="connectionTrendChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-sm font-semibold text-gray-700">Logs récents</h4>
            <a href="{{ route('admin.logs') }}" class="text-sm text-blue-600 hover:text-blue-800">Voir tout</a>
        </div>
        <div class="overflow-auto h-64 bg-gray-100 p-2 rounded text-xs font-mono">
            @forelse($recentLogs as $log)
                <div class="mb-2 pb-2 border-b border-gray-200">
                    {{ $log }}
                </div>
            @empty
                <p class="text-gray-500">Aucun log récent disponible.</p>
            @endforelse
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-sm font-semibold text-gray-700">Actions développeur</h4>
        </div>
        <ul class="space-y-2">
            <li>
                <form action="{{ route('admin.reset-today-stats') }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">
                        Réinitialiser stats du jour
                    </button>
                </form>
            </li>
            <li class="pt-2">
                <button onclick="window.location.reload()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                    Rafraîchir le tableau de bord
                </button>
            </li>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des statistiques récentes
    const recentStats = @json($recentStats);
    
    new Chart(document.getElementById('recentStatsChart'), {
        type: 'line',
        data: {
            labels: recentStats.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit'});
            }),
            datasets: [
                {
                    label: 'Connexions',
                    data: recentStats.map(item => item.connection_count),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Requêtes',
                    data: recentStats.map(item => item.request_count),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                    borderDash: [5, 5]
                },
                {
                    label: 'Erreurs',
                    data: recentStats.map(item => item.error_count),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'transparent',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Données pour le graphique de tendance des connexions
    const connectionTrend = @json($connectionTrend);
    
    new Chart(document.getElementById('connectionTrendChart'), {
        type: 'bar',
        data: {
            labels: connectionTrend.map(item => `Semaine ${item.week.toString().substr(-2)}`),
            datasets: [
                {
                    label: 'Connexions par semaine',
                    data: connectionTrend.map(item => item.total),
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection
