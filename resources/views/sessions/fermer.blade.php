@extends('layouts.app')

@section('title', ($isFrench ? 'Fermer Session #' : 'Close Session #') . $session->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-4 sm:py-6 lg:py-8">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6 max-w-4xl">
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-amber-900">
                <i class="fas fa-times-circle mr-2 sm:mr-3"></i>
                {{ $isFrench ? 'Fermer Session' : 'Close Session' }} #{{ $session->id }}
            </h1>
            <a href="{{ route('sessions.show', $session->id) }}" 
               class="w-full sm:w-auto bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center font-semibold text-sm sm:text-base">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ $isFrench ? 'Annuler' : 'Cancel' }}
            </a>
        </div>

        <!-- Messages d'erreur -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-6 shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Informations session -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl shadow-xl p-4 sm:p-6 mb-6 border border-blue-300">
            <h2 class="text-lg sm:text-xl font-bold text-blue-900 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                {{ $isFrench ? 'Informations de la Session' : 'Session Information' }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div class="bg-white/60 rounded-lg p-3 border border-blue-200">
                    <span class="text-xs sm:text-sm text-blue-700 font-semibold">
                        {{ $isFrench ? 'Vendeur:' : 'Seller:' }}
                    </span>
                    <p class="font-bold text-gray-900 text-sm sm:text-base mt-1">{{ $session->vendeur->name }}</p>
                </div>
                <div class="bg-white/60 rounded-lg p-3 border border-blue-200">
                    <span class="text-xs sm:text-sm text-blue-700 font-semibold">
                        {{ $isFrench ? 'Catégorie:' : 'Category:' }}
                    </span>
                    <p class="font-bold text-gray-900 text-sm sm:text-base mt-1">
                        {{ $isFrench ? ucfirst($session->categorie) : ($session->categorie == 'boulangerie' ? 'Bakery' : 'Pastry') }}
                    </p>
                </div>
                <div class="bg-white/60 rounded-lg p-3 border border-blue-200">
                    <span class="text-xs sm:text-sm text-blue-700 font-semibold">
                        {{ $isFrench ? 'Fond de vente:' : 'Sales fund:' }}
                    </span>
                    <p class="font-bold text-amber-800 text-sm sm:text-base mt-1">{{ number_format($session->fond_vente, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-white/60 rounded-lg p-3 border border-blue-200">
                    <span class="text-xs sm:text-sm text-blue-700 font-semibold">Orange Money {{ $isFrench ? 'initial:' : 'initial:' }}</span>
                    <p class="font-bold text-orange-700 text-sm sm:text-base mt-1">{{ number_format($session->orange_money_initial, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-white/60 rounded-lg p-3 border border-blue-200 sm:col-span-2">
                    <span class="text-xs sm:text-sm text-blue-700 font-semibold">MTN Money {{ $isFrench ? 'initial:' : 'initial:' }}</span>
                    <p class="font-bold text-yellow-700 text-sm sm:text-base mt-1">{{ number_format($session->mtn_money_initial, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>
        </div>

        <!-- Formulaire de fermeture -->
        <form action="{{ route('sessions.fermer', $session->id) }}" method="POST" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 border border-amber-200">
            @csrf
            
            <h2 class="text-lg sm:text-xl font-bold text-amber-900 mb-6 flex items-center">
                <i class="fas fa-money-bill-wave mr-2"></i>
                {{ $isFrench ? 'Montants de Fermeture' : 'Closing Amounts' }}
            </h2>
            
            <div class="space-y-5 sm:space-y-6">
                <!-- Montant Versé -->
                <div>
                    <label for="montant_verse" class="block text-sm font-bold text-gray-800 mb-2">
                        <i class="fas fa-coins mr-2 text-amber-600"></i>
                        {{ $isFrench ? 'Montant Versé (Espèces)' : 'Amount Paid (Cash)' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            step="0.01" 
                            id="montant_verse" 
                            name="montant_verse" 
                            value="{{ old('montant_verse') }}"
                            class="w-full border-2 border-amber-300 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-200 bg-white text-gray-900 px-4 py-3 pr-20 text-base font-semibold shadow-sm"
                            required
                            placeholder="0"
                        >
                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">FCFA</span>
                    </div>
                    @error('montant_verse')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Orange Money Final -->
                <div>
                    <label for="orange_money_final" class="block text-sm font-bold text-gray-800 mb-2">
                        <i class="fab fa-buffer mr-2 text-orange-600"></i>
                        Orange Money {{ $isFrench ? 'Final' : 'Final' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            step="0.01" 
                            id="orange_money_final" 
                            name="orange_money_final" 
                            value="{{ old('orange_money_final', $session->orange_money_initial) }}"
                            class="w-full border-2 border-orange-300 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-200 bg-white text-gray-900 px-4 py-3 pr-20 text-base font-semibold shadow-sm"
                            required
                            placeholder="0"
                        >
                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">FCFA</span>
                    </div>
                    @error('orange_money_final')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- MTN Money Final -->
                <div>
                    <label for="mtn_money_final" class="block text-sm font-bold text-gray-800 mb-2">
                        <i class="fas fa-mobile-alt mr-2 text-yellow-600"></i>
                        MTN Money {{ $isFrench ? 'Final' : 'Final' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            step="0.01" 
                            id="mtn_money_final" 
                            name="mtn_money_final" 
                            value="{{ old('mtn_money_final', $session->mtn_money_initial) }}"
                            class="w-full border-2 border-yellow-300 rounded-xl focus:border-yellow-500 focus:ring-4 focus:ring-yellow-200 bg-white text-gray-900 px-4 py-3 pr-20 text-base font-semibold shadow-sm"
                            required
                            placeholder="0"
                        >
                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">FCFA</span>
                    </div>
                    @error('mtn_money_final')
                        <p class="text-red-600 text-sm mt-1 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Boutons -->
            <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3 sm:gap-4">
                <a href="{{ route('sessions.show', $session->id) }}" 
                   class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white px-6 sm:px-8 py-3 rounded-xl transition-all duration-300 font-semibold text-center shadow-lg hover:shadow-xl">
                    <i class="fas fa-times mr-2"></i>
                    {{ $isFrench ? 'Annuler' : 'Cancel' }}
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 sm:px-8 py-3 rounded-xl transition-all duration-300 font-bold shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-lock mr-2"></i>
                    {{ $isFrench ? 'Fermer la Session' : 'Close Session' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
