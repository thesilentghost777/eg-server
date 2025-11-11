<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Modifier le Retour' : 'Edit Return' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-amber-50 font-sans">
    <div class="min-h-screen">
        <header class="bg-gradient-to-r from-amber-700 to-amber-900 shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-edit"></i>
                        {{ $isFrench ? 'Modifier le Retour' : 'Edit Return' }}
                    </h1>
                    <a href="{{ route('retours.index') }}" class="bg-white text-amber-800 px-4 py-2 rounded-lg hover:bg-amber-100 transition-all duration-300 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>{{ $isFrench ? 'Retour' : 'Back' }}
                    </a>
                </div>
            </div>
        </header>

        <main class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('retours.update', $retour) }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden">
                    @csrf
                    @method('PUT')

                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">{{ $isFrench ? 'Formulaire de Modification' : 'Edit Form' }}</h2>
                    </div>

                    <div class="p-6 space-y-6">
                        @if($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">{{ $isFrench ? 'Produit' : 'Product' }} *</label>
                                <select name="produit_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300">
                                    @foreach($produits as $produit)
                                        <option value="{{ $produit->id }}" {{ $retour->produit_id == $produit->id ? 'selected' : '' }}>
                                            {{ $produit->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">{{ $isFrench ? 'Quantité' : 'Quantity' }} *</label>
                                <input type="number" name="quantite" value="{{ $retour->quantite }}" min="1" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">{{ $isFrench ? 'Pointeur' : 'Counter' }} *</label>
                                <select name="pointeur_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300">
                                    @foreach($pointeurs as $pointeur)
                                        <option value="{{ $pointeur->id }}" {{ $retour->pointeur_id == $pointeur->id ? 'selected' : '' }}>
                                            {{ $pointeur->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">{{ $isFrench ? 'Vendeur' : 'Seller' }} *</label>
                                <select name="vendeur_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300">
                                    @foreach($vendeurs as $vendeur)
                                        <option value="{{ $vendeur->id }}" {{ $retour->vendeur_id == $vendeur->id ? 'selected' : '' }}>
                                            {{ $vendeur->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">{{ $isFrench ? 'Raison' : 'Reason' }} *</label>
                                <select name="raison" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300">
                                    <option value="perime" {{ $retour->raison == 'perime' ? 'selected' : '' }}>{{ $isFrench ? 'Périmé' : 'Expired' }}</option>
                                    <option value="abime" {{ $retour->raison == 'abime' ? 'selected' : '' }}>{{ $isFrench ? 'Abîmé' : 'Damaged' }}</option>
                                    <option value="autre" {{ $retour->raison == 'autre' ? 'selected' : '' }}>{{ $isFrench ? 'Autre' : 'Other' }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">{{ $isFrench ? 'Date' : 'Date' }} *</label>
                                <input type="datetime-local" name="date_retour" value="{{ $retour->date_retour->format('Y-m-d\TH:i') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">{{ $isFrench ? 'Description' : 'Description' }}</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-300">{{ $retour->description }}</textarea>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i>{{ $isFrench ? 'Enregistrer' : 'Save' }}
                            </button>
                            <a href="{{ route('retours.index') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg text-center">
                                <i class="fas fa-times mr-2"></i>{{ $isFrench ? 'Annuler' : 'Cancel' }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
