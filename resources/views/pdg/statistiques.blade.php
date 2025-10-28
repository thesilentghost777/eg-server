@extends('layouts.app')

@section('title', $isFrench ? 'Statistiques' : 'Statistics')

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
        <i class="fas fa-chart-pie mr-3"></i>
        {{ $isFrench ? 'Statistiques et Analyses' : 'Statistics and Analytics' }}
    </h1>
    <p class="text-amber-50 text-sm sm:text-base">
        {{ $isFrench ? 'Analyses approfondies des performances' : 'In-depth performance analysis' }}
    </p>
</div>

        <!-- Period Selector -->
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-8" x-data="{ periode: 'month' }">
            <div class="flex flex-wrap gap-3">
                <button @click="periode = 'today'" :class="periode === 'today' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-6 py-2 rounded-lg font-medium transition-all">
                    {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                </button>
                <button @click="periode = 'week'" :class="periode === 'week' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-6 py-2 rounded-lg font-medium transition-all">
                    {{ $isFrench ? 'Cette Semaine' : 'This Week' }}
                </button>
                <button @click="periode = 'month'" :class="periode === 'month' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-6 py-2 rounded-lg font-medium transition-all">
                    {{ $isFrench ? 'Ce Mois' : 'This Month' }}
                </button>
                <button @click="periode = 'year'" :class="periode === 'year' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-6 py-2 rounded-lg font-medium transition-all">
                    {{ $isFrench ? 'Cette Année' : 'This Year' }}
                </button>
            </div>
        </div>

        <!-- KPIs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-coins text-4xl opacity-80"></i>
                    <div class="text-right">
                        <p class="text-3xl font-bold">{{ number_format($kpis['ca_total'] ?? 0) }} F</p>
                        <p class="text-green-100 text-sm">+{{ $kpis['evolution_ca'] ?? 0 }}%</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'CA Total' : 'Total Revenue' }}</h3>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-chart-line text-4xl opacity-80"></i>
                    <div class="text-right">
                        <p class="text-3xl font-bold">{{ number_format($kpis['total_ventes'] ?? 0) }}</p>
                        <p class="text-blue-100 text-sm">+{{ $kpis['evolution_ventes'] ?? 0 }}%</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Total Ventes' : 'Total Sales' }}</h3>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-percentage text-4xl opacity-80"></i>
                    <div class="text-right">
                        <p class="text-3xl font-bold">{{ $kpis['taux_retour'] ?? 0 }}%</p>
                        <p class="text-purple-100 text-sm">{{ $kpis['retours_total'] ?? 0 }} {{ $isFrench ? 'retours' : 'returns' }}</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Taux de Retour' : 'Return Rate' }}</h3>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-shopping-bag text-4xl opacity-80"></i>
                    <div class="text-right">
                        <p class="text-3xl font-bold">{{ number_format($kpis['panier_moyen'] ?? 0) }} F</p>
                        <p class="text-orange-100 text-sm">{{ $isFrench ? 'par transaction' : 'per transaction' }}</p>
                    </div>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Panier Moyen' : 'Average Basket' }}</h3>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- CA par Catégorie -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-chart-pie mr-2 text-amber-600"></i>
                    {{ $isFrench ? 'CA par Catégorie' : 'Revenue by Category' }}
                </h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium text-gray-700"><i class="fas fa-bread-slice mr-2 text-amber-600"></i>{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</span>
                            <span class="font-bold text-amber-700">{{ number_format($stats['ca_boulangerie'] ?? 0) }} F ({{ $stats['pct_boulangerie'] ?? 0 }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-4 rounded-full" style="width: {{ $stats['pct_boulangerie'] ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium text-gray-700"><i class="fas fa-birthday-cake mr-2 text-pink-600"></i>{{ $isFrench ? 'Pâtisserie' : 'Pastry' }}</span>
                            <span class="font-bold text-pink-700">{{ number_format($stats['ca_patisserie'] ?? 0) }} F ({{ $stats['pct_patisserie'] ?? 0 }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-gradient-to-r from-pink-500 to-pink-600 h-4 rounded-full" style="width: {{ $stats['pct_patisserie'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Produits -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-star mr-2 text-amber-600"></i>
                    {{ $isFrench ? 'Top 5 Produits' : 'Top 5 Products' }}
                </h3>
                <div class="space-y-3">
                    @forelse($topProduits ?? [] as $index => $produit)
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ ['bg-amber-500', 'bg-gray-400', 'bg-orange-600', 'bg-blue-500', 'bg-green-500'][$index] ?? 'bg-gray-300' }} text-white font-bold">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $produit['nom'] }}</p>
                            <p class="text-sm text-gray-500">{{ $produit['quantite'] }} {{ $isFrench ? 'vendus' : 'sold' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">{{ number_format($produit['ca']) }} F</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">{{ $isFrench ? 'Aucune donnée' : 'No data' }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Performance Vendeurs -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-trophy mr-2 text-amber-600"></i>
                {{ $isFrench ? 'Performance des Vendeurs' : 'Sellers Performance' }}
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ $isFrench ? 'Rang' : 'Rank' }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ $isFrench ? 'Vendeur' : 'Seller' }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">{{ $isFrench ? 'Ventes' : 'Sales' }}</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">{{ $isFrench ? 'CA' : 'Revenue' }}</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">{{ $isFrench ? 'Taux Retour' : 'Return Rate' }}</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">{{ $isFrench ? 'Performance' : 'Performance' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($performanceVendeurs ?? [] as $index => $vendeur)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $index < 3 ? 'bg-gradient-to-br from-amber-400 to-amber-600 text-white' : 'bg-gray-200 text-gray-600' }} font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-user-circle text-2xl text-gray-400"></i>
                                    <span class="font-semibold text-gray-800">{{ $vendeur['nom'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                    {{ $vendeur['categorie'] == 'boulangerie' ? 'bg-amber-100 text-amber-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ ucfirst($vendeur['categorie']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-bold text-blue-600">{{ $vendeur['total_ventes'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="font-bold text-green-600">{{ number_format($vendeur['ca']) }} F</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $vendeur['taux_retour'] < 5 ? 'bg-green-100 text-green-800' : ($vendeur['taux_retour'] < 10 ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $vendeur['taux_retour'] }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $vendeur['note'] ? 'text-amber-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                {{ $isFrench ? 'Aucune donnée disponible' : 'No data available' }}
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
