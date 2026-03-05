@extends('layouts.app')

@section('title', $isFrench ? 'Modifier Réception' : 'Edit Reception')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        <i class="fas fa-edit mr-3"></i>
                        {{ $isFrench ? 'Modifier la Réception' : 'Edit Reception' }}
                    </h1>
                    <p class="text-amber-50">{{ $isFrench ? 'Réception #' : 'Reception #' }}{{ $reception->id }}</p>
                </div>
                <a href="{{ route('pdg.receptions') }}" class="px-4 py-2 bg-white text-amber-700 rounded-lg hover:bg-amber-50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('pdg.receptions.update', $reception->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Pointeur (NON MODIFIABLE) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-check text-blue-600 mr-1"></i>
                            {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                        </label>
                        <input type="text" value="{{ $reception->pointeur->name }}" disabled
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>

                    <!-- Producteur (NON MODIFIABLE) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tie text-purple-600 mr-1"></i>
                            {{ $isFrench ? 'Producteur' : 'Producer' }}
                        </label>
                        <input type="text" value="{{ $reception->producteur->name }}" disabled
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>

                    <!-- Produit (MODIFIABLE) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-box text-green-600 mr-1"></i>
                            {{ $isFrench ? 'Produit' : 'Product' }} *
                        </label>
                        <select name="produit_id" required
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200">
                            @foreach($produits as $produit)
                                <option value="{{ $produit->id }}" {{ $reception->produit_id == $produit->id ? 'selected' : '' }}>
                                    {{ $produit->nom }} - {{ $produit->prix }} FCFA ({{ ucfirst($produit->categorie) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantité (MODIFIABLE) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag text-blue-600 mr-1"></i>
                            {{ $isFrench ? 'Quantité' : 'Quantity' }} *
                        </label>
                        <input type="number" name="quantite" value="{{ old('quantite', $reception->quantite) }}" 
                               min="1" required
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    </div>

                    <!-- Vendeur Assigné (MODIFIABLE) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-store text-green-600 mr-1"></i>
                            {{ $isFrench ? 'Vendeur Assigné' : 'Assigned Seller' }}
                        </label>
                        <select name="vendeur_assigne_id"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200">
                            <option value="">{{ $isFrench ? 'Non assigné' : 'Not assigned' }}</option>
                            @foreach($vendeurs as $vendeur)
                                <option value="{{ $vendeur->id }}" {{ $reception->vendeur_assigne_id == $vendeur->id ? 'selected' : '' }}>
                                    {{ $vendeur->name }} ({{ ucfirst(str_replace('vendeur_', '', $vendeur->role)) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date de Réception (MODIFIABLE) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-amber-600 mr-1"></i>
                            {{ $isFrench ? 'Date de Réception' : 'Reception Date' }} *
                        </label>
                        <input type="datetime-local" name="date_reception" 
                               value="{{ old('date_reception', $reception->date_reception->format('Y-m-d\TH:i')) }}" 
                               required
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200">
                    </div>
                </div>

                <!-- Notes (NON MODIFIABLE - affiché en lecture seule) -->
                @if($reception->notes)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-gray-600 mr-1"></i>
                        {{ $isFrench ? 'Notes' : 'Notes' }}
                    </label>
                    <textarea disabled rows="3"
                              class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">{{ $reception->notes }}</textarea>
                </div>
                @endif

                <!-- Avertissement -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ $isFrench 
                                    ? 'Attention: Cette modification affectera les statistiques et peut impacter les inventaires et sessions de vente associées.' 
                                    : 'Warning: This modification will affect statistics and may impact associated inventories and sales sessions.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('pdg.receptions') }}" 
                       class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                {{ $isFrench ? 'Informations' : 'Information' }}
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">{{ $isFrench ? 'Créée le:' : 'Created on:' }}</span>
                    <span class="font-semibold ml-2">{{ $reception->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">{{ $isFrench ? 'Modifiée le:' : 'Updated on:' }}</span>
                    <span class="font-semibold ml-2">{{ $reception->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">{{ $isFrench ? 'Statut:' : 'Status:' }}</span>
                    <span class="ml-2">
                        @if($reception->verrou)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">
                                <i class="fas fa-lock mr-1"></i>{{ $isFrench ? 'Verrouillé' : 'Locked' }}
                            </span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">
                                <i class="fas fa-lock-open mr-1"></i>{{ $isFrench ? 'Modifiable' : 'Editable' }}
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection