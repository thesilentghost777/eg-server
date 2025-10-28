@extends('layouts.app')

@section('title', 'Détails Session #' . $session->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Détails Session #{{ $session->id }}</h1>
        <div class="space-x-3">
            <a href="{{ route('sessions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">
                Retour
            </a>
            @if($session->statut == 'ouverte')
                <a href="{{ route('sessions.fermer.form', $session->id) }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition">
                    Fermer la Session
                </a>
            @endif
            <a href="{{ route('sessions.edit', $session->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                Modifier
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Informations générales -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Informations Générales</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Vendeur</label>
                <p class="text-lg font-semibold text-gray-900">{{ $session->vendeur->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Catégorie</label>
                <p class="text-lg">
                    <span class="px-3 py-1 rounded-full text-sm {{ $session->categorie == 'boulangerie' ? 'bg-yellow-100 text-yellow-800' : 'bg-pink-100 text-pink-800' }}">
                        {{ ucfirst($session->categorie) }}
                    </span>
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Date d'ouverture</label>
                <p class="text-lg font-semibold text-gray-900">{{ $session->date_ouverture->format('d/m/Y à H:i') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Statut</label>
                <p class="text-lg">
                    @if($session->statut == 'ouverte')
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Ouverte</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Fermée</span>
                    @endif
                </p>
            </div>
            @if($session->statut == 'fermee')
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Date de fermeture</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $session->date_fermeture->format('d/m/Y à H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Fermée par</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $session->fermeePar->name ?? 'N/A' }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Montants -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Ouverture -->
        <div class="bg-blue-50 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-blue-800 mb-4">Montants d'Ouverture</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Fond de vente:</span>
                    <span class="font-bold text-lg">{{ number_format($session->fond_vente, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Orange Money:</span>
                    <span class="font-bold text-lg">{{ number_format($session->orange_money_initial, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">MTN Money:</span>
                    <span class="font-bold text-lg">{{ number_format($session->mtn_money_initial, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="border-t pt-3 flex justify-between items-center">
                    <span class="font-bold text-gray-700">Total Ouverture:</span>
                    <span class="font-bold text-xl text-blue-700">
                        {{ number_format($session->fond_vente + $session->orange_money_initial + $session->mtn_money_initial, 0, ',', ' ') }} FCFA
                    </span>
                </div>
            </div>
        </div>

        <!-- Fermeture -->
        @if($session->statut == 'fermee')
            <div class="bg-green-50 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-green-800 mb-4">Montants de Fermeture</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Montant versé (espèces):</span>
                        <span class="font-bold text-lg">{{ number_format($session->montant_verse, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Orange Money:</span>
                        <span class="font-bold text-lg">{{ number_format($session->orange_money_final, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">MTN Money:</span>
                        <span class="font-bold text-lg">{{ number_format($session->mtn_money_final, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between items-center">
                        <span class="font-bold text-gray-700">Total Versé:</span>
                        <span class="font-bold text-xl text-green-700">
                            {{ number_format($session->montant_verse + $session->orange_money_final + $session->mtn_money_final, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Résultat Final -->
    @if($session->statut == 'fermee')
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Résultat Final</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-lg">
                    <span class="text-gray-700">Différence Orange Money:</span>
                    <span class="font-bold">
                        {{ number_format($session->orange_money_final - $session->orange_money_initial, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                <div class="flex justify-between items-center text-lg">
                    <span class="text-gray-700">Différence MTN Money:</span>
                    <span class="font-bold">
                        {{ number_format($session->mtn_money_final - $session->mtn_money_initial, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                <div class="border-t border-b py-4 flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-800">Manquant / Excédent:</span>
                    <span class="text-2xl font-bold {{ $session->manquant > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $session->manquant > 0 ? '-' : '+' }}{{ number_format(abs($session->manquant), 0, ',', ' ') }} FCFA
                    </span>
                </div>
                @if($session->manquant > 0)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-800 font-medium">⚠️ Manquant détecté dans la caisse</p>
                    </div>
                @elseif($session->manquant < 0)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-green-800 font-medium">✓ Excédent dans la caisse</p>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-blue-800 font-medium">✓ Caisse équilibrée</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
