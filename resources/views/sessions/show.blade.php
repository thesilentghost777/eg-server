@extends('layouts.app')

@section('title', ($isFrench ? 'Détails Session #' : 'Session Details #') . $session->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-4 sm:py-6 lg:py-8">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6">
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-amber-900">
                <i class="fas fa-file-invoice-dollar mr-2 sm:mr-3"></i>
                {{ $isFrench ? 'Détails Session' : 'Session Details' }} #{{ $session->id }}
            </h1>
            <div class="flex flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
                <a href="{{ route('sessions.index') }}" 
                   class="flex-1 sm:flex-none bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 sm:px-6 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center font-semibold text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ $isFrench ? 'Retour' : 'Back' }}
                </a>
                @if($session->statut == 'ouverte')
                    <a href="{{ route('sessions.fermer.form', $session->id) }}" 
                       class="flex-1 sm:flex-none bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white px-4 sm:px-6 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center font-semibold text-sm">
                        <i class="fas fa-times-circle mr-2"></i>
                        {{ $isFrench ? 'Fermer' : 'Close' }}
                    </a>
                @endif
                <a href="{{ route('sessions.edit', $session->id) }}" 
                   class="flex-1 sm:flex-none bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-4 sm:px-6 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center font-semibold text-sm">
                    <i class="fas fa-edit mr-2"></i>
                    {{ $isFrench ? 'Modifier' : 'Edit' }}
                </a>
            </div>
        </div>

        <!-- Message succès -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-md animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Informations générales -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-4 sm:p-6 mb-6 border border-amber-200">
            <h2 class="text-lg sm:text-xl font-bold text-amber-900 mb-5 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                {{ $isFrench ? 'Informations Générales' : 'General Information' }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                    <label class="block text-xs sm:text-sm font-semibold text-blue-700 mb-2">
                        <i class="fas fa-user mr-1"></i>
                        {{ $isFrench ? 'Vendeur' : 'Seller' }}
                    </label>
                    <p class="text-base sm:text-lg font-bold text-gray-900">{{ $session->vendeur->name }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                    <label class="block text-xs sm:text-sm font-semibold text-purple-700 mb-2">
                        <i class="fas fa-tag mr-1"></i>
                        {{ $isFrench ? 'Catégorie' : 'Category' }}
                    </label>
                    <p class="text-base sm:text-lg">
                        <span class="px-3 py-1.5 rounded-full text-sm font-bold {{ $session->categorie == 'boulangerie' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : 'bg-pink-100 text-pink-800 border border-pink-300' }}">
                            {{ $isFrench ? ucfirst($session->categorie) : ($session->categorie == 'boulangerie' ? 'Bakery' : 'Pastry') }}
                        </span>
                    </p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                    <label class="block text-xs sm:text-sm font-semibold text-green-700 mb-2">
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ $isFrench ? 'Date d\'ouverture' : 'Opening Date' }}
                    </label>
                    <p class="text-base sm:text-lg font-bold text-gray-900">{{ $session->date_ouverture->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 border border-amber-200">
                    <label class="block text-xs sm:text-sm font-semibold text-amber-700 mb-2">
                        <i class="fas fa-traffic-light mr-1"></i>
                        {{ $isFrench ? 'Statut' : 'Status' }}
                    </label>
                    <p class="text-base sm:text-lg">
                        @if($session->statut == 'ouverte')
                            <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-bold border border-green-300">
                                <i class="fas fa-unlock mr-1"></i>
                                {{ $isFrench ? 'Ouverte' : 'Open' }}
                            </span>
                        @else
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-full text-sm font-bold border border-gray-300">
                                <i class="fas fa-lock mr-1"></i>
                                {{ $isFrench ? 'Fermée' : 'Closed' }}
                            </span>
                        @endif
                    </p>
                </div>
                @if($session->statut == 'fermee')
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                        <label class="block text-xs sm:text-sm font-semibold text-red-700 mb-2">
                            <i class="far fa-calendar-check mr-1"></i>
                            {{ $isFrench ? 'Date de fermeture' : 'Closing Date' }}
                        </label>
                        <p class="text-base sm:text-lg font-bold text-gray-900">{{ $session->date_fermeture->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 border border-indigo-200">
                        <label class="block text-xs sm:text-sm font-semibold text-indigo-700 mb-2">
                            <i class="fas fa-user-shield mr-1"></i>
                            {{ $isFrench ? 'Fermée par' : 'Closed by' }}
                        </label>
                        <p class="text-base sm:text-lg font-bold text-gray-900">{{ $session->fermeePar->name ?? 'N/A' }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Valeur des Ventes -->
        <div class="bg-gradient-to-r from-indigo-100 via-purple-100 to-pink-100 rounded-2xl shadow-xl p-5 sm:p-6 mb-6 border-2 border-indigo-300">
            <h2 class="text-lg sm:text-xl font-bold text-indigo-900 mb-4 flex items-center">
                <i class="fas fa-chart-line mr-2 text-2xl"></i>
                {{ $isFrench ? '💰 Ventes de la Session' : '💰 Session Sales' }}
            </h2>
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                <span class="text-base sm:text-lg text-indigo-800 font-semibold">
                    {{ $isFrench ? 'Valeur Totale des Ventes:' : 'Total Sales Value:' }}
                </span>
                <span class="text-2xl sm:text-3xl lg:text-4xl font-bold text-indigo-900 bg-white px-6 py-3 rounded-xl shadow-lg border-2 border-indigo-300">
                    {{ number_format($session->valeur_vente ?? 0, 0, ',', ' ') }} FCFA
                </span>
            </div>
        </div>

        <!-- Montants -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
            <!-- Ouverture -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-xl p-5 sm:p-6 border-2 border-blue-300">
                <h2 class="text-lg sm:text-xl font-bold text-blue-900 mb-5 flex items-center">
                    <i class="fas fa-door-open mr-2 text-2xl"></i>
                    {{ $isFrench ? 'Montants d\'Ouverture' : 'Opening Amounts' }}
                </h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-white/70 rounded-lg p-3 border border-blue-200">
                        <span class="text-sm sm:text-base text-gray-700 font-semibold">
                            <i class="fas fa-wallet mr-2 text-blue-600"></i>
                            {{ $isFrench ? 'Fond de vente:' : 'Sales fund:' }}
                        </span>
                        <span class="font-bold text-base sm:text-lg text-amber-800">{{ number_format($session->fond_vente, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center bg-white/70 rounded-lg p-3 border border-orange-200">
                        <span class="text-sm sm:text-base text-gray-700 font-semibold">
                            <i class="fab fa-buffer mr-2 text-orange-600"></i>
                            Orange Money:
                        </span>
                        <span class="font-bold text-base sm:text-lg text-orange-700">{{ number_format($session->orange_money_initial, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center bg-white/70 rounded-lg p-3 border border-yellow-200">
                        <span class="text-sm sm:text-base text-gray-700 font-semibold">
                            <i class="fas fa-mobile-alt mr-2 text-yellow-600"></i>
                            MTN Money:
                        </span>
                        <span class="font-bold text-base sm:text-lg text-yellow-700">{{ number_format($session->mtn_money_initial, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="border-t-2 border-blue-300 pt-4 flex justify-between items-center bg-blue-200/50 rounded-lg p-3">
                        <span class="font-bold text-gray-800 text-sm sm:text-base">
                            {{ $isFrench ? 'Total Ouverture:' : 'Total Opening:' }}
                        </span>
                        <span class="font-bold text-xl sm:text-2xl text-blue-800">
                            {{ number_format($session->fond_vente + $session->orange_money_initial + $session->mtn_money_initial, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
            </div>

            <!-- Fermeture -->
            @if($session->statut == 'fermee')
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-xl p-5 sm:p-6 border-2 border-green-300">
                    <h2 class="text-lg sm:text-xl font-bold text-green-900 mb-5 flex items-center">
                        <i class="fas fa-door-closed mr-2 text-2xl"></i>
                        {{ $isFrench ? 'Montants de Fermeture' : 'Closing Amounts' }}
                    </h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center bg-white/70 rounded-lg p-3 border border-green-200">
                            <span class="text-sm sm:text-base text-gray-700 font-semibold">
                                <i class="fas fa-coins mr-2 text-green-600"></i>
                                {{ $isFrench ? 'Montant versé:' : 'Amount paid:' }}
                            </span>
                            <span class="font-bold text-base sm:text-lg text-green-800">{{ number_format($session->montant_verse, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between items-center bg-white/70 rounded-lg p-3 border border-orange-200">
                            <span class="text-sm sm:text-base text-gray-700 font-semibold">
                                <i class="fab fa-buffer mr-2 text-orange-600"></i>
                                Orange Money:
                            </span>
                            <span class="font-bold text-base sm:text-lg text-orange-700">{{ number_format($session->orange_money_final, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between items-center bg-white/70 rounded-lg p-3 border border-yellow-200">
                            <span class="text-sm sm:text-base text-gray-700 font-semibold">
                                <i class="fas fa-mobile-alt mr-2 text-yellow-600"></i>
                                MTN Money:
                            </span>
                            <span class="font-bold text-base sm:text-lg text-yellow-700">{{ number_format($session->mtn_money_final, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="border-t-2 border-green-300 pt-4 flex justify-between items-center bg-green-200/50 rounded-lg p-3">
                            <span class="font-bold text-gray-800 text-sm sm:text-base">
                                {{ $isFrench ? 'Total Versé:' : 'Total Paid:' }}
                            </span>
                            <span class="font-bold text-xl sm:text-2xl text-green-800">
                                {{ number_format($session->montant_verse + $session->orange_money_final + $session->mtn_money_final, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Résultat Final -->
        @if($session->statut == 'fermee')
            @php
                $diffOM = $session->orange_money_final - $session->orange_money_initial;
                $diffMOMO = $session->mtn_money_final - $session->mtn_money_initial;
                $ventesTotales = $session->valeur_vente ?? 0;
            @endphp

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-5 sm:p-6 mb-6 border border-amber-200">
                <h2 class="text-lg sm:text-xl font-bold text-amber-900 mb-5 flex items-center">
                    <i class="fas fa-calculator mr-2 text-2xl"></i>
                    {{ $isFrench ? '📊 Calcul du Résultat' : '📊 Result Calculation' }}
                </h2>
                
                <!-- Formule -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-gray-300 rounded-xl p-4 sm:p-5 mb-6 shadow-inner">
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-square-root-alt mr-2"></i>
                        {{ $isFrench ? '📐 Formule:' : '📐 Formula:' }}
                    </h3>
                    <div class="font-mono text-xs sm:text-sm bg-white p-4 rounded-lg border-2 border-gray-300 shadow-sm overflow-x-auto">
                        <p class="text-gray-900 mb-3 font-semibold whitespace-nowrap">
                            <strong>{{ $isFrench ? 'Manquant' : 'Missing' }}</strong> = ({{ $isFrench ? 'Ventes' : 'Sales' }} + {{ $isFrench ? 'Fond' : 'Fund' }}) - ({{ $isFrench ? 'Versé' : 'Paid' }} + Diff. OM + Diff. MTN)
                        </p>
                        <p class="text-blue-700 font-bold whitespace-nowrap">
                            <strong>{{ $isFrench ? 'Manquant' : 'Missing' }}</strong> = ({{ number_format($ventesTotales, 0, ',', ' ') }} + {{ number_format($session->fond_vente, 0, ',', ' ') }}) - ({{ number_format($session->montant_verse, 0, ',', ' ') }} + {{ number_format($diffOM, 0, ',', ' ') }} + {{ number_format($diffMOMO, 0, ',', ' ') }})
                        </p>
                    </div>
                </div>

                <!-- Résultats -->
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-base sm:text-lg bg-orange-50 rounded-lg p-4 border border-orange-200">
                        <span class="text-gray-800 font-semibold">
                            <i class="fab fa-buffer mr-2 text-orange-600"></i>
                            {{ $isFrench ? 'Différence Orange Money:' : 'Orange Money Difference:' }}
                        </span>
                        <span class="font-bold text-lg {{ $diffOM >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $diffOM >= 0 ? '+' : '' }}{{ number_format($diffOM, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-base sm:text-lg bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                        <span class="text-gray-800 font-semibold">
                            <i class="fas fa-mobile-alt mr-2 text-yellow-600"></i>
                            {{ $isFrench ? 'Différence MTN Money:' : 'MTN Money Difference:' }}
                        </span>
                        <span class="font-bold text-lg {{ $diffMOMO >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $diffMOMO >= 0 ? '+' : '' }}{{ number_format($diffMOMO, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    <div class="border-t-4 border-amber-300 pt-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gradient-to-r from-amber-100 to-orange-100 rounded-xl p-5 shadow-lg">
                        <span class="text-xl sm:text-2xl font-bold text-gray-900">
                            <i class="fas fa-balance-scale mr-2"></i>
                            {{ $isFrench ? 'Manquant / Excédent:' : 'Missing / Surplus:' }}
                        </span>
                        <span class="text-2xl sm:text-3xl lg:text-4xl font-bold {{ $session->manquant > 0 ? 'text-red-600' : ($session->manquant < 0 ? 'text-green-600' : 'text-gray-700') }}">
                            {{ $session->manquant > 0 ? '-' : ($session->manquant < 0 ? '+' : '') }}{{ number_format(abs($session->manquant), 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    
                    <!-- Badge de statut -->
                    @if($session->manquant > 0)
                        <div class="bg-red-50 border-2 border-red-300 rounded-xl p-4 shadow-md">
                            <p class="text-red-900 font-bold text-center flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle mr-2 text-2xl"></i>
                                {{ $isFrench ? '⚠️ Manquant détecté dans la caisse' : '⚠️ Missing amount detected in cash register' }}
                            </p>
                        </div>
                    @elseif($session->manquant < 0)
                        <div class="bg-green-50 border-2 border-green-300 rounded-xl p-4 shadow-md">
                            <p class="text-green-900 font-bold text-center flex items-center justify-center">
                                <i class="fas fa-check-circle mr-2 text-2xl"></i>
                                {{ $isFrench ? '✓ Excédent dans la caisse' : '✓ Surplus in cash register' }}
                            </p>
                        </div>
                    @else
                        <div class="bg-blue-50 border-2 border-blue-300 rounded-xl p-4 shadow-md">
                            <p class="text-blue-900 font-bold text-center flex items-center justify-center">
                                <i class="fas fa-check-double mr-2 text-2xl"></i>
                                {{ $isFrench ? '✓ Caisse parfaitement équilibrée' : '✓ Cash register perfectly balanced' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endsection
