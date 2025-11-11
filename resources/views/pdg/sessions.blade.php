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
                {{ $isFrench ? 'Sessions de Vente' : 'Sales Sessions' }}
            </h1>
            <p class="text-amber-50 text-sm sm:text-base">
                {{ $isFrench ? 'Vue de toutes les sessions' : 'View of all sessions' }}
            </p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6">
            <form method="GET" action="{{ route('pdg.sessions') }}">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Statut' : 'Status' }}</label>
                        <select name="statut" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-amber-500">
                            <option value="">{{ $isFrench ? 'Toutes' : 'All' }}</option>
                            <option value="ouverte" {{ request('statut') == 'ouverte' ? 'selected' : '' }}>{{ $isFrench ? 'Ouvertes' : 'Open' }}</option>
                            <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>{{ $isFrench ? 'Fermées' : 'Closed' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Catégorie' : 'Category' }}</label>
                        <select name="categorie" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500">
                            <option value="">{{ $isFrench ? 'Toutes' : 'All' }}</option>
                            <option value="boulangerie" {{ request('categorie') == 'boulangerie' ? 'selected' : '' }}>{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</option>
                            <option value="patisserie" {{ request('categorie') == 'patisserie' ? 'selected' : '' }}>{{ $isFrench ? 'Pâtisserie' : 'Pastry' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date début' : 'Start Date' }}</label>
                        <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                        <i class="fas fa-filter mr-2"></i>{{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Sessions List -->
<div class="space-y-6">
    @forelse($sessions as $session)
    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
        <!-- Session Header -->
        <div class="bread-gradient p-6 bg-gradient-to-r from-amber-600 to-orange-600">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <h3 class="text-2xl font-bold text-white">
                            {{ $isFrench ? 'Session' : 'Session' }} #{{ $session->id }}
                        </h3>
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full
                            {{ $session->statut == 'ouverte' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ $session->statut == 'ouverte' ? ($isFrench ? 'Ouverte' : 'Open') : ($isFrench ? 'Fermée' : 'Closed') }}
                        </span>
                    </div>
                    <div class="text-amber-50 space-y-1">
                        <p class="flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            <strong class="mr-1">{{ $isFrench ? 'Vendeur:' : 'Seller:' }}</strong> 
                            {{ $session->vendeur->name ?? 'N/A' }}
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            <strong class="mr-1">{{ $isFrench ? 'Date:' : 'Date:' }}</strong> 
                            {{ $session->date_ouverture->format('d/m/Y H:i') }}
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-{{ $session->categorie == 'boulangerie' ? 'bread-slice' : 'birthday-cake' }} mr-2"></i>
                            <strong class="mr-1">{{ $isFrench ? 'Catégorie:' : 'Category:' }}</strong> 
                            {{ ucfirst($session->categorie) }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col items-end space-y-2">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-6 py-3">
                        <p class="text-amber-50 text-sm font-medium">{{ $isFrench ? 'Fond de vente' : 'Sales Fund' }}</p>
                        <p class="text-3xl font-bold text-white">{{ number_format($session->fond_vente, 0, ',', ' ') }} F</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Basic Info -->
        <div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Orange Money -->
                <div class="text-center p-6 bg-white rounded-xl shadow-md border-l-4 border-orange-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-center mb-3">
                        <i class="fas fa-wallet text-orange-500 text-2xl mr-2"></i>
                        <div class="text-sm font-semibold text-gray-700">{{ $isFrench ? 'Orange Money' : 'Orange Money' }}</div>
                    </div>
                    <div class="text-3xl font-bold text-orange-500">{{ number_format($session->orange_money_initial, 0, ',', ' ') }} F</div>
                </div>

                <!-- MTN Money -->
                <div class="text-center p-6 bg-white rounded-xl shadow-md border-l-4 border-yellow-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-center mb-3">
                        <i class="fas fa-mobile-alt text-yellow-500 text-2xl mr-2"></i>
                        <div class="text-sm font-semibold text-gray-700">{{ $isFrench ? 'MTN Money' : 'MTN Money' }}</div>
                    </div>
                    <div class="text-3xl font-bold text-yellow-500">{{ number_format($session->mtn_money_initial, 0, ',', ' ') }} F</div>
                </div>

                @if($session->statut == 'fermee')
                <!-- Montant Versé -->
                <div class="text-center p-6 bg-white rounded-xl shadow-md border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-center mb-3">
                        <i class="fas fa-hand-holding-usd text-green-500 text-2xl mr-2"></i>
                        <div class="text-sm font-semibold text-gray-700">{{ $isFrench ? 'Montant Versé' : 'Amount Paid' }}</div>
                    </div>
                    <div class="text-3xl font-bold text-green-500">{{ number_format($session->montant_verse ?? 0, 0, ',', ' ') }} F</div>
                </div>

                <!-- Manquant -->
                <div class="text-center p-6 bg-white rounded-xl shadow-md border-l-4 border-red-500 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-center mb-3">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-2"></i>
                        <div class="text-sm font-semibold text-gray-700">{{ $isFrench ? 'Manquant' : 'Missing' }}</div>
                    </div>
                    <div class="text-3xl font-bold text-red-500">{{ number_format($session->manquant ?? 0, 0, ',', ' ') }} F</div>
                </div>
                @else
                <!-- Fond de Vente (session ouverte) -->
                <div class="text-center p-6 bg-white rounded-xl shadow-md border-l-4 border-blue-500 hover:shadow-lg transition-shadow duration-300 col-span-1 sm:col-span-2">
                    <div class="flex items-center justify-center mb-3">
                        <i class="fas fa-money-bill-wave text-blue-500 text-2xl mr-2"></i>
                        <div class="text-sm font-semibold text-gray-700">{{ $isFrench ? 'Fond de Vente' : 'Sales Fund' }}</div>
                    </div>
                    <div class="text-3xl font-bold text-blue-500">{{ number_format($session->fond_vente, 0, ',', ' ') }} F</div>
                </div>
                @endif
            </div>

            <!-- Lien vers les détails -->
            <div class="mt-6 text-center">
                <a href="{{ route('pdg.sessions.detaillees', ['session_id' => $session->id]) }}" 
                   class="inline-flex items-center px-6 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition-colors duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-eye mr-2"></i>
                    {{ $isFrench ? 'Voir les détails complets' : 'View full details' }}
                </a>
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

        <!-- Pagination -->
        @if($sessions->hasPages())
        <div class="mt-6">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection