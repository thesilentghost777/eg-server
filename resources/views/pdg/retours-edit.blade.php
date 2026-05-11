@extends('layouts.app')

@section('title', $isFrench ? 'Modifier le Retour' : 'Edit Return')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-red-50">
    <div class="container mx-auto px-4 py-8 max-w-2xl">

        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-700 to-amber-900 rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">
                        <i class="fas fa-edit mr-3"></i>
                        {{ $isFrench ? 'Modifier le Retour' : 'Edit Return' }}
                    </h1>
                    @if(count($idsFusionnes) > 1)
                        <p class="text-amber-200 text-sm mt-1">
                            <i class="fas fa-layer-group mr-1"></i>
                            {{ count($idsFusionnes) }} {{ $isFrench ? 'entrées fusionnées — quantité totale : ' : 'merged entries — total qty: ' }}
                            <strong>{{ $quantiteFusionnee }}</strong>
                        </p>
                    @endif
                </div>
                <a href="{{ route('pdg.retours') }}"
                   class="bg-white text-amber-800 px-4 py-2 rounded-lg hover:bg-amber-50 transition-all font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>

        @if(count($idsFusionnes) > 1)
        <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <i class="fas fa-info-circle text-indigo-500 text-xl mr-3 mt-0.5"></i>
                <p class="text-sm text-indigo-700">
                    {{ $isFrench
                        ? 'La modification conservera une seule entrée avec la quantité saisie et supprimera les autres.'
                        : 'Saving will keep one entry with the entered quantity and delete the others.' }}
                </p>
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

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('pdg.retours.update', $retour->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="ids_fusionnes" value="{{ implode(',', $idsFusionnes) }}">

                <div class="grid grid-cols-1 gap-5">

                    <!-- Produit -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-box mr-1 text-amber-600"></i>
                            {{ $isFrench ? 'Produit' : 'Product' }} *
                        </label>
                        <select name="produit_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-amber-500 transition-all">
                            @foreach($produits as $produit)
                                <option value="{{ $produit->id }}"
                                    {{ old('produit_id', $retour->produit_id) == $produit->id ? 'selected' : '' }}>
                                    {{ $produit->nom }} — {{ number_format($produit->prix, 0, ',', ' ') }} FCFA ({{ ucfirst($produit->categorie) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantité -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-hashtag mr-1 text-blue-600"></i>
                            {{ $isFrench ? 'Quantité totale' : 'Total Quantity' }} *
                        </label>
                        <input type="number" name="quantite" min="1" required
                               value="{{ old('quantite', $quantiteFusionnee) }}"
                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-blue-500 transition-all">
                    </div>

                    <!-- Raison -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag mr-1 text-red-600"></i>
                            {{ $isFrench ? 'Raison' : 'Reason' }} *
                        </label>
                        <select name="raison" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-red-500 transition-all">
                            <option value="perime" {{ old('raison', $retour->raison) === 'perime' ? 'selected' : '' }}>{{ $isFrench ? 'Périmé' : 'Expired' }}</option>
                            <option value="abime"  {{ old('raison', $retour->raison) === 'abime'  ? 'selected' : '' }}>{{ $isFrench ? 'Abîmé' : 'Damaged' }}</option>
                            <option value="autre"  {{ old('raison', $retour->raison) === 'autre'  ? 'selected' : '' }}>{{ $isFrench ? 'Autre' : 'Other' }}</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-comment mr-1 text-gray-500"></i>
                            {{ $isFrench ? 'Description (optionnel)' : 'Description (optional)' }}
                        </label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-gray-500 transition-all resize-none">{{ old('description', $retour->description) }}</textarea>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1 text-purple-600"></i>
                            {{ $isFrench ? 'Date du retour' : 'Return date' }} *
                        </label>
                        <input type="datetime-local" name="date_retour" required
                               value="{{ old('date_retour', $retour->date_retour->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-purple-500 transition-all">
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-all shadow-md">
                        <i class="fas fa-save mr-2"></i>{{ $isFrench ? 'Enregistrer' : 'Save' }}
                    </button>
                    <a href="{{ route('pdg.retours') }}"
                       class="flex-1 px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all shadow-md text-center">
                        <i class="fas fa-times mr-2"></i>{{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection