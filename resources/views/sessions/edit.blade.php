@extends('layouts.app')

@section('title', 'Modifier Session #' . $session->id)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Modifier Session #{{ $session->id }}</h1>
        <a href="{{ route('sessions.show', $session->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">
            Annuler
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Info session -->
    <div class="bg-blue-50 rounded-lg shadow-md p-4 mb-6">
        <div class="flex justify-between">
            <span><strong>Vendeur:</strong> {{ $session->vendeur->name }}</span>
            <span><strong>Catégorie:</strong> {{ ucfirst($session->categorie) }}</span>
            <span><strong>Statut:</strong> 
                <span class="px-2 py-1 rounded-full text-xs {{ $session->statut == 'ouverte' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($session->statut) }}
                </span>
            </span>
        </div>
    </div>

    <form action="{{ route('sessions.update', $session->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')
        
        <h2 class="text-xl font-bold text-gray-800 mb-6">Modifier les Montants</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Montants d'ouverture -->
            <div class="border-r pr-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Ouverture</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="fond_vente" class="block text-sm font-medium text-gray-700 mb-2">
                            Fond de Vente <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="fond_vente" 
                            name="fond_vente" 
                            value="{{ old('fond_vente', $session->fond_vente) }}"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required
                        >
                        @error('fond_vente')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="orange_money_initial" class="block text-sm font-medium text-gray-700 mb-2">
                            Orange Money Initial <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="orange_money_initial" 
                            name="orange_money_initial" 
                            value="{{ old('orange_money_initial', $session->orange_money_initial) }}"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required
                        >
                        @error('orange_money_initial')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mtn_money_initial" class="block text-sm font-medium text-gray-700 mb-2">
                            MTN Money Initial <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="mtn_money_initial" 
                            name="mtn_money_initial" 
                            value="{{ old('mtn_money_initial', $session->mtn_money_initial) }}"
                            class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required
                        >
                        @error('mtn_money_initial')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Montants de fermeture -->
            <div class="pl-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Fermeture</h3>
                
                @if($session->statut == 'fermee')
                    <div class="space-y-4">
                        <div>
                            <label for="montant_verse" class="block text-sm font-medium text-gray-700 mb-2">
                                Montant Versé
                            </label>
                            <input 
                                type="number" 
                                step="0.01" 
                                id="montant_verse" 
                                name="montant_verse" 
                                value="{{ old('montant_verse', $session->montant_verse) }}"
                                class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                            >
                            @error('montant_verse')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="orange_money_final" class="block text-sm font-medium text-gray-700 mb-2">
                                Orange Money Final
                            </label>
                            <input 
                                type="number" 
                                step="0.01" 
                                id="orange_money_final" 
                                name="orange_money_final" 
                                value="{{ old('orange_money_final', $session->orange_money_final) }}"
                                class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                            >
                            @error('orange_money_final')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mtn_money_final" class="block text-sm font-medium text-gray-700 mb-2">
                                MTN Money Final
                            </label>
                            <input 
                                type="number" 
                                step="0.01" 
                                id="mtn_money_final" 
                                name="mtn_money_final" 
                                value="{{ old('mtn_money_final', $session->mtn_money_final) }}"
                                class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                            >
                            @error('mtn_money_final')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-800 text-sm">
                            Les montants de fermeture ne peuvent être modifiés que pour les sessions fermées.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('sessions.show', $session->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition">
                Annuler
            </a>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg transition font-semibold">
                Enregistrer les Modifications
            </button>
        </div>
    </form>
</div>
@endsection
