@extends('layouts.app')

@section('title', $isFrench ? 'Liste des Produits' : 'Products List')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 mb-6 border-t-4 border-amber-600">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-amber-900 mb-2">
                        <i class="fas fa-bread-slice mr-3 text-amber-600"></i>
                        {{ $isFrench ? 'Gestion des Produits' : 'Products Management' }}
                    </h1>
                    <p class="text-amber-700">
                        {{ $isFrench ? 'Gérez votre catalogue de produits de boulangerie et pâtisserie' : 'Manage your bakery and pastry products catalog' }}
                    </p>
                </div>
                <a href="{{ route('produits.create') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-plus-circle mr-2"></i>
                    {{ $isFrench ? 'Nouveau Produit' : 'New Product' }}
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('produits.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            <i class="fas fa-filter mr-2"></i>
                            {{ $isFrench ? 'Catégorie' : 'Category' }}
                        </label>
                        <select name="categorie" 
                                id="categorieSelect"
                                onchange="document.getElementById('filterForm').submit()"
                                class="w-full px-4 py-2 border-2 border-amber-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                            <option value="all" {{ $categorie == 'all' ? 'selected' : '' }}>{{ $isFrench ? 'Toutes' : 'All' }}</option>
                            <option value="boulangerie" {{ $categorie == 'boulangerie' ? 'selected' : '' }}>{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</option>
                            <option value="patisserie" {{ $categorie == 'patisserie' ? 'selected' : '' }}>{{ $isFrench ? 'Pâtisserie' : 'Pastry' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            <i class="fas fa-toggle-on mr-2"></i>
                            {{ $isFrench ? 'Statut' : 'Status' }}
                        </label>
                        <select name="statut"
                                id="statutSelect"
                                onchange="document.getElementById('filterForm').submit()"
                                class="w-full px-4 py-2 border-2 border-amber-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                            <option value="all" {{ $statut == 'all' ? 'selected' : '' }}>{{ $isFrench ? 'Tous' : 'All' }}</option>
                            <option value="actif" {{ $statut == 'actif' ? 'selected' : '' }}>{{ $isFrench ? 'Actifs' : 'Active' }}</option>
                            <option value="inactif" {{ $statut == 'inactif' ? 'selected' : '' }}>{{ $isFrench ? 'Inactifs' : 'Inactive' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            <i class="fas fa-search mr-2"></i>
                            {{ $isFrench ? 'Recherche' : 'Search' }}
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="search"
                                   id="searchInput"
                                   value="{{ $search }}"
                                   placeholder="{{ $isFrench ? 'Rechercher un produit...' : 'Search a product...' }}"
                                   class="w-full px-4 py-2 pr-10 border-2 border-amber-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                            @if($search)
                            <button type="button" 
                                    onclick="document.getElementById('searchInput').value = ''; document.getElementById('filterForm').submit();"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-amber-600 hover:text-amber-800">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Badges des filtres actifs -->
                @if($categorie != 'all' || $statut != 'all' || $search)
                <div class="mt-4 flex flex-wrap gap-2 items-center">
                    <span class="text-sm font-semibold text-amber-900">{{ $isFrench ? 'Filtres actifs:' : 'Active filters:' }}</span>
                    
                    @if($categorie != 'all')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-amber-100 text-amber-800">
                        {{ $categorie === 'boulangerie' ? ($isFrench ? 'Boulangerie' : 'Bakery') : ($isFrench ? 'Pâtisserie' : 'Pastry') }}
                        <button type="button" onclick="document.getElementById('categorieSelect').value = 'all'; document.getElementById('filterForm').submit();" class="ml-2 hover:text-amber-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endif
                    
                    @if($statut != 'all')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                        {{ $statut === 'actif' ? ($isFrench ? 'Actifs' : 'Active') : ($isFrench ? 'Inactifs' : 'Inactive') }}
                        <button type="button" onclick="document.getElementById('statutSelect').value = 'all'; document.getElementById('filterForm').submit();" class="ml-2 hover:text-blue-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endif
                    
                    @if($search)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                        "{{ $search }}"
                        <button type="button" onclick="document.getElementById('searchInput').value = ''; document.getElementById('filterForm').submit();" class="ml-2 hover:text-green-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                    @endif
                    
                    <a href="{{ route('produits.index') }}" class="text-sm text-red-600 hover:text-red-800 font-semibold">
                        <i class="fas fa-times-circle mr-1"></i>
                        {{ $isFrench ? 'Réinitialiser' : 'Reset all' }}
                    </a>
                </div>
                @endif
            </form>
        </div>

        <!-- Résultats -->
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <p class="text-amber-900 font-semibold">
                <i class="fas fa-info-circle mr-2"></i>
                {{ $produits->total() }} {{ $isFrench ? 'produit(s) trouvé(s)' : 'product(s) found' }}
            </p>
        </div>

        <!-- Liste des produits -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($produits as $produit)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-amber-400">
                <!-- Badge catégorie -->
                <div class="bg-gradient-to-r {{ $produit->categorie === 'boulangerie' ? 'from-amber-600 to-orange-600' : 'from-pink-600 to-rose-600' }} px-4 py-2">
                    <span class="text-white font-semibold text-sm flex items-center">
                        <i class="fas {{ $produit->categorie === 'boulangerie' ? 'fa-bread-slice' : 'fa-birthday-cake' }} mr-2"></i>
                        {{ $isFrench ? ucfirst($produit->categorie) : ($produit->categorie === 'boulangerie' ? 'Bakery' : 'Pastry') }}
                    </span>
                </div>

                <div class="p-6">
                    <!-- Nom et Prix -->
                    <h3 class="text-xl font-bold text-amber-900 mb-2 truncate">{{ $produit->nom }}</h3>
                    <p class="text-3xl font-bold text-orange-600 mb-4">{{ number_format($produit->prix, 2) }} FCFA</p>

                    <!-- Statut -->
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $produit->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $produit->actif ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $produit->actif ? ($isFrench ? 'Actif' : 'Active') : ($isFrench ? 'Inactif' : 'Inactive') }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('produits.edit', $produit->id) }}" 
                           class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                            <i class="fas fa-edit mr-1"></i>
                            {{ $isFrench ? 'Modifier' : 'Edit' }}
                        </a>
                        <button onclick="toggleActif({{ $produit->id }})"
                                class="flex-1 text-center px-4 py-2 {{ $produit->actif ? 'bg-orange-600' : 'bg-green-600' }} text-white rounded-lg hover:opacity-90 transition-opacity text-sm font-semibold">
                            <i class="fas fa-toggle-{{ $produit->actif ? 'off' : 'on' }} mr-1"></i>
                            {{ $produit->actif ? ($isFrench ? 'Désactiver' : 'Disable') : ($isFrench ? 'Activer' : 'Enable') }}
                        </button>
                        <button onclick="deleteProduit({{ $produit->id }})"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-inbox text-6xl text-amber-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-amber-900 mb-2">
                        {{ $isFrench ? 'Aucun produit trouvé' : 'No products found' }}
                    </h3>
                    <p class="text-amber-700 mb-6">
                        {{ $isFrench ? 'Essayez de modifier vos filtres ou ajoutez un nouveau produit' : 'Try adjusting your filters or add a new product' }}
                    </p>
                    @if($categorie != 'all' || $statut != 'all' || $search)
                    <a href="{{ route('produits.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 mr-2">
                        <i class="fas fa-redo mr-2"></i>
                        {{ $isFrench ? 'Réinitialiser les filtres' : 'Reset filters' }}
                    </a>
                    @endif
                    <a href="{{ route('produits.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-plus-circle mr-2"></i>
                        {{ $isFrench ? 'Ajouter un produit' : 'Add a product' }}
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($produits->hasPages())
        <div class="mt-8">
            {{ $produits->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<script>
// Recherche avec debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});

function toggleActif(id) {
    const isFrench = {{ $isFrench ? 'true' : 'false' }};
    Swal.fire({
        title: isFrench ? 'Confirmer l\'action' : 'Confirm action',
        text: isFrench ? 'Voulez-vous changer le statut de ce produit ?' : 'Do you want to change the status of this product?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d97706',
        cancelButtonColor: '#6b7280',
        confirmButtonText: isFrench ? 'Oui, changer' : 'Yes, change',
        cancelButtonText: isFrench ? 'Annuler' : 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/produits/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: isFrench ? 'Succès!' : 'Success!',
                        text: data.message,
                        confirmButtonColor: '#d97706'
                    }).then(() => location.reload());
                }
            });
        }
    });
}

function deleteProduit(id) {
    const isFrench = {{ $isFrench ? 'true' : 'false' }};
    Swal.fire({
        title: isFrench ? 'Êtes-vous sûr ?' : 'Are you sure?',
        text: isFrench ? 'Cette action est irréversible !' : 'This action is irreversible!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: isFrench ? 'Oui, supprimer' : 'Yes, delete',
        cancelButtonText: isFrench ? 'Annuler' : 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/produits/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: isFrench ? 'Supprimé!' : 'Deleted!',
                        text: data.message,
                        confirmButtonColor: '#d97706'
                    }).then(() => location.reload());
                }
            });
        }
    });
}
</script>
@endsection