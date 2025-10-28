@extends('layouts.app')

@section('title', $isFrench ? 'Modifier le Produit' : 'Edit Product')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('produits.index') }}" 
               class="inline-flex items-center text-amber-700 hover:text-amber-900 font-semibold mb-4 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ $isFrench ? 'Retour à la liste' : 'Back to list' }}
            </a>
            <h1 class="text-3xl sm:text-4xl font-bold text-amber-900">
                <i class="fas fa-edit mr-3 text-amber-600"></i>
                {{ $isFrench ? 'Modifier le Produit' : 'Edit Product' }}
            </h1>
            <p class="text-amber-700 mt-2">
                {{ $isFrench ? 'Mettez à jour les informations du produit' : 'Update the product information' }}
            </p>
        </div>

        <!-- Card produit actuel -->
        <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl shadow-xl p-6 mb-6 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-amber-100 text-sm mb-1">{{ $isFrench ? 'Produit actuel' : 'Current product' }}</p>
                    <h2 class="text-2xl font-bold">{{ $produit->nom }}</h2>
                    <p class="text-amber-100 mt-1">{{ number_format($produit->prix, 2) }} FCFA</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg font-semibold">
                        <i class="fas {{ $produit->categorie === 'boulangerie' ? 'fa-bread-slice' : 'fa-birthday-cake' }} mr-2"></i>
                        {{ $isFrench ? ucfirst($produit->categorie) : ($produit->categorie === 'boulangerie' ? 'Bakery' : 'Pastry') }}
                    </span>
                    <span class="px-4 py-2 {{ $produit->actif ? 'bg-green-500' : 'bg-red-500' }} rounded-lg font-semibold">
                        <i class="fas {{ $produit->actif ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                        {{ $produit->actif ? ($isFrench ? 'Actif' : 'Active') : ($isFrench ? 'Inactif' : 'Inactive') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-t-4 border-blue-600">
            <form action="{{ route('produits.update', $produit->id) }}" method="POST" x-data="formHandler()" @submit.prevent="submitForm">
                @csrf
                @method('PUT')
                
                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Nom du produit -->
                    <div>
                        <label for="nom" class="block text-sm font-bold text-amber-900 mb-2">
                            <i class="fas fa-tag mr-2 text-amber-600"></i>
                            {{ $isFrench ? 'Nom du Produit' : 'Product Name' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nom" 
                               name="nom" 
                               value="{{ old('nom', $produit->nom) }}"
                               required
                               class="w-full px-4 py-3 border-2 border-amber-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-lg"
                               placeholder="{{ $isFrench ? 'Ex: Pain complet, Croissant aux amandes...' : 'Ex: Whole bread, Almond croissant...' }}">
                        @error('nom')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Prix -->
                    <div>
                        <label for="prix" class="block text-sm font-bold text-amber-900 mb-2">
                            <i class="fas fa-coins mr-2 text-amber-600"></i>
                            {{ $isFrench ? 'Prix (FCFA)' : 'Price (FCFA)' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="prix" 
                                   name="prix" 
                                   value="{{ old('prix', $produit->prix) }}"
                                   step="0.01" 
                                   min="0"
                                   required
                                   class="w-full px-4 py-3 border-2 border-amber-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-lg pr-20"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <span class="text-amber-700 font-semibold">FCFA</span>
                            </div>
                        </div>
                        @error('prix')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label class="block text-sm font-bold text-amber-900 mb-3">
                            <i class="fas fa-layer-group mr-2 text-amber-600"></i>
                            {{ $isFrench ? 'Catégorie' : 'Category' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" 
                                       name="categorie" 
                                       value="boulangerie" 
                                       {{ old('categorie', $produit->categorie) === 'boulangerie' ? 'checked' : '' }}
                                       required
                                       class="peer sr-only">
                                <div class="p-6 border-3 border-amber-200 rounded-xl transition-all peer-checked:border-amber-600 peer-checked:bg-gradient-to-br peer-checked:from-amber-50 peer-checked:to-orange-50 hover:shadow-lg">
                                    <div class="flex flex-col items-center text-center">
                                        <i class="fas fa-bread-slice text-4xl text-amber-600 mb-3"></i>
                                        <h3 class="font-bold text-lg text-amber-900">
                                            {{ $isFrench ? 'Boulangerie' : 'Bakery' }}
                                        </h3>
                                        <p class="text-sm text-amber-700 mt-1">
                                            {{ $isFrench ? 'Pains et viennoiseries' : 'Breads and pastries' }}
                                        </p>
                                    </div>
                                    <div class="absolute top-2 right-2 hidden peer-checked:block">
                                        <i class="fas fa-check-circle text-2xl text-amber-600"></i>
                                    </div>
                                </div>
                            </label>

                            <label class="relative cursor-pointer group">
                                <input type="radio" 
                                       name="categorie" 
                                       value="patisserie"
                                       {{ old('categorie', $produit->categorie) === 'patisserie' ? 'checked' : '' }}
                                       required
                                       class="peer sr-only">
                                <div class="p-6 border-3 border-pink-200 rounded-xl transition-all peer-checked:border-pink-600 peer-checked:bg-gradient-to-br peer-checked:from-pink-50 peer-checked:to-rose-50 hover:shadow-lg">
                                    <div class="flex flex-col items-center text-center">
                                        <i class="fas fa-birthday-cake text-4xl text-pink-600 mb-3"></i>
                                        <h3 class="font-bold text-lg text-pink-900">
                                            {{ $isFrench ? 'Pâtisserie' : 'Pastry' }}
                                        </h3>
                                        <p class="text-sm text-pink-700 mt-1">
                                            {{ $isFrench ? 'Gâteaux et desserts' : 'Cakes and desserts' }}
                                        </p>
                                    </div>
                                    <div class="absolute top-2 right-2 hidden peer-checked:block">
                                        <i class="fas fa-check-circle text-2xl text-pink-600"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('categorie')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div>
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" 
                                       name="actif" 
                                       value="1"
                                       {{ old('actif', $produit->actif) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-14 h-8 bg-gray-300 rounded-full peer-checked:bg-green-500 transition-all"></div>
                                <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition-all peer-checked:translate-x-6 shadow-md"></div>
                            </div>
                            <span class="ml-4 text-amber-900 font-semibold">
                                <i class="fas fa-toggle-on mr-2 text-amber-600"></i>
                                {{ $isFrench ? 'Produit actif (visible dans le catalogue)' : 'Active product (visible in catalog)' }}
                            </span>
                        </label>
                    </div>

                    <!-- Informations de modification -->
                    <div class="bg-gray-50 rounded-xl p-4 border-2 border-gray-200">
                        <div class="flex items-start">
                            <i class="fas fa-clock text-gray-500 text-lg mr-3 mt-1"></i>
                            <div class="text-sm text-gray-600">
                                <p><strong>{{ $isFrench ? 'Créé le' : 'Created on' }}:</strong> {{ $produit->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mt-1"><strong>{{ $isFrench ? 'Dernière modification' : 'Last updated' }}:</strong> {{ $produit->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 sm:px-8 py-6 flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-lg">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Enregistrer les Modifications' : 'Save Changes' }}
                    </button>
                    <a href="{{ route('produits.index') }}" 
                       class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition-colors text-lg">
                        <i class="fas fa-times mr-2"></i>
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Danger zone -->
        <div class="mt-6 bg-red-50 border-2 border-red-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-red-900 mb-3 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ $isFrench ? 'Zone Dangereuse' : 'Danger Zone' }}
            </h3>
            <p class="text-red-800 mb-4 text-sm">
                {{ $isFrench ? 'La suppression de ce produit est définitive et irréversible.' : 'Deleting this product is permanent and irreversible.' }}
            </p>
            <button onclick="deleteProduit()" 
                    class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors">
                <i class="fas fa-trash-alt mr-2"></i>
                {{ $isFrench ? 'Supprimer ce Produit' : 'Delete this Product' }}
            </button>
        </div>
    </div>
</div>

<script>
function formHandler() {
    return {
        submitForm(e) {
            const form = e.target;
            const formData = new FormData(form);
            const isFrench = {{ $isFrench ? 'true' : 'false' }};
            
            Swal.fire({
                title: isFrench ? 'Modification en cours...' : 'Updating...',
                text: isFrench ? 'Veuillez patienter' : 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
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
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        window.location.href = '{{ route("produits.index") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: isFrench ? 'Erreur!' : 'Error!',
                        text: data.message || (isFrench ? 'Une erreur est survenue' : 'An error occurred'),
                        confirmButtonColor: '#2563eb'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: isFrench ? 'Erreur!' : 'Error!',
                    text: isFrench ? 'Erreur de connexion' : 'Connection error',
                    confirmButtonColor: '#2563eb'
                });
            });
        }
    }
}

function deleteProduit() {
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
            fetch('/api/produits/{{ $produit->id }}', {
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
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        window.location.href = '{{ route("produits.index") }}';
                    });
                }
            });
        }
    });
}
</script>
@endsection