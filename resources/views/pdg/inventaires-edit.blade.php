@extends('layouts.app')

@section('title', $isFrench ? 'Modifier Inventaire' : 'Edit Inventory')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-8">
    <div class="container mx-auto px-4 max-w-6xl">

        {{-- Alertes --}}
        @if(session('success'))
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
        @endif

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

        <!-- Informations générales (NON MODIFIABLES) -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                {{ $isFrench ? 'Informations Générales' : 'General Information' }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-1 text-blue-600"></i>
                        {{ $isFrench ? 'Catégorie' : 'Category' }}
                    </label>
                    <input type="text" value="{{ ucfirst($inventaire->categorie) }}" disabled
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed font-semibold">
                </div>
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

        <!-- Formulaire modification des quantités existantes -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-boxes text-green-600 mr-2"></i>
                {{ $isFrench ? 'Produits Inventoriés' : 'Inventoried Products' }}
                <span class="ml-2 text-sm font-normal text-gray-500">({{ $inventaire->details->count() }} {{ $isFrench ? 'produits' : 'products' }})</span>
            </h2>

            <form method="POST" action="{{ route('pdg.inventaires.update', $inventaire->id) }}" id="inventaireForm">
                @csrf
                @method('PUT')

                @if($inventaire->details->count() > 0)
                <div class="space-y-3" id="produitsContainer">
                    @foreach($inventaire->details as $index => $detail)
                    <div class="produit-row bg-gray-50 rounded-lg p-4 border-2 border-gray-200 hover:border-blue-300 transition-all">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">

                            {{-- Produit (non modifiable) --}}
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    {{ $isFrench ? 'Produit' : 'Product' }}
                                </label>
                                <input type="hidden" name="details[{{ $index }}][produit_id]" value="{{ $detail->produit_id }}">
                                <input type="text" value="{{ $detail->produit->nom }}" disabled
                                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed text-sm">
                            </div>

                            {{-- Quantité (modifiable) --}}
                            <div class="md:col-span-3">
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    {{ $isFrench ? 'Quantité Restante' : 'Remaining Qty' }} *
                                </label>
                                <input type="number"
                                       name="details[{{ $index }}][quantite_restante]"
                                       value="{{ old('details.'.$index.'.quantite_restante', $detail->quantite_restante) }}"
                                       min="0" required
                                       class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                                       onchange="calculerTotal()">
                            </div>

                            {{-- Prix --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    {{ $isFrench ? 'Prix Unit.' : 'Unit Price' }}
                                </label>
                                <div class="px-3 py-2 bg-blue-50 border-2 border-blue-200 rounded-lg text-center">
                                    <span class="font-bold text-blue-700 text-sm">{{ number_format($detail->produit->prix) }} F</span>
                                </div>
                            </div>

                            {{-- Valeur calculée --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    {{ $isFrench ? 'Valeur' : 'Value' }}
                                </label>
                                <div class="px-3 py-2 bg-green-50 border-2 border-green-200 rounded-lg text-center valeur-ligne"
                                     data-prix="{{ $detail->produit->prix }}">
                                    <span class="font-bold text-green-700 text-sm">
                                        {{ number_format($detail->quantite_restante * $detail->produit->prix) }} F
                                    </span>
                                </div>
                            </div>

                            {{-- Bouton supprimer --}}
                            <div class="md:col-span-1 flex justify-center">
                                <button type="button"
                                        onclick="confirmerSuppression({{ $inventaire->id }}, {{ $detail->id }})"
                                        class="w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-all flex items-center justify-center"
                                        title="{{ $isFrench ? 'Supprimer ce produit' : 'Remove this product' }}">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Total --}}
                <div class="mt-4 bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-5 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-90">{{ $isFrench ? 'Valeur Totale' : 'Total Value' }}</p>
                            <p class="text-3xl font-bold" id="valeurTotale">
                                @php
                                    $total = $inventaire->details->sum(fn($d) => $d->quantite_restante * ($d->produit->prix ?? 0));
                                @endphp
                                {{ number_format($total) }} F
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">{{ $isFrench ? 'Nb. Produits' : 'Products' }}</p>
                            <p class="text-3xl font-bold">{{ $inventaire->details->count() }}</p>
                        </div>
                    </div>
                </div>

                {{-- Avertissement --}}
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 my-4 rounded-r-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mt-0.5 mr-3"></i>
                        <p class="text-sm text-yellow-700">
                            {{ $isFrench
                                ? 'Attention : cette modification affectera les statistiques et les données historiques.'
                                : 'Warning: this modification will affect statistics and historical data.' }}
                        </p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('pdg.inventaires') }}"
                       class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all">
                        <i class="fas fa-times mr-2"></i>{{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-md">
                        <i class="fas fa-save mr-2"></i>{{ $isFrench ? 'Enregistrer les Modifications' : 'Save Changes' }}
                    </button>
                </div>

                @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-box-open text-5xl mb-3"></i>
                    <p>{{ $isFrench ? 'Aucun produit dans cet inventaire.' : 'No products in this inventory.' }}</p>
                </div>
                @endif
            </form>
        </div>

        <!-- Formulaire ajout d'un nouveau produit -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-2 border-dashed border-blue-300">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                {{ $isFrench ? 'Ajouter un Produit' : 'Add a Product' }}
            </h2>

            <form method="POST" action="{{ route('pdg.inventaires.details.add', $inventaire->id) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                    {{-- Sélection du produit --}}
                    <div class="md:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-box mr-1 text-blue-600"></i>
                            {{ $isFrench ? 'Produit' : 'Product' }} *
                        </label>
                        <select name="produit_id" required id="newProduitSelect"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                onchange="afficherPrixNouveauProduit(this)">
                            <option value="">-- {{ $isFrench ? 'Choisir un produit' : 'Choose a product' }} --</option>
                            @foreach($produits as $produit)
                                {{-- Griser les produits déjà présents --}}
                                @php $dejaPresent = $inventaire->details->pluck('produit_id')->contains($produit->id); @endphp
                                <option value="{{ $produit->id }}"
                                        data-prix="{{ $produit->prix }}"
                                        {{ $dejaPresent ? 'disabled' : '' }}>
                                    {{ $produit->nom }} - {{ number_format($produit->prix) }} F
                                    {{ $dejaPresent ? '('.$isFrench ? 'déjà ajouté' : 'already added'.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Quantité --}}
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag mr-1 text-green-600"></i>
                            {{ $isFrench ? 'Quantité Restante' : 'Remaining Qty' }} *
                        </label>
                        <input type="number" name="quantite_restante" min="0" required
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                               placeholder="0">
                    </div>

                    {{-- Prix affiché --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $isFrench ? 'Prix Unit.' : 'Unit Price' }}
                        </label>
                        <div class="px-4 py-2 bg-blue-50 border-2 border-blue-200 rounded-lg text-center" id="newPrixDisplay">
                            <span class="font-bold text-blue-700">--</span>
                        </div>
                    </div>

                    {{-- Bouton ajouter --}}
                    <div class="md:col-span-1">
                        <button type="submit"
                                class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all shadow-md flex items-center justify-center"
                                title="{{ $isFrench ? 'Ajouter' : 'Add' }}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Historique -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-history text-blue-600 mr-2"></i>
                {{ $isFrench ? 'Historique' : 'History' }}
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">{{ $isFrench ? 'Créé le :' : 'Created on:' }}</span>
                    <span class="font-semibold ml-2">{{ $inventaire->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">{{ $isFrench ? 'Modifié le :' : 'Updated on:' }}</span>
                    <span class="font-semibold ml-2">{{ $inventaire->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal confirmation suppression --}}
<div id="modalSuppression" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-3xl text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">
                {{ $isFrench ? 'Supprimer ce produit ?' : 'Remove this product?' }}
            </h3>
            <p class="text-gray-600 text-sm">
                {{ $isFrench
                    ? 'Ce produit sera retiré définitivement de l\'inventaire.'
                    : 'This product will be permanently removed from the inventory.' }}
            </p>
        </div>
        <form method="POST" id="formSuppression">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="fermerModal()"
                        class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all font-medium">
                    <i class="fas fa-times mr-2"></i>{{ $isFrench ? 'Annuler' : 'Cancel' }}
                </button>
                <button type="submit"
                        class="flex-1 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-medium shadow-md">
                    <i class="fas fa-trash mr-2"></i>{{ $isFrench ? 'Supprimer' : 'Delete' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function calculerTotal() {
    let total = 0;
    document.querySelectorAll('.produit-row').forEach(row => {
        const quantiteInput = row.querySelector('input[name*="[quantite_restante]"]');
        const valeurDiv = row.querySelector('.valeur-ligne');
        const prix = parseFloat(valeurDiv.dataset.prix);
        const quantite = parseInt(quantiteInput.value) || 0;
        const valeurLigne = quantite * prix;
        total += valeurLigne;
        valeurDiv.querySelector('span').textContent = valeurLigne.toLocaleString('fr-FR') + ' F';
    });
    document.getElementById('valeurTotale').textContent = total.toLocaleString('fr-FR') + ' F';
}

function afficherPrixNouveauProduit(select) {
    const option = select.options[select.selectedIndex];
    const prix = option.dataset.prix;
    const display = document.getElementById('newPrixDisplay').querySelector('span');
    display.textContent = prix ? parseInt(prix).toLocaleString('fr-FR') + ' F' : '--';
}

function confirmerSuppression(inventaireId, detailId) {
    const url = `/pdg/inventaires/${inventaireId}/details/${detailId}`;
    document.getElementById('formSuppression').action = url;
    document.getElementById('modalSuppression').classList.remove('hidden');
}

function fermerModal() {
    document.getElementById('modalSuppression').classList.add('hidden');
}

document.getElementById('modalSuppression').addEventListener('click', function(e) {
    if (e.target === this) fermerModal();
});

document.addEventListener('DOMContentLoaded', calculerTotal);

document.getElementById('inventaireForm')?.addEventListener('submit', function(e) {
    if (!confirm('{{ $isFrench ? "Enregistrer les modifications ?" : "Save changes?" }}')) {
        e.preventDefault();
    }
});
</script>
@endsection