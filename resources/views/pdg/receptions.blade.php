@extends('layouts.app')

@section('title', $isFrench ? 'Réceptions de Produits' : 'Product Receptions')

@section('styles')
<style>
    .bread-gradient {
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
       <div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                <i class="fas fa-truck mr-3"></i>
                {{ $isFrench ? 'Réceptions de Produits' : 'Product Receptions' }}
            </h1>
            <p class="text-amber-50 text-sm sm:text-base">
                {{ $isFrench ? 'Suivi des arrivages et réceptions' : 'Track arrivals and receptions' }}
            </p>
        </div>
        <button onclick="window.print()" class="bg-white text-amber-700 px-6 py-3 rounded-xl font-semibold hover:bg-amber-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
            <i class="fas fa-print mr-2"></i>{{ $isFrench ? 'Imprimer' : 'Print' }}
        </button>
    </div>
</div>
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6" x-data="{ dateFilter: 'today', categorie: 'all' }">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Période' : 'Period' }}</label>
                    <select x-model="dateFilter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-amber-500">
                        <option value="today">{{ $isFrench ? 'Aujourd\'hui' : 'Today' }}</option>
                        <option value="week">{{ $isFrench ? 'Cette semaine' : 'This week' }}</option>
                        <option value="month">{{ $isFrench ? 'Ce mois' : 'This month' }}</option>
                        <option value="all">{{ $isFrench ? 'Tout' : 'All' }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Catégorie' : 'Category' }}</label>
                    <select x-model="categorie" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500">
                        <option value="all">{{ $isFrench ? 'Toutes' : 'All' }}</option>
                        <option value="boulangerie">{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</option>
                        <option value="patisserie">{{ $isFrench ? 'Pâtisserie' : 'Pastry' }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-box text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $stats['total_receptions'] ?? 0 }}</span>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Total Réceptions' : 'Total Receptions' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-layer-group text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $stats['total_quantite'] ?? 0 }}</span>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Quantité Totale' : 'Total Quantity' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-users text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $stats['producteurs_actifs'] ?? 0 }}</span>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Producteurs Actifs' : 'Active Producers' }}</h3>
            </div>
        </div>

        <!-- Receptions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bread-gradient text-white">
                        <tr>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Date/Heure' : 'Date/Time' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Producteur' : 'Producer' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Pointeur' : 'Pointer' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Vendeur' : 'Seller' }}</th>
                            <th class="px-4 py-4 text-center text-sm font-semibold">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($receptions ?? [] as $reception)
                        <tr class="hover:bg-amber-50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $reception['date'] }}</div>
                                    <div class="text-gray-500">{{ $reception['heure'] }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="font-semibold text-gray-900">{{ $reception['produit'] }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                    {{ $reception['categorie'] == 'boulangerie' ? 'bg-amber-100 text-amber-800' : 'bg-pink-100 text-pink-800' }}">
                                    <i class="fas fa-{{ $reception['categorie'] == 'boulangerie' ? 'bread-slice' : 'birthday-cake' }} mr-1"></i>
                                    {{ ucfirst($reception['categorie']) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="font-bold text-lg text-blue-600">{{ $reception['quantite'] }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">{{ $reception['producteur'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user-check text-blue-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">{{ $reception['pointeur'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-store text-green-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">{{ $reception['vendeur'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('pdg.print-reception', $reception['id']) }}" target="_blank"
                                   class="inline-flex items-center px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all text-sm">
                                    <i class="fas fa-print mr-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                                <p class="text-lg">{{ $isFrench ? 'Aucune réception trouvée' : 'No receptions found' }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
