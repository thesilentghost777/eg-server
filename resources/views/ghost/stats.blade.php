@extends('ghost.layout')

@section('admin-content')
<h3 class="text-xl font-bold text-gray-800 mb-4">Statistiques détaillées</h3>

<div class="mb-8">
    <div class="bg-white rounded-lg shadow p-4">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">Statistiques du mois</h4>
        <div class="h-80">
            <canvas id="monthlyStatsChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">Performance par jour de semaine</h4>
        <div class="h-64">
            <canvas id="weekdayPerformanceChart"></canvas>
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">Temps de réponse moyen</h4>
        <div class="h-64">
            <canvas id="responseTimeChart"></canvas>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow">
    <h4 class="text-lg font-semibold text-gray-700 mb-4">Données brutes</h4>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Connexions</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Requêtes</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Temps moyen (ms)</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Erreurs</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Taux d'erreur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyStats as $stat)
                <tr class="border-b">
                    <td class="px-4 py-2 text-sm">{{ Carbon\Carbon::parse($stat->date)->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-sm">{{ $stat->connection_count }}</td>
                    <td class="px-4 py-2 text-sm">{{ $stat->request_count }}</td>
                    <td class="px-4 py-2 text-sm">{{ $stat->average_response_time }}</td>
                    <td class="px-4 py-2 text-sm @if($stat->error_count > 0) text-red-600 @endif">{{ $stat->error_count }}</td>
                    <td class="px-4 py-2 text-sm">{{ $stat->request_count > 0 ? round(($stat->error_count / $stat->request_count) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyStats = @json($monthlyStats);
    
    // Préparation des données par jour de semaine
    const weekdayData = Array(7).fill(0);
    const weekdayCounts = Array(7).fill(0);
    const responseTimes = monthlyStats.map(item => item.average_response_time);
    
    monthlyStats.forEach(item => {
        const date = new Date(item.date);
        const dayOfWeek = date.getDay();
        weekdayData[dayOfWeek] += item.request_count;
        weekdayCounts[dayOfWeek]++;
    });
    
    // Normaliser les données par jour de semaine
    const weekdayAvg = weekdayData.map((value, index) => 
        weekdayCounts[index] > 0 ? Math.round(value / weekdayCounts[index]) : 0
    );
    
    // Graphique mensuel
    new Chart(document.getElementById('monthlyStatsChart'), {
        type: 'line',
        data: {
            labels: monthlyStats.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit'});
            }),
            datasets: [
                {
                    label: 'Connexions',
                    data: monthlyStats.map(item => item.connection_count),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Requêtes',
                    data: monthlyStats.map(item => item.request_count),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'transparent',
                    tension: 0.3
                },
                {
                    label: 'Erreurs',
                    data: monthlyStats.map(item => item.error_count),
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
    
    // Graphique par jour de semaine
    new Chart(document.getElementById('weekdayPerformanceChart'), {
        type: 'bar',
        data: {
            labels: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            datasets: [
                {
                    label: 'Requêtes moyennes par jour',
                    data: weekdayAvg,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderColor: 'rgb(16, 185, 129)',
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
    
    // Graphique temps de réponse
    new Chart(document.getElementById('responseTimeChart'), {
        type: 'line',
        data: {
            labels: monthlyStats.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit'});
            }),
            datasets: [
                {
                    label: 'Temps de réponse moyen (ms)',
                    data: responseTimes,
                    borderColor: 'rgb(124, 58, 237)',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    tension: 0.3,
                    fill: true
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
