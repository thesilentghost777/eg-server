@extends('layouts.app')

@section('title', $isFrench ? 'Créer un Utilisateur' : 'Create User')

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
                <i class="fas fa-user-plus mr-3"></i>
                {{ $isFrench ? 'Nouvel Utilisateur' : 'New User' }}
            </h1>
            <p class="text-amber-50 text-sm mt-1">
                {{ $isFrench ? 'Ajouter un nouveau membre à l\'équipe' : 'Add a new team member' }}
            </p>
        </div>
    </div>
</div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <form action="{{ route('users.store') }}" method="POST" x-data="{ role: '' }">
                @csrf

                <!-- Nom -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user mr-2 text-amber-600"></i>
                        {{ $isFrench ? 'Nom complet' : 'Full name' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
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
                    <input type="tel" name="numero_telephone" value="{{ old('numero_telephone') }}" required
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
                    <select name="role" x-model="role" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-green-500 focus:ring focus:ring-green-200 transition-all">
                        <option value="">{{ $isFrench ? 'Sélectionnez un rôle' : 'Select a role' }}</option>
                        <option value="pdg">PDG</option>
                        <option value="pointeur">{{ $isFrench ? 'Pointeur' : 'Pointer' }}</option>
                        <option value="vendeur_boulangerie">{{ $isFrench ? 'Vendeur Boulangerie' : 'Bakery Seller' }}</option>
                        <option value="vendeur_patisserie">{{ $isFrench ? 'Vendeur Pâtisserie' : 'Pastry Seller' }}</option>
                        <option value="producteur">{{ $isFrench ? 'Producteur' : 'Producer' }}</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code PIN -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-purple-600"></i>
                        {{ $isFrench ? 'Code PIN (6 chiffres)' : 'PIN Code (6 digits)' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code_pin" value="{{ old('code_pin') }}" required
                           maxlength="6" pattern="[0-9]{6}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring focus:ring-purple-200 transition-all font-mono text-2xl tracking-widest text-center"
                           placeholder="●●●●●●">
                    @error('code_pin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ $isFrench ? 'Le code PIN doit contenir exactement 6 chiffres' : 'PIN code must contain exactly 6 digits' }}
                    </p>
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
    {{ $isFrench ? 'Créer l\'utilisateur' : 'Create User' }}
</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
