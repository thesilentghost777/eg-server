@extends('layouts.app')

@section('title', 'Flux Opérationnel')

@section('styles')
<style>
    .bread-gradient {
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
    }
    .timeline-item {
        position: relative;
        padding-left: 3rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 2rem;
        bottom: -2rem;
        width: 2px;
        background: linear-gradient(180deg, #D4A574, transparent);
    }
    .timeline-item:last-child::before {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
<div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
        <i class="fas fa-stream mr-3"></i>
        Flux Opérationnel
    </h1>
    <p class="text-amber-50 text-sm sm:text-base">
        Suivi des opérations - {{ $flux['date'] ?? $selectedDate ?? '' }}
    </p>
</div>

        
        <!-- Stats Live (Résumé) -->
        @if(isset($flux['resume']))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-users text-3xl text-green-500"></i>
                    <span class="text-3xl font-bold text-gray-800">{{ count($flux['flux'] ?? []) }}</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-600">Vendeurs Actifs</h3>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-shopping-cart text-3xl text-blue-500"></i>
                    <span class="text-3xl font-bold text-gray-800">{{ $flux['resume']['total_ventes'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-600">Total Ventes</h3>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-boxes text-3xl text-purple-500"></i>
                    <span class="text-3xl font-bold text-gray-800">{{ $flux['resume']['total_produits'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-600">Produits Vendus</h3>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-dollar-sign text-3xl text-orange-500"></i>
                    <span class="text-3xl font-bold text-gray-800">{{ number_format($flux['resume']['chiffre_affaire'] ?? 0, 0, ',', ' ') }}</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-600">Chiffre d'Affaires (FCFA)</h3>
            </div>
        </div>
        @endif

        <!-- Flux par Vendeur -->
        <div class="space-y-6">
            @forelse($flux['flux'] ?? [] as $fluxVendeur)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <!-- En-tête Vendeur -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-amber-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ $fluxVendeur['vendeur']['nom'] }}</h2>
                            <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $fluxVendeur['vendeur']['role'])) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Ventes</p>
                        <p class="text-2xl font-bold text-amber-600">{{ $fluxVendeur['total_ventes'] }}</p>
                    </div>
                </div>

                <!-- Timeline des Produits -->
                <div class="space-y-4">
                    @forelse($fluxVendeur['produits'] ?? [] as $produitFlux)
                    <div class="timeline-item">
                        <div class="absolute left-0 w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center">
                            <i class="fas fa-bread-slice text-xs text-white"></i>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-800">{{ $produitFlux['produit'] ?? 'Produit' }}</h4>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                    {{ $produitFlux['quantite_vendue'] ?? 0 }} vendus
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
                                <div class="bg-white p-3 rounded-lg">
                                    <p class="text-xs text-gray-500">Réception</p>
                                    <p class="text-lg font-bold text-blue-600">{{ $produitFlux['reception'] ?? 0 }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg">
                                    <p class="text-xs text-gray-500">Stock Initial</p>
                                    <p class="text-lg font-bold text-purple-600">{{ $produitFlux['stock_initial'] ?? 0 }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg">
                                    <p class="text-xs text-gray-500">Retours</p>
                                    <p class="text-lg font-bold text-orange-600">{{ $produitFlux['retours'] ?? 0 }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg">
                                    <p class="text-xs text-gray-500">Stock Final</p>
                                    <p class="text-lg font-bold text-gray-600">{{ $produitFlux['stock_final'] ?? 0 }}</p>
                                </div>
                            </div>

                            @if(isset($produitFlux['details']) && !empty($produitFlux['details']))
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-2">Détails des ventes :</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($produitFlux['details'] as $detail)
                                    <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                        {{ $detail['heure'] ?? '' }} - {{ $detail['quantite'] ?? 0 }} unité(s)
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">Aucun produit pour ce vendeur</p>
                    @endforelse
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">Aucune activité pour cette période</p>
                <p class="text-sm text-gray-400 mt-2">Sélectionnez une autre date ou vendeur</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection