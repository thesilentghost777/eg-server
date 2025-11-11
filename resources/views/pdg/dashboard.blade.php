@extends('layouts.app')

@section('title', $isFrench ? 'Tableau de Bord PDG' : 'CEO Dashboard')

@section('styles')
<style>
    .bread-gradient {
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
    }
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
        <div class="bg-gradient-to-br from-amber-600 to-amber-700 rounded-2xl shadow-xl p-6 sm:p-8 mb-6 sm:mb-8 text-center sm:text-left">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3 flex items-center justify-center sm:justify-start">
        <i class="fas fa-crown mr-3 text-yellow-300"></i>
        {{ $isFrench ? 'Tableau de Bord PDG' : 'CEO Dashboard' }}
    </h1>
    <p class="text-amber-100 text-sm sm:text-base opacity-90">
        {{ $isFrench ? "Vue d'ensemble complète de votre entreprise" : 'Complete overview of your business' }}
    </p>
</div>


        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
            <a href="{{ route('pdg.receptions') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 w-14 h-14 rounded-xl flex items-center justify-center">
                        <i class="fas fa-truck text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">{{ $isFrench ? 'Réceptions' : 'Receptions' }}</h3>
                        <p class="text-gray-500 text-sm">{{ $isFrench ? 'Gérer les arrivages' : 'Manage arrivals' }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('pdg.inventaires') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 w-14 h-14 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">{{ $isFrench ? 'Inventaires' : 'Inventories' }}</h3>
                        <p class="text-gray-500 text-sm">{{ $isFrench ? 'Suivre les stocks' : 'Track stocks' }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('sessions.index') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <div class="bg-purple-100 w-14 h-14 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">{{ $isFrench ? 'Sessions' : 'Sessions' }}</h3>
                        <p class="text-gray-500 text-sm">{{ $isFrench ? 'Détails des ventes' : 'Sales details' }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('pdg.flux.form') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <div class="bg-orange-100 w-14 h-14 rounded-xl flex items-center justify-center">
                        <i class="fas fa-stream text-2xl text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">{{ $isFrench ? 'Flux Opérationnel' : 'Operational Flow' }}</h3>
                        <p class="text-gray-500 text-sm">{{ $isFrench ? 'Suivi temps réel' : 'Real-time tracking' }}</p>
                    </div>
                </div>
            </a>

           
            <a href="{{ route('users.index') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <div class="bg-amber-100 w-14 h-14 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users-cog text-2xl text-amber-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">{{ $isFrench ? 'Utilisateurs' : 'Users' }}</h3>
                        <p class="text-gray-500 text-sm">{{ $isFrench ? 'Gérer l\'équipe' : 'Manage team' }}</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-history mr-2 text-amber-600"></i>
                {{ $isFrench ? 'Activités Récentes' : 'Recent Activities' }}
            </h2>
            <div class="space-y-3">
                @forelse($recentActivities ?? [] as $activity)
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-10 h-10 rounded-full {{ $activity['color'] }} flex items-center justify-center text-white">
                        <i class="fas {{ $activity['icon'] }}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $activity['time'] }}</span>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">
                    {{ $isFrench ? 'Aucune activité récente' : 'No recent activity' }}
                </p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
