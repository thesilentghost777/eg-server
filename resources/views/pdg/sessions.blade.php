@extends('layouts.app')

@section('title', $isFrench ? 'Sessions de Vente' : 'Sales Sessions')

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
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
        <i class="fas fa-calendar-alt mr-3"></i>
        {{ $isFrench ? 'Sessions de Vente Détaillées' : 'Detailed Sales Sessions' }}
    </h1>
    <p class="text-amber-50 text-sm sm:text-base">
        {{ $isFrench ? 'Vue complète de toutes les sessions' : 'Complete view of all sessions' }}
    </p>
</div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6" x-data="{ statut: 'all', categorie: 'all' }">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Statut' : 'Status' }}</label>
                    <select x-model="statut" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-amber-500">
                        <option value="all">{{ $isFrench ? 'Toutes' : 'All' }}</option>
                        <option value="ouverte">{{ $isFrench ? 'Ouvertes' : 'Open' }}</option>
                        <option value="fermee">{{ $isFrench ? 'Fermées' : 'Closed' }}</option>
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date' : 'Date' }}</label>
                    <input type="date" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500">
                </div>
            </div>
        </div>

        <!-- Sessions List -->
        <div class="space-y-6">
            @forelse($sessions ?? [] as $session)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                <!-- Session Header -->
                <div class="bread-gradient p-6">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                        <div>
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-2xl font-bold text-white">
                                    {{ $isFrench ? 'Session' : 'Session' }} #{{ $session['id'] }}
                                </h3>
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                                    {{ $session['statut'] == 'ouverte' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                    {{ $session['statut'] == 'ouverte' ? ($isFrench ? 'Ouverte' : 'Open') : ($isFrench ? 'Fermée' : 'Closed') }}
                                </span>
                            </div>
                            <div class="text-amber-100 space-y-1">
                                <p><i class="fas fa-user mr-2"></i><strong>{{ $isFrench ? 'Vendeur:' : 'Seller:' }}</strong> {{ $session['vendeur'] }}</p>
                                <p><i class="fas fa-calendar mr-2"></i><strong>{{ $isFrench ? 'Date:' : 'Date:' }}</strong> {{ $session['date'] }}</p>
                                <p><i class="fas fa-{{ $session['categorie'] == 'boulangerie' ? 'bread-slice' : 'birthday-cake' }} mr-2"></i>
                                   <strong>{{ $isFrench ? 'Catégorie:' : 'Category:' }}</strong> {{ ucfirst($session['categorie']) }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <div class="bg-white/20 rounded-lg px-6 py-3">
                                <p class="text-amber-100 text-sm">{{ $isFrench ? 'Chiffre d\'affaires' : 'Revenue' }}</p>
                                <p class="text-3xl font-bold text-white">{{ number_format($session['chiffre_affaires']) }} F</p>
                            </div>
                            <a href="{{ route('pdg.print-session', $session['id']) }}" target="_blank"
                               class="bg-white text-amber-700 px-6 py-2 rounded-lg font-semibold hover:bg-amber-50 transition-all">
                                <i class="fas fa-print mr-2"></i>{{ $isFrench ? 'Imprimer' : 'Print' }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Session Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-6 bg-gray-50">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $session['total_produits_recus'] }}</div>
                        <div class="text-sm text-gray-600">{{ $isFrench ? 'Produits Reçus' : 'Products Received' }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $session['total_ventes'] }}</div>
                        <div class="text-sm text-gray-600">{{ $isFrench ? 'Ventes' : 'Sales' }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600">{{ $session['total_retours'] }}</div>
                        <div class="text-sm text-gray-600">{{ $isFrench ? 'Retours' : 'Returns' }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $session['stock_restant'] }}</div>
                        <div class="text-sm text-gray-600">{{ $isFrench ? 'Stock Restant' : 'Remaining Stock' }}</div>
                    </div>
                </div>

                <!-- Products Details -->
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-list mr-2 text-amber-600"></i>
                        {{ $isFrench ? 'Détails des Produits' : 'Products Details' }}
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold">{{ $isFrench ? 'Reçu' : 'Received' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold">{{ $isFrench ? 'Vendu' : 'Sold' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold">{{ $isFrench ? 'Retourné' : 'Returned' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold">{{ $isFrench ? 'Restant' : 'Remaining' }}</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold">{{ $isFrench ? 'CA' : 'Revenue' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($session['produits'] as $produit)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $produit['nom'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">
                                            {{ $produit['recu'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-semibold">
                                            {{ $produit['vendu'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex px-2 py-1 bg-orange-100 text-orange-800 rounded text-sm font-semibold">
                                            {{ $produit['retourne'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex px-2 py-1 bg-purple-100 text-purple-800 rounded text-sm font-semibold">
                                            {{ $produit['restant'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-green-600">
                                        {{ number_format($produit['ca']) }} F
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucune session trouvée' : 'No sessions found' }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
