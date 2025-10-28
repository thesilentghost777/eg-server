@extends('layouts.app')

@section('title', $isFrench ? 'Nouveau Produit' : 'New Product')

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
                <i class="fas fa-plus-circle mr-3 text-amber-600"></i>
                {{ $isFrench ? 'Créer un Nouveau Produit' : 'Create a New Product' }}
            </h1>
            <p class="text-amber-700 mt-2">
                {{ $isFrench ? 'Ajoutez un nouveau produit à votre catalogue' : 'Add a new product to your catalog' }}
            </p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-t-4 border-amber-600">
            <form action="{{ route('produits.store') }}" method="POST" x-data="formHandler()" @submit.prevent="submitForm">
                @csrf
                
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
                               x-model="formData.nom"
                               required
                               class="w-full px-4 py-3 border-2 border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-lg"
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
                                   x-model="formData.prix"
                                   step="0.01" 
                                   min="0"
                                   required
                                   class="w-full px-4 py-3 border-2 border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-lg pr-20"
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
                                       x-model="formData.categorie"
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
                                       x-model="formData.categorie"
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
                                       x-model="formData.actif"
                                       checked
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
                </div>

                <!-- Actions -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 sm:px-8 py-6 flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-lg">
                        <i class="fas fa-save mr-2"></i>
                        {{ $isFrench ? 'Créer le Produit' : 'Create Product' }}
                    </button>
                    <a href="{{ route('produits.index') }}" 
                       class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition-colors text-lg">
                        <i class="fas fa-times mr-2"></i>
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Info card -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="font-bold text-blue-900 mb-1">
                        {{ $isFrench ? 'Conseil' : 'Tip' }}
                    </h3>
                    <p class="text-blue-800 text-sm">
                        {{ $isFrench ? 'Choisissez des noms clairs et attractifs pour vos produits. Un bon nom augmente les ventes!' : 'Choose clear and attractive names for your products. A good name increases sales!' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function formHandler() {
    return {
        formData: {
            nom: '',
            prix: '',
            categorie: '',
            actif: true
        },

        async submitForm(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const isFrench = {{ $isFrench ? 'true' : 'false' }};

            Swal.fire({
                title: isFrench ? 'Création en cours...' : 'Creating...',
                text: isFrench ? 'Veuillez patienter...' : 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                // Vérifier si la réponse est JSON
                const contentType = response.headers.get('content-type');
                const isJson = contentType && contentType.includes('application/json');

                const data = isJson ? await response.json() : {};

                if (!response.ok) {
                    // Erreurs Laravel (validation ou autres)
                    if (data.errors) {
                        const errors = Object.values(data.errors).flat().join('\n');
                        throw new Error(errors);
                    }
                    throw new Error(data.message || (isFrench ? 'Une erreur serveur est survenue.' : 'A server error occurred.'));
                }

                // Succès
                if (data.success) {
    Swal.fire({
        icon: 'success',
        title: isFrench ? 'Succès !' : 'Success!',
        text: data.message || (isFrench ? 'Produit créé avec succès.' : 'Product created successfully.'),
        confirmButtonColor: '#d97706',
        timer: 2000, // Fermeture automatique après 2 secondes
        showConfirmButton: false
    }).then(() => {
        // Réinitialiser le formulaire au lieu de rediriger
        document.querySelector('form').reset();
        
        // Si vous avez des champs spécifiques à réinitialiser (comme des selects ou des éditeurs)
        // Ajoutez-les ici
        
        // Optionnel : Scroll vers le haut du formulaire
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
} else {
    throw new Error(data.message || (isFrench ? 'Une erreur est survenue.' : 'An error occurred.'));
}

            } catch (error) {
                // Gestion d’erreurs complète
                Swal.fire({
                    icon: 'error',
                    title: isFrench ? 'Erreur !' : 'Error!',
                    text: error.message || (isFrench ? 'Erreur de connexion.' : 'Connection error.'),
                    confirmButtonColor: '#d97706'
                });
            }
        }
    };
}
</script>

@endsection