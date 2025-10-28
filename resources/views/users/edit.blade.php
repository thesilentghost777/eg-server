@extends('layouts.app')

@section('title', $isFrench ? 'Modifier l\'Utilisateur' : 'Edit User')

@section('styles')
<style>
    .bread-gradient {
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 py-6 sm:py-12">
    <div class="container mx-auto px-4 max-w-2xl">
       <!-- Header -->
<div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 mb-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('users.index') }}" class="text-white hover:text-amber-50 transition-colors duration-300">
            <i class="fas fa-arrow-left text-2xl"></i>
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">
                <i class="fas fa-user-edit mr-3"></i>
                {{ $isFrench ? 'Modifier l\'Utilisateur' : 'Edit User' }}
            </h1>
            <p class="text-amber-50 text-sm mt-1">
                {{ $user->nom }}
            </p>
        </div>
    </div>
</div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nom -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user mr-2 text-amber-600"></i>
                        {{ $isFrench ? 'Nom complet' : 'Full name' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-amber-500 focus:ring focus:ring-amber-200 transition-all"
                           placeholder="{{ $isFrench ? 'Entrez le nom complet' : 'Enter full name' }}">
                    @error('nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-phone mr-2 text-blue-600"></i>
                        {{ $isFrench ? 'Numéro de téléphone' : 'Phone number' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="numero_telephone" value="{{ old('numero_telephone', $user->numero_telephone) }}" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                           placeholder="{{ $isFrench ? '+XXX XXX XXX XXX' : '+XXX XXX XXX XXX' }}">
                    @error('numero_telephone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rôle -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user-tag mr-2 text-green-600"></i>
                        {{ $isFrench ? 'Rôle' : 'Role' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="role" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-green-500 focus:ring focus:ring-green-200 transition-all">
                        <option value="">{{ $isFrench ? 'Sélectionnez un rôle' : 'Select a role' }}</option>
                        <option value="pdg" {{ $user->role == 'pdg' ? 'selected' : '' }}>PDG</option>
                        <option value="pointeur" {{ $user->role == 'pointeur' ? 'selected' : '' }}>{{ $isFrench ? 'Pointeur' : 'Pointer' }}</option>
                        <option value="vendeur_boulangerie" {{ $user->role == 'vendeur_boulangerie' ? 'selected' : '' }}>{{ $isFrench ? 'Vendeur Boulangerie' : 'Bakery Seller' }}</option>
                        <option value="vendeur_patisserie" {{ $user->role == 'vendeur_patisserie' ? 'selected' : '' }}>{{ $isFrench ? 'Vendeur Pâtisserie' : 'Pastry Seller' }}</option>
                        <option value="producteur" {{ $user->role == 'producteur' ? 'selected' : '' }}>{{ $isFrench ? 'Producteur' : 'Producer' }}</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code PIN (optionnel pour modification) -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-purple-600"></i>
                        {{ $isFrench ? 'Nouveau Code PIN (optionnel)' : 'New PIN Code (optional)' }}
                    </label>
                    <input type="text" name="code_pin" value="{{ old('code_pin') }}"
                           maxlength="6" pattern="[0-9]{6}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all font-mono text-2xl tracking-widest text-center"
                           placeholder="●●●●●●">
                    @error('code_pin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ $isFrench ? 'Laissez vide pour conserver le code actuel' : 'Leave blank to keep current code' }}
                    </p>
                </div>

                <!-- Status -->
                <div class="mb-6 bg-gray-50 rounded-xl p-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="actif" value="1" {{ $user->actif ? 'checked' : '' }}
                               class="w-6 h-6 text-green-600 border-2 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-gray-700 font-semibold">
                            <i class="fas fa-check-circle mr-2 text-green-600"></i>
                            {{ $isFrench ? 'Utilisateur actif' : 'User active' }}
                        </span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mt-8">
                    <a href="{{ route('users.index') }}" 
                       class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 px-6 rounded-xl font-semibold text-center transition-all">
                        <i class="fas fa-times mr-2"></i>
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </a>
                    <button type="submit"
        class="flex-1 bg-gradient-to-r from-amber-700 to-amber-600 text-white py-3 px-6 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 transform hover:scale-105">
    <i class="fas fa-save mr-2"></i>
    {{ $isFrench ? 'Enregistrer' : 'Save Changes' }}
</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
