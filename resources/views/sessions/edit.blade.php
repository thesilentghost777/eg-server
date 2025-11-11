@extends('layouts.app')

@section('title', ($isFrench ? 'Modifier Session #' : 'Edit Session #') . $session->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-4 sm:py-6 lg:py-8">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6 max-w-6xl">
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-amber-900">
                <i class="fas fa-edit mr-2 sm:mr-3"></i>
                {{ $isFrench ? 'Modifier Session' : 'Edit Session' }} #{{ $session->id }}
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

        <!-- Info session -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl shadow-xl p-4 sm:p-5 mb-6 border border-blue-300">
            <div class="flex flex-wrap gap-3 sm:gap-4 justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user text-blue-700"></i>
                    <span class="text-sm sm:text-base">
                        <strong class="text-blue-900">{{ $isFrench ? 'Vendeur:' : 'Seller:' }}</strong>
                        <span class="text-gray-900 ml-2">{{ $session->vendeur->name }}</span>
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-tag text-blue-700"></i>
                    <span class="text-sm sm:text-base">
                        <strong class="text-blue-900">{{ $isFrench ? 'Catégorie:' : 'Category:' }}</strong>
                        <span class="text-gray-900 ml-2">
                            {{ $isFrench ? ucfirst($session->categorie) : ($session->categorie == 'boulangerie' ? 'Bakery' : 'Pastry') }}
                        </span>
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm sm:text-base">
                        <strong class="text-blue-900">{{ $isFrench ? 'Statut:' : 'Status:' }}</strong>
                        <span class="px-3 py-1 rounded-full text-xs font-bold ml-2 {{ $session->statut == 'ouverte' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-gray-100 text-gray-800 border border-gray-300' }}">
                            {{ $isFrench ? ucfirst($session->statut) : ($session->statut == 'ouverte' ? 'Open' : 'Closed') }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('sessions.update', $session->id) }}" method="POST" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 border border-amber-200">
            @csrf
            @method('PUT')
            
            <h2 class="text-lg sm:text-xl font-bold text-amber-900 mb-6 flex items-center">
                <i class="fas fa-money-check-alt mr-2"></i>
                {{ $isFrench ? 'Modifier les Montants' : 'Edit Amounts' }}
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Montants d'ouverture -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 sm:p-6 border-2 border-blue-200">
                    <h3 class="text-base sm:text-lg font-bold text-blue-900 mb-5 flex items-center">
                        <i class="fas fa-door-open mr-2"></i>
                        {{ $isFrench ? 'Ouverture' : 'Opening' }}
                    </h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="fond_vente" class="block text-sm font-bold text-gray-800 mb-2">
                                <i class="fas fa-wallet mr-2 text-blue-600"></i>
                                {{ $isFrench ? 'Fond de Vente' : 'Sales Fund' }}
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    id="fond_vente" 
                                    name="fond_vente" 
                                    value="{{ old('fond_vente', $session->fond_vente) }}"
                                    class="w-full border-2 border-blue-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-200 bg-white px-4 py-3 pr-20 font-semibold shadow-sm"
                                    required
                                >
                                <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">FCFA</span>
                            </div>
                            @error('fond_vente')
                                <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="orange_money_initial" class="block text-sm font-bold text-gray-800 mb-2">
                                <i class="fab fa-buffer mr-2 text-orange-600"></i>
                                Orange Money {{ $isFrench ? 'Initial' : 'Initial' }}
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    id="orange_money_initial" 
                                    name="orange_money_initial" 
                                    value="{{ old('orange_money_initial', $session->orange_money_initial) }}"
                                    class="w-full border-2 border-orange-300 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-200 bg-white px-4 py-3 pr-20 font-semibold shadow-sm"
                                    required
                                >
                                <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">FCFA</span>
                            </div>
                            @error('orange_money_initial')
                                <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mtn_money_initial" class="block text-sm font-bold text-gray-800 mb-2">
                                <i class="fas fa-mobile-alt mr-2 text-yellow-600"></i>
                                MTN Money {{ $isFrench ? 'Initial' : 'Initial' }}
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    id="mtn_money_initial" 
                                    name="mtn_money_initial" 
                                    value="{{ old('mtn_money_initial', $session->mtn_money_initial) }}"
                                    class="w-full border-2 border-yellow-300 rounded-lg focus:border-yellow-500 focus:ring-4 focus:ring-yellow-200 bg-white px-4 py-3 pr-20 font-semibold shadow-sm"
                                    required
                                >
                                <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">FCFA</span>
                            </div>
                            @error('mtn_money_initial')
                                <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Montants de fermeture -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 sm:p-6 border-2 border-green-200">
                    <h3 class="text-base sm:text-lg font-bold text-green-900 mb-5 flex items-center">
                        <i class="fas fa-door-closed mr-2"></i>
                        {{ $isFrench ? 'Fermeture' : 'Closing' }}
                    </h3>
                    
                    @if($session->statut == 'fermee')
                        <div class="space-y-5">
                            <div>
                                <label for="montant_verse" class="block text-sm font-bold text-gray-800 mb-2">
                                    <i class="fas fa-coins mr-2 text-green-600"></i>
                                    {{ $isFrench ? 'Montant Versé' : 'Amount Paid' }}
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        id="montant_verse" 
                                        name="montant_verse" 
                                        value="{{ old('montant_verse', $session->montant_verse) }}"
                                        class="w-full border-2 border-green-300 rounded-lg focus:border-green-500 focus:ring-4 focus:ring-green-200 bg-white px-4 py-3 pr-20 font-semibold shadow-sm"
                                    >
                                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">FCFA</span>
                                </div>
                                @error('montant_verse')
                                    <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="orange_money_final" class="block text-sm font-bold text-gray-800 mb-2">
                                    <i class="fab fa-buffer mr-2 text-orange-600"></i>
                                    Orange Money {{ $isFrench ? 'Final' : 'Final' }}
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        id="orange_money_final" 
                                        name="orange_money_final" 
                                        value="{{ old('orange_money_final', $session->orange_money_final) }}"
                                        class="w-full border-2 border-orange-300 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-200 bg-white px-4 py-3 pr-20 font-semibold shadow-sm"
                                    >
                                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">FCFA</span>
                                </div>
                                @error('orange_money_final')
                                    <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="mtn_money_final" class="block text-sm font-bold text-gray-800 mb-2">
                                    <i class="fas fa-mobile-alt mr-2 text-yellow-600"></i>
                                    MTN Money {{ $isFrench ? 'Final' : 'Final' }}
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        id="mtn_money_final" 
                                        name="mtn_money_final" 
                                        value="{{ old('mtn_money_final', $session->mtn_money_final) }}"
                                        class="w-full border-2 border-yellow-300 rounded-lg focus:border-yellow-500 focus:ring-4 focus:ring-yellow-200 bg-white px-4 py-3 pr-20 font-semibold shadow-sm"
                                    >
                                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">FCFA</span>
                                </div>
                                @error('mtn_money_final')
                                    <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-5 flex items-start">
                            <i class="fas fa-info-circle text-yellow-600 text-2xl mr-3 mt-1"></i>
                            <p class="text-yellow-900 font-medium">
                                {{ $isFrench ? 'Les montants de fermeture ne peuvent être modifiés que pour les sessions fermées.' : 'Closing amounts can only be modified for closed sessions.' }}
                            </p>
                        </div>
                    @endif
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
                        class="w-full sm:w-auto bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 sm:px-8 py-3 rounded-xl transition-all duration-300 font-bold shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    {{ $isFrench ? 'Enregistrer' : 'Save Changes' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
