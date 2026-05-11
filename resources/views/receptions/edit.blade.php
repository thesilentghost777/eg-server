@extends('layouts.app')

@section('title', $isFrench ? 'Modifier Réception' : 'Edit Reception')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8">
    <div class="container mx-auto px-4 max-w-4xl">

        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-1">
                        <i class="fas fa-edit mr-3"></i>
                        {{ $isFrench ? 'Modifier la Réception' : 'Edit Reception' }}
                    </h1>
                    <p class="text-amber-100 text-sm">
                        {{ $isFrench ? 'Réception #' : 'Reception #' }}{{ $reception->id }}
                        @if(count($idsFusionnes) > 1)
                            — <i class="fas fa-layer-group mr-1"></i>
                            {{ count($idsFusionnes) }} {{ $isFrench ? 'entrées fusionnées' : 'merged entries' }}
                        @endif
                    </p>
                </div>
                <a href="{{ route('pdg.receptions') }}"
                   class="px-4 py-2 bg-white text-amber-700 rounded-lg hover:bg-amber-50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>

        @if(count($idsFusionnes) > 1)
        <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <i class="fas fa-info-circle text-indigo-500 text-xl mr-3 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-indigo-800">
                        {{ $isFrench ? 'Entrées fusionnées' : 'Merged entries' }}
                    </p>
                    <p class="text-sm text-indigo-700 mt-1">
                        {{ $isFrench
                            ? 'Ce groupe regroupe '.count($idsFusionnes).' entrées avec une quantité totale de '.$quantiteFusionnee.'. La modification conservera une seule entrée.'
                            : 'This group merges '.count($idsFusionnes).' entries with a total quantity of '.$quantiteFusionnee.'. Saving will keep a single entry.' }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg">
            @foreach($errors->all() as $error)
                <p><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('pdg.receptions.update', $reception->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="ids_fusionnes" value="{{ implode(',', $idsFusionnes) }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    <!-- Pointeur (lecture seule) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-check text-blue-600 mr-1"></i>
                            {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                        </label>
                        <input type="text" value="{{ $reception->pointeur->name }}" disabled
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>

                    <!-- Producteur (lecture seule) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tie text-purple-600 mr-1"></i>
                            {{ $isFrench ? 'Producteur' : 'Producer' }}
                        </label>
                        <input type="text" value="{{ $reception->producteur->name }}" disabled
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>

                    <!-- Produit -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-box text-green-600 mr-1"></i>
                            {{ $isFrench ? 'Produit' : 'Product' }} *
                        </label>
                        <select name="produit_id" required
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200">
                            @foreach($produits as $produit)
                                <option value="{{ $produit->id }}"
                                    {{ $reception->produit_id == $produit->id ? 'selected' : '' }}>
                                    {{ $produit->nom }} — {{ number_format($produit->prix, 0, ',', ' ') }} FCFA ({{ ucfirst($produit->categorie) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantité -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag text-blue-600 mr-1"></i>
                            {{ $isFrench ? 'Quantité totale' : 'Total Quantity' }} *
                        </label>
                        <input type="number" name="quantite"
                               value="{{ old('quantite', $quantiteFusionnee) }}"
                               min="1" required
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    </div>

                    <!-- Vendeur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-store text-green-600 mr-1"></i>
                            {{ $isFrench ? 'Vendeur Assigné' : 'Assigned Seller' }}
                        </label>
                        <select name="vendeur_assigne_id"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200">
                            <option value="">{{ $isFrench ? 'Non assigné' : 'Not assigned' }}</option>
                            @foreach($vendeurs as $vendeur)
                                <option value="{{ $vendeur->id }}"
                                    {{ $reception->vendeur_assigne_id == $vendeur->id ? 'selected' : '' }}>
                                    {{ $vendeur->name }} ({{ ucfirst(str_replace('vendeur_', '', $vendeur->role)) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
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

                <!-- Avertissement -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mr-3 mt-0.5"></i>
                        <p class="text-sm text-yellow-700">
                            {{ $isFrench
                                ? 'Attention : cette modification affectera les statistiques et peut impacter les inventaires associés.'
                                : 'Warning: this modification will affect statistics and may impact associated inventories.' }}
                        </p>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('pdg.receptions') }}"
                       class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-md">
                        <i class="fas fa-save mr-2"></i>{{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Infos -->
        <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                {{ $isFrench ? 'Informations' : 'Information' }}
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">{{ $isFrench ? 'Créée le :' : 'Created on:' }}</span>
                    <span class="font-semibold ml-2">{{ $reception->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">{{ $isFrench ? 'Modifiée le :' : 'Updated on:' }}</span>
                    <span class="font-semibold ml-2">{{ $reception->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection