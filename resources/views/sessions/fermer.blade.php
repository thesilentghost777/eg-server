@extends('layouts.app')

@section('title', 'Fermer Session #' . $session->id)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Fermer Session #{{ $session->id }}</h1>
        <a href="{{ route('sessions.show', $session->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">
            Annuler
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Informations session -->
    <div class="bg-blue-50 rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-blue-800 mb-4">Informations de la Session</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-gray-700">Vendeur:</span>
                <span class="font-bold ml-2">{{ $session->vendeur->name }}</span>
            </div>
            <div>
                <span class="text-gray-700">Catégorie:</span>
                <span class="font-bold ml-2">{{ ucfirst($session->categorie) }}</span>
            </div>
            <div>
                <span class="text-gray-700">Fond de vente:</span>
                <span class="font-bold ml-2">{{ number_format($session->fond_vente, 0, ',', ' ') }} FCFA</span>
            </div>
            <div>
                <span class="text-gray-700">Orange Money initial:</span>
                <span class="font-bold ml-2">{{ number_format($session->orange_money_initial, 0, ',', ' ') }} FCFA</span>
            </div>
            <div>
                <span class="text-gray-700">MTN Money initial:</span>
                <span class="font-bold ml-2">{{ number_format($session->mtn_money_initial, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
    </div>

    <!-- Formulaire de fermeture -->
    <form action="{{ route('sessions.fermer', $session->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        
        <h2 class="text-xl font-bold text-gray-800 mb-6">Montants de Fermeture</h2>
        
        <div class="space-y-6">
            <div>
                <label for="montant_verse" class="block text-sm font-medium text-gray-700 mb-2">
                    Montant Versé (Espèces) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    step="0.01" 
                    id="montant_verse" 
                    name="montant_verse" 
                    value="{{ old('montant_verse') }}"
                    class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                >
                @error('montant_verse')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="orange_money_final" class="block text-sm font-medium text-gray-700 mb-2">
                    Orange Money Final <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    step="0.01" 
                    id="orange_money_final" 
                    name="orange_money_final" 
                    value="{{ old('orange_money_final', $session->orange_money_initial) }}"
                    class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                >
                @error('orange_money_final')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="mtn_money_final" class="block text-sm font-medium text-gray-700 mb-2">
                    MTN Money Final <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    step="0.01" 
                    id="mtn_money_final" 
                    name="mtn_money_final" 
                    value="{{ old('mtn_money_final', $session->mtn_money_initial) }}"
                    class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                >
                @error('mtn_money_final')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('sessions.show', $session->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition">
                Annuler
            </a>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg transition font-semibold">
                Fermer la Session
            </button>
        </div>
    </form>
</div>
@endsection
