@extends('layouts.app')

@section('title', $isFrench ? 'Inventaires' : 'Inventories')

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
                <i class="fas fa-clipboard-list mr-3"></i>
                {{ $isFrench ? 'Suivi des Inventaires' : 'Inventory Tracking' }}
            </h1>
            <p class="text-amber-50 text-sm sm:text-base">
                {{ $isFrench ? 'Historique des changements de garde' : 'History of shift changes' }}
            </p>
        </div>
    </div>
</div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-check-circle text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $stats['total_inventaires'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-semibold">{{ $isFrench ? 'Total Inventaires' : 'Total Inventories' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-bread-slice text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $stats['boulangerie'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-semibold">{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-birthday-cake text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $stats['patisserie'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-semibold">{{ $isFrench ? 'Pâtisserie' : 'Pastry' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-calendar-day text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $stats['aujourd_hui'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-semibold">{{ $isFrench ? 'Aujourd\'hui' : 'Today' }}</h3>
            </div>
        </div>

        <!-- Inventaires List -->
        <div class="space-y-6">
            @forelse($inventaires ?? [] as $inventaire)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                <div class="bread-gradient p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/20 w-12 h-12 rounded-xl flex items-center justify-center">
                            <i class="fas fa-{{ $inventaire['categorie'] == 'boulangerie' ? 'bread-slice' : 'birthday-cake' }} text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">
                                {{ $isFrench ? 'Inventaire' : 'Inventory' }} #{{ $inventaire['id'] }}
                            </h3>
                            <p class="text-amber-100 text-sm">
                                {{ $inventaire['date'] }} • {{ $inventaire['heure'] }}
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex px-4 py-2 text-sm font-medium rounded-lg bg-white/20 text-white">
                        {{ ucfirst($inventaire['categorie']) }}
                    </span>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Vendeur Sortant -->
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="font-bold text-red-800 mb-3 flex items-center">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                {{ $isFrench ? 'Vendeur Sortant' : 'Outgoing Seller' }}
                            </h4>
                            <div class="space-y-2">
                                <p class="text-gray-700"><strong>{{ $isFrench ? 'Nom:' : 'Name:' }}</strong> {{ $inventaire['vendeur_sortant']['nom'] }}</p>
                                <p class="text-gray-700"><strong>{{ $isFrench ? 'Code PIN:' : 'PIN:' }}</strong> ●●●●●●</p>
                                <p class="text-green-600 font-semibold"><i class="fas fa-check-circle mr-1"></i>{{ $isFrench ? 'Validé' : 'Validated' }}</p>
                            </div>
                        </div>

                        <!-- Vendeur Entrant -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <h4 class="font-bold text-green-800 mb-3 flex items-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                {{ $isFrench ? 'Vendeur Entrant' : 'Incoming Seller' }}
                            </h4>
                            <div class="space-y-2">
                                <p class="text-gray-700"><strong>{{ $isFrench ? 'Nom:' : 'Name:' }}</strong> {{ $inventaire['vendeur_entrant']['nom'] }}</p>
                                <p class="text-gray-700"><strong>{{ $isFrench ? 'Code PIN:' : 'PIN:' }}</strong> ●●●●●●</p>
                                <p class="text-green-600 font-semibold"><i class="fas fa-check-circle mr-1"></i>{{ $isFrench ? 'Validé' : 'Validated' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Produits Inventoriés -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $isFrench ? 'Quantité Laissée' : 'Quantity Left' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $isFrench ? 'Prix Unitaire' : 'Unit Price' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $isFrench ? 'Valeur Total' : 'Total Value' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($inventaire['produits'] as $produit)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-900">{{ $produit['nom'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-bold">
                                            {{ $produit['quantite'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ number_format($produit['prix']) }} F</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="font-bold text-green-600">{{ number_format($produit['valeur_totale']) }} F</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-800">{{ $isFrench ? 'Valeur Totale:' : 'Total Value:' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xl font-bold text-green-600">{{ number_format($inventaire['valeur_totale']) }} F</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Print Button -->
                    <div class="mt-6 text-right">
                        <a href="{{ route('pdg.print-inventaire', $inventaire['id']) }}" target="_blank"
                           class="inline-flex items-center px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all font-semibold">
                            <i class="fas fa-print mr-2"></i>
                            {{ $isFrench ? 'Imprimer' : 'Print' }}
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucun inventaire trouvé' : 'No inventories found' }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
