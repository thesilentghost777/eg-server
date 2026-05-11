@extends('layouts.app')

@section('title', $isFrench ? 'Retours Produits' : 'Product Returns')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-red-50">

    <!-- Header -->
    <header class="bg-gradient-to-r from-amber-700 to-amber-900 shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-undo-alt"></i>
                    {{ $isFrench ? 'Gestion des Retours' : 'Returns Management' }}
                </h1>
                <a href="{{ route('pdg.dashboard') }}"
                   class="bg-white text-amber-800 px-4 py-2 rounded-lg hover:bg-amber-100 transition-all font-semibold">
                    <i class="fas fa-home mr-2"></i>{{ $isFrench ? 'Accueil' : 'Home' }}
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl mr-3"></i>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-amber-800 flex items-center gap-2">
                    <i class="fas fa-filter"></i>
                    {{ $isFrench ? 'Filtres' : 'Filters' }}
                </h2>
                <a href="{{ route('pdg.retours.reset') }}"
                   class="text-amber-600 hover:text-amber-800 font-semibold text-sm">
                    <i class="fas fa-redo mr-1"></i>{{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                </a>
            </div>

            <form method="GET" action="{{ route('pdg.retours') }}" id="retourFilterForm"
                  class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

                <!-- Vendeur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Vendeur' : 'Seller' }}</label>
                    <select name="vendeur_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            onchange="document.getElementById('retourFilterForm').submit()">
                        <option value="">{{ $isFrench ? 'Tous les vendeurs' : 'All sellers' }}</option>
                        @foreach($vendeurs as $vendeur)
                            <option value="{{ $vendeur->id }}"
                                {{ ($currentFilters['vendeur_id'] ?? '') == $vendeur->id ? 'selected' : '' }}>
                                {{ $vendeur->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pointeur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Pointeur' : 'Pointer' }}</label>
                    <select name="pointeur_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            onchange="document.getElementById('retourFilterForm').submit()">
                        <option value="">{{ $isFrench ? 'Tous les pointeurs' : 'All pointers' }}</option>
                        @foreach($pointeurs as $pointeur)
                            <option value="{{ $pointeur->id }}"
                                {{ ($currentFilters['pointeur_id'] ?? '') == $pointeur->id ? 'selected' : '' }}>
                                {{ $pointeur->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Produit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Produit' : 'Product' }}</label>
                    <select name="produit_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            onchange="document.getElementById('retourFilterForm').submit()">
                        <option value="">{{ $isFrench ? 'Tous les produits' : 'All products' }}</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->id }}"
                                {{ ($currentFilters['produit_id'] ?? '') == $produit->id ? 'selected' : '' }}>
                                {{ $produit->nom }} — {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Raison — dynamique depuis $raisons -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Raison' : 'Reason' }}</label>
                    <select name="raison"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            onchange="document.getElementById('retourFilterForm').submit()">
                        <option value="">{{ $isFrench ? 'Toutes les raisons' : 'All reasons' }}</option>
                        @foreach($raisons as $raison)
                            <option value="{{ $raison->code }}"
                                {{ ($currentFilters['raison'] ?? '') === $raison->code ? 'selected' : '' }}>
                                {{ $raison->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date début -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date début' : 'Start date' }}</label>
                    <input type="date" name="date_debut"
                           value="{{ $currentFilters['date_debut'] ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                           onchange="document.getElementById('retourFilterForm').submit()">
                </div>

                <!-- Date fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Date fin' : 'End date' }}</label>
                    <input type="date" name="date_fin"
                           value="{{ $currentFilters['date_fin'] ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                           onchange="document.getElementById('retourFilterForm').submit()">
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $isFrench ? 'Statut' : 'Status' }}</label>
                    <select name="verrou"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            onchange="document.getElementById('retourFilterForm').submit()">
                        <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                        <option value="0" {{ ($currentFilters['verrou'] ?? '') === '0' ? 'selected' : '' }}>{{ $isFrench ? 'Non verrouillé' : 'Unlocked' }}</option>
                        <option value="1" {{ ($currentFilters['verrou'] ?? '') === '1' ? 'selected' : '' }}>{{ $isFrench ? 'Verrouillé' : 'Locked' }}</option>
                    </select>
                </div>

                <!-- Raccourcis + bouton -->
                <div class="flex items-end gap-2">
                    <button type="button" onclick="setRetourDateFilter('today')"
                            class="flex-1 px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition-all">
                        <i class="fas fa-calendar-day mr-1"></i>{{ $isFrench ? "Auj." : 'Today' }}
                    </button>
                    <button type="submit"
                            class="flex-1 px-3 py-2 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700 transition-all">
                        <i class="fas fa-search mr-1"></i>{{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-amber-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Total Retours' : 'Total Returns' }}</p>
                        <p class="text-3xl font-bold text-amber-800 mt-2">{{ $retours->total() }}</p>
                    </div>
                    <i class="fas fa-boxes text-4xl text-amber-300"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Verrouillés' : 'Locked' }}</p>
                        <p class="text-3xl font-bold text-red-800 mt-2">
                            {{ $retours->getCollection()->where('verrou', true)->count() }}
                        </p>
                    </div>
                    <i class="fas fa-lock text-4xl text-red-300"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">{{ $isFrench ? 'Modifiables' : 'Editable' }}</p>
                        <p class="text-3xl font-bold text-green-800 mt-2">
                            {{ $retours->getCollection()->where('verrou', false)->count() }}
                        </p>
                    </div>
                    <i class="fas fa-unlock text-4xl text-green-300"></i>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-amber-700 to-amber-800 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">{{ $isFrench ? 'Date' : 'Date' }}</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">{{ $isFrench ? 'Raison' : 'Reason' }}</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">{{ $isFrench ? 'Vendeur' : 'Seller' }}</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase">{{ $isFrench ? 'Actions' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($retours as $retour)
                            @php
                                $idsFusionnes = $retour->ids_fusionnes ?? [$retour->id];
                            @endphp
                            <tr class="hover:bg-amber-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $retour->date_retour->format('d/m/Y H:i') }}
                                    @if($retour->est_fusion ?? false)
                                        <br>
                                        <span class="text-xs text-indigo-500 font-semibold">
                                            <i class="fas fa-layer-group mr-1"></i>{{ count($idsFusionnes) }} {{ $isFrench ? 'entrées' : 'entries' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $retour->produit->nom }}
                                    <br>
                                    <span class="text-xs text-gray-500">{{ number_format($retour->produit->prix, 0, ',', ' ') }} FCFA</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="font-bold text-amber-700 text-lg">
                                        {{ $retour->quantite_fusionnee ?? $retour->quantite }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{-- ✅ Utilisation des tableaux dynamiques passés par le controller --}}
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $raisonColors[$retour->raison] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $raisonLabels[$retour->raison] ?? $retour->raison }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $retour->vendeur->name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($retour->verrou)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            <i class="fas fa-lock mr-2"></i>{{ $isFrench ? 'Verrouillé' : 'Locked' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-unlock mr-2"></i>{{ $isFrench ? 'Modifiable' : 'Editable' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if(!$retour->verrou)
                                            <a href="{{ route('pdg.retours.edit', $retour->id) }}?ids_fusionnes={{ implode(',', $idsFusionnes) }}"
                                               class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all shadow-md">
                                                <i class="fas fa-edit mr-1"></i>{{ $isFrench ? 'Modifier' : 'Edit' }}
                                            </a>
                                            <button onclick="confirmDeleteRetour({{ $retour->id }}, '{{ implode(',', $idsFusionnes) }}')"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all shadow-md">
                                                <i class="fas fa-trash mr-1"></i>{{ $isFrench ? 'Supprimer' : 'Delete' }}
                                            </button>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                                <i class="fas fa-lock mr-1"></i>{{ $isFrench ? 'Verrouillé' : 'Locked' }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center text-gray-500">
                                        <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                                        <p class="text-lg font-semibold">{{ $isFrench ? 'Aucun retour enregistré' : 'No returns recorded' }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($retours->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $retours->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Formulaire suppression caché -->
        <form id="deleteRetourForm" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
            <input type="hidden" name="ids_fusionnes" id="deleteRetourIdsFusionnes">
        </form>
    </main>
</div>

<script>
function setRetourDateFilter(type) {
    const today = new Date();
    const s = today.toISOString().split('T')[0];
    document.querySelector('input[name="date_debut"]').value = s;
    document.querySelector('input[name="date_fin"]').value   = s;
    document.getElementById('retourFilterForm').submit();
}

function confirmDeleteRetour(id, idsFusionnes) {
    const isFusion = idsFusionnes.split(',').length > 1;
    const msg = isFusion
        ? '{{ $isFrench ? "Ce groupe contient plusieurs entrées. Toutes seront supprimées !" : "This group has multiple entries. All will be deleted!" }}'
        : '{{ $isFrench ? "Ce retour sera supprimé définitivement." : "This return will be permanently deleted." }}';

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '{{ $isFrench ? "Supprimer ?" : "Delete?" }}',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '{{ $isFrench ? "Oui, supprimer" : "Yes, delete" }}',
            cancelButtonText: '{{ $isFrench ? "Annuler" : "Cancel" }}'
        }).then(r => { if (r.isConfirmed) submitDeleteRetour(id, idsFusionnes); });
    } else {
        if (confirm(msg)) submitDeleteRetour(id, idsFusionnes);
    }
}

function submitDeleteRetour(id, idsFusionnes) {
    const form = document.getElementById('deleteRetourForm');
    form.action = `/pdg/retours/${id}`;
    document.getElementById('deleteRetourIdsFusionnes').value = idsFusionnes;
    form.submit();
}
</script>
@endsection