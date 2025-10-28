@extends('layouts.app')

@section('title', 'Historique des Sessions')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Historique Complet des Sessions</h1>
        <a href="{{ route('sessions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition">
            Retour
        </a>
    </div>

    <!-- Filtres avancés -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('sessions.historique') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="statut" class="w-full border-gray-300 rounded-lg">
                    <option value="">Tous</option>
                    <option value="ouverte" {{ request('statut') == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                    <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>Fermée</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full border-gray-300 rounded-lg">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex-1 transition">
                    Filtrer
                </button>
                <a href="{{ route('sessions.historique') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-blue-50 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Sessions</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $sessions->total() }}</p>
                </div>
                <div class="text-blue-500 text-3xl">📊</div>
            </div>
        </div>
        
        <div class="bg-green-50 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Fermées</p>
                    <p class="text-2xl font-bold text-green-700">{{ $sessions->where('statut', 'fermee')->count() }}</p>
                </div>
                <div class="text-green-500 text-3xl">✓</div>
            </div>
        </div>
        
        <div class="bg-orange-50 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Ouvertes</p>
                    <p class="text-2xl font-bold text-orange-700">{{ $sessions->where('statut', 'ouverte')->count() }}</p>
                </div>
                <div class="text-orange-500 text-3xl">⏳</div>
            </div>
        </div>
        
        <div class="bg-red-50 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Manquants</p>
                    <p class="text-2xl font-bold text-red-700">
                        {{ $sessions->where('statut', 'fermee')->where('manquant', '>', 0)->count() }}
                    </p>
                </div>
                <div class="text-red-500 text-3xl">⚠️</div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendeur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ouverture</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fermeture</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manquant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sessions as $session)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $session->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $session->vendeur->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 rounded-full text-xs {{ $session->categorie == 'boulangerie' ? 'bg-yellow-100 text-yellow-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($session->categorie) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $session->date_ouverture->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $session->date_fermeture ? $session->date_fermeture->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($session->statut == 'ouverte')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Ouverte</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">Fermée</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($session->statut == 'fermee')
                                <span class="{{ $session->manquant > 0 ? 'text-red-600 font-bold' : ($session->manquant < 0 ? 'text-green-600' : 'text-gray-600') }}">
                                    {{ number_format($session->manquant, 0, ',', ' ') }} FCFA
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('sessions.show', $session->id) }}" class="text-blue-600 hover:text-blue-900">Détails</a>
                            @if($session->statut == 'ouverte')
                                <a href="{{ route('sessions.fermer.form', $session->id) }}" class="text-orange-600 hover:text-orange-900">Fermer</a>
                            @endif
                            <a href="{{ route('sessions.edit', $session->id) }}" class="text-purple-600 hover:text-purple-900">Modifier</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">Aucune session trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
