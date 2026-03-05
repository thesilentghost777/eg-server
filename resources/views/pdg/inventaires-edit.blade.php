@extends('layouts.app')

@section('title', $isFrench ? 'Modifier Inventaire' : 'Edit Inventory')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        <i class="fas fa-edit mr-3"></i>
                        {{ $isFrench ? 'Modifier l\'Inventaire' : 'Edit Inventory' }}
                    </h1>
                    <p class="text-amber-50">
                        {{ $isFrench ? 'Inventaire' : 'Inventory' }} #{{ $inventaire->id }} - 
                        {{ $inventaire->date_inventaire->format('d/m/Y H:i') }}
                    </p>
                </div>
                <a href="{{ route('pdg.inventaires') }}" class="px-4 py-2 bg-white text-amber-700 rounded-lg hover:bg-amber-50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                </a>
            </div>
        </div>

        <!-- Informations de l'inventaire (NON MODIFIABLES) -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                {{ $isFrench ? 'Informations Générales' : 'General Information' }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Vendeur Sortant -->
                <div class="bg-red-50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-red-800 mb-2">
                        <i class="fas fa-sign-out-alt mr-1"></i>
                        {{ $isFrench ? 'Vendeur Sortant' : 'Outgoing Seller' }}
                    </label>
                    <input type="text" value="{{ $inventaire->vendeurSortant->name }}" disabled
                           class="w-full px-4 py-2 border-2 border-red-200 rounded-lg bg-red-100 text-gray-700 cursor-not-allowed font-semibold">
                    <p class="mt-2 text-sm {{ $inventaire->valide_sortant ? 'text-green-600' : 'text-orange-600' }}">
                        <i class="fas fa-{{ $inventaire->valide_sortant ? 'check-circle' : 'clock' }} mr-1"></i>
                        {{ $inventaire->valide_sortant ? ($isFrench ? 'Validé' : 'Validated') : ($isFrench ? 'En attente' : 'Pending') }}
                    </p>
                </div>

                <!-- Vendeur Entrant -->
                <div class="bg-green-50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-green-800 mb-2">
                        <i class="fas fa-sign-in-alt mr-1"></i>
                        {{ $isFrench ? 'Vendeur Entrant' : 'Incoming Seller' }}
                    </label>
                    <input type="text" value="{{ $inventaire->vendeurEntrant->name }}" disabled
                           class="w-full px-4 py-2 border-2 border-green-200 rounded-lg bg-green-100 text-gray-700 cursor-not-allowed font-semibold">
                    <p class="mt-2 text-sm {{ $inventaire->valide_entrant ? 'text-green-600' : 'text-orange-600' }}">
                        <i class="fas fa-{{ $inventaire->valide_entrant ? 'check-circle' : 'clock' }} mr-1"></i>
                        {{ $inventaire->valide_entrant ? ($isFrench ? 'Validé' : 'Validated') : ($isFrench ? 'En attente' : 'Pending') }}
                    </p>
                </div>

                <!-- Catégorie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-1 text-blue-600"></i>
                        {{ $isFrench ? 'Catégorie' : 'Category' }}
                    </label>
                    <input type="text" value="{{ ucfirst($inventaire->categorie) }}" disabled
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed font-semibold">
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1 text-amber-600"></i>
                        {{ $isFrench ? 'Date de l\'inventaire' : 'Inventory Date' }}
                    </label>
                    <input type="text" value="{{ $inventaire->date_inventaire->format('d/m/Y H:i') }}" disabled
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed font-semibold">
                </div>
            </div>
        </div>

        <!-- Formulaire de modification des produits -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-boxes text-green-600 mr-2"></i>
                {{ $isFrench ? 'Produits Inventoriés' : 'Inventoried Products' }}
            </h2>

            <form method="POST" action="{{ route('pdg.inventaires.update', $inventaire->id) }}" id="inventaireForm">
                @csrf
                @method('PUT')

                <div class="space-y-4" id="produitsContainer">
                    @foreach($inventaire->details as $index => $detail)
                    <div class="produit-row bg-gray-50 rounded-lg p-4 border-2 border-gray-200 hover:border-blue-300 transition-all">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            <!-- Produit (NON MODIFIABLE) -->
                            <div class="md:col-span-5">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-box mr-1 text-blue-600"></i>
                                    {{ $isFrench ? 'Produit' : 'Product' }}
                                </label>
                                <input type="hidden" name="details[{{ $index }}][produit_id]" value="{{ $detail->produit_id }}">
                                <input type="text" value="{{ $detail->produit->nom }} - {{ number_format($detail->produit->prix) }} F" disabled
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed">
                            </div>

                            <!-- Quantité (MODIFIABLE) -->
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-hashtag mr-1 text-green-600"></i>
                                    {{ $isFrench ? 'Quantité Restante' : 'Remaining Quantity' }} *
                                </label>
                                <input type="number" 
                                       name="details[{{ $index }}][quantite_restante]" 
                                       value="{{ old('details.'.$index.'.quantite_restante', $detail->quantite_restante) }}" 
                                       min="0" 
                                       required
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                                       onchange="calculerTotal()">
                            </div>

                            <!-- Prix Unitaire (AFFICHAGE) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Prix Unit.' : 'Unit Price' }}
                                </label>
                                <div class="px-4 py-2 bg-blue-50 border-2 border-blue-200 rounded-lg text-center">
                                    <span class="font-bold text-blue-700">{{ number_format($detail->produit->prix) }} F</span>
                                </div>
                            </div>

                            <!-- Valeur Totale (CALCULÉE) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $isFrench ? 'Valeur' : 'Value' }}
                                </label>
                                <div class="px-4 py-2 bg-green-50 border-2 border-green-200 rounded-lg text-center valeur-ligne" 
                                     data-prix="{{ $detail->produit->prix }}"
                                     data-index="{{ $index }}">
                                    <span class="font-bold text-green-700">
                                        {{ number_format($detail->quantite_restante * $detail->produit->prix) }} F
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Total Général -->
                <div class="mt-6 bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-90">{{ $isFrench ? 'Valeur Totale de l\'Inventaire' : 'Total Inventory Value' }}</p>
                            <p class="text-3xl font-bold" id="valeurTotale">
                                @php
                                    $total = $inventaire->details->sum(function($d) {
                                        return $d->quantite_restante * ($d->produit->prix ?? 0);
                                    });
                                @endphp
                                {{ number_format($total) }} F
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">{{ $isFrench ? 'Nombre de Produits' : 'Number of Products' }}</p>
                            <p class="text-3xl font-bold">{{ $inventaire->details->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Avertissement -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 my-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ $isFrench 
                                    ? 'Attention: Cette modification affectera les statistiques et les données historiques. Assurez-vous que les quantités sont correctes.' 
                                    : 'Warning: This modification will affect statistics and historical data. Make sure quantities are correct.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('pdg.inventaires') }}" 
                       class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all">
                        <i class="fas fa-times mr-2"></i>
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Enregistrer les Modifications' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                {{ $isFrench ? 'Historique' : 'History' }}
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">{{ $isFrench ? 'Créé le:' : 'Created on:' }}</span>
                    <span class="font-semibold ml-2">{{ $inventaire->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">{{ $isFrench ? 'Modifié le:' : 'Updated on:' }}</span>
                    <span class="font-semibold ml-2">{{ $inventaire->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculerTotal() {
    let total = 0;
    
    // Parcourir tous les produits
    document.querySelectorAll('.produit-row').forEach((row, index) => {
        const quantiteInput = row.querySelector('input[name*="[quantite_restante]"]');
        const valeurDiv = row.querySelector('.valeur-ligne');
        const prix = parseFloat(valeurDiv.dataset.prix);
        const quantite = parseInt(quantiteInput.value) || 0;
        
        // Calculer la valeur de cette ligne
        const valeurLigne = quantite * prix;
        total += valeurLigne;
        
        // Mettre à jour l'affichage de la valeur de cette ligne
        valeurDiv.querySelector('span').textContent = valeurLigne.toLocaleString('fr-FR') + ' F';
    });
    
    // Mettre à jour le total général
    document.getElementById('valeurTotale').textContent = total.toLocaleString('fr-FR') + ' F';
}

// Calculer le total au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    calculerTotal();
});

// Confirmation avant soumission
document.getElementById('inventaireForm').addEventListener('submit', function(e) {
    const confirmation = confirm('{{ $isFrench ? "Êtes-vous sûr de vouloir enregistrer ces modifications ?" : "Are you sure you want to save these changes?" }}');
    if (!confirmation) {
        e.preventDefault();
    }
});
</script>
@endsection