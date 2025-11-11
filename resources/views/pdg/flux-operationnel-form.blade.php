@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                {{ $isFrench ? 'Flux Opérationnel - Sélection des Critères' : 'Operational Flow - Criteria Selection' }}
            </h1>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form method="GET" action="{{ route('pdg.flux') }}" class="space-y-6">
                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Date' : 'Date' }} <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="date" 
                        name="date" 
                        value="{{ request('date', date('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date') border-red-500 @enderror"
                        required
                    >
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vendeur (optionnel) -->
                <div>
                    <label for="vendeur_id" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Vendeur (optionnel)' : 'Seller (optional)' }}
                    </label>
                    <select 
                        id="vendeur_id" 
                        name="vendeur_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vendeur_id') border-red-500 @enderror"
                    >
                        <option value="">{{ $isFrench ? '-- Tous les vendeurs --' : '-- All sellers --' }}</option>
                        @foreach($vendeurs as $vendeur)
                            <option value="{{ $vendeur->id }}" {{ request('vendeur_id') == $vendeur->id ? 'selected' : '' }}>
                                {{ $vendeur->nom }} 
                                <span class="text-gray-500">
                                    ({{ $vendeur->role == 'Serveur' ? ($isFrench ? 'Serveur' : 'Server') : $vendeur->role }})
                                </span>
                            </option>
                        @endforeach
                    </select>
                    @error('vendeur_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Produit (optionnel) -->
                <div>
                    <label for="produit_id" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $isFrench ? 'Produit (optionnel)' : 'Product (optional)' }}
                    </label>
                    <select 
                        id="produit_id" 
                        name="produit_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('produit_id') border-red-500 @enderror"
                    >
                        <option value="">{{ $isFrench ? '-- Tous les produits --' : '-- All products --' }}</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->id }}" {{ request('produit_id') == $produit->id ? 'selected' : '' }}>
                                {{ $produit->nom }} 
                                <span class="text-gray-500">
                                    ({{ number_format($produit->prix, 2) }} €)
                                </span>
                            </option>
                        @endforeach
                    </select>
                    @error('produit_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons -->
                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-md"
                    >
                        {{ $isFrench ? 'Générer le Flux Opérationnel' : 'Generate Operational Flow' }}
                    </button>
                    <a 
                        href="{{ route('pdg.dashboard') }}" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200 text-center"
                    >
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>

            <!-- Informations supplémentaires -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">ℹ️ {{ $isFrench ? 'Informations' : 'Information' }}</h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• {{ $isFrench ? 'La date est obligatoire pour générer le rapport' : 'Date is required to generate the report' }}</li>
                    <li>• {{ $isFrench ? 'Les filtres vendeur et produit sont optionnels' : 'Seller and product filters are optional' }}</li>
                    <li>• {{ $isFrench ? 'Sans filtre, le rapport affichera toutes les opérations de la journée' : 'Without filters, the report will show all operations for the day' }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
