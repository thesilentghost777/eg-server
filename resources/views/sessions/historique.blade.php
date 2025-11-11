@extends('layouts.app')

@section('title', $isFrench ? 'Historique des Sessions' : 'Sessions History')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-amber-900">
                <i class="fas fa-history mr-2 sm:mr-3"></i>
                {{ $isFrench ? 'Historique Complet' : 'Complete History' }}
            </h1>
            <a href="{{ route('sessions.index') }}" 
               class="w-full sm:w-auto bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center font-semibold text-sm sm:text-base">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ $isFrench ? 'Retour' : 'Back' }}
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-4 sm:p-6 mb-6 border border-amber-200">
            <form method="GET" action="{{ route('sessions.historique') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            <i class="fas fa-filter mr-2"></i>
                            {{ $isFrench ? 'Statut' : 'Status' }}
                        </label>
                        <select name="statut" class="w-full border-amber-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 bg-white">
                            <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                            <option value="ouverte" {{ request('statut') == 'ouverte' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Ouverte' : 'Open' }}
                            </option>
                            <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Fermée' : 'Closed' }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ $isFrench ? 'Date début' : 'Start Date' }}
                        </label>
                        <input type="date" name="date_debut" value="{{ request('date_debut') }}" 
                               class="w-full border-amber-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            <i class="fas fa-calendar-check mr-2"></i>
                            {{ $isFrench ? 'Date fin' : 'End Date' }}
                        </label>
                        <input type="date" name="date_fin" value="{{ request('date_fin') }}" 
                               class="w-full border-amber-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 bg-white">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white px-4 sm:px-6 py-2.5 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 font-semibold">
                            <i class="fas fa-search mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">{{ $isFrench ? 'Filtrer' : 'Filter' }}</span>
                            <span class="sm:hidden"><i class="fas fa-search"></i></span>
                        </button>
                        <a href="{{ route('sessions.historique') }}" 
                           class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2.5 rounded-lg transition-colors font-semibold">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-blue-200 transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm text-blue-700 font-semibold mb-1">
                            {{ $isFrench ? 'Total' : 'Total' }}
                        </p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-800">{{ $sessions->total() }}</p>
                    </div>
                    <div class="text-3xl sm:text-4xl lg:text-5xl">📊</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-green-200 transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm text-green-700 font-semibold mb-1">
                            {{ $isFrench ? 'Fermées' : 'Closed' }}
                        </p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-800">{{ $sessions->where('statut', 'fermee')->count() }}</p>
                    </div>
                    <div class="text-3xl sm:text-4xl lg:text-5xl">✓</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-orange-200 transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm text-orange-700 font-semibold mb-1">
                            {{ $isFrench ? 'Ouvertes' : 'Open' }}
                        </p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-orange-800">{{ $sessions->where('statut', 'ouverte')->count() }}</p>
                    </div>
                    <div class="text-3xl sm:text-4xl lg:text-5xl">⏳</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-red-200 transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm text-red-700 font-semibold mb-1">
                            {{ $isFrench ? 'Manquants' : 'Missing' }}
                        </p>
                        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-red-800">
                            {{ $sessions->where('statut', 'fermee')->where('manquant', '>', 0)->count() }}
                        </p>
                    </div>
                    <div class="text-3xl sm:text-4xl lg:text-5xl">⚠️</div>
                </div>
            </div>
        </div>

        <!-- Tableau - Desktop -->
        <div class="hidden lg:block bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl overflow-hidden border border-amber-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-amber-200">
                    <thead class="bg-gradient-to-r from-amber-100 to-orange-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-900 uppercase">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-900 uppercase">
                                {{ $isFrench ? 'Vendeur' : 'Seller' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-900 uppercase">
                                {{ $isFrench ? 'Catégorie' : 'Category' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-900 uppercase">
                                {{ $isFrench ? 'Ouverture' : 'Opening' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-900 uppercase">
                                {{ $isFrench ? 'Fermeture' : 'Closing' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-900 uppercase">
                                {{ $isFrench ? 'Statut' : 'Status' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-900 uppercase">
                                {{ $isFrench ? 'Manquant' : 'Missing' }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-900 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-amber-100">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-amber-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-amber-900">#{{ $session->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $session->vendeur->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $session->categorie == 'boulangerie' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : 'bg-pink-100 text-pink-800 border border-pink-300' }}">
                                        {{ $isFrench ? ucfirst($session->categorie) : ($session->categorie == 'boulangerie' ? 'Bakery' : 'Pastry') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <i class="far fa-calendar mr-2 text-amber-600"></i>
                                    {{ $session->date_ouverture->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($session->date_fermeture)
                                        <i class="far fa-calendar-check mr-2 text-amber-600"></i>
                                        {{ $session->date_fermeture->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($session->statut == 'ouverte')
                                        <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-xs font-bold border border-green-300">
                                            <i class="fas fa-unlock mr-1"></i>
                                            {{ $isFrench ? 'Ouverte' : 'Open' }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-full text-xs font-bold border border-gray-300">
                                            <i class="fas fa-lock mr-1"></i>
                                            {{ $isFrench ? 'Fermée' : 'Closed' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($session->statut == 'fermee')
                                        <span class="{{ $session->manquant > 0 ? 'text-red-600 font-bold' : ($session->manquant < 0 ? 'text-green-600 font-semibold' : 'text-gray-600') }}">
                                            {{ number_format($session->manquant, 0, ',', ' ') }} FCFA
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{ route('sessions.show', $session->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ $isFrench ? 'Détails' : 'Details' }}
                                    </a>
                                    @if($session->statut == 'ouverte')
                                        <a href="{{ route('sessions.fermer.form', $session->id) }}" class="text-orange-600 hover:text-orange-900 font-semibold">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            {{ $isFrench ? 'Fermer' : 'Close' }}
                                        </a>
                                    @endif
                                    <a href="{{ route('sessions.edit', $session->id) }}" class="text-purple-600 hover:text-purple-900 font-semibold">
                                        <i class="fas fa-edit mr-1"></i>
                                        {{ $isFrench ? 'Modifier' : 'Edit' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl text-amber-300 mb-3"></i>
                                    <p class="text-lg font-semibold">{{ $isFrench ? 'Aucune session trouvée' : 'No session found' }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cards - Mobile/Tablette -->
        <div class="lg:hidden space-y-4">
            @forelse($sessions as $session)
                <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-lg border border-amber-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-100 to-orange-100 px-4 py-3 border-b border-amber-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-amber-900">#{{ $session->id }}</span>
                            @if($session->statut == 'ouverte')
                                <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-xs font-bold border border-green-300">
                                    <i class="fas fa-unlock mr-1"></i>
                                    {{ $isFrench ? 'Ouverte' : 'Open' }}
                                </span>
                            @else
                                <span class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-full text-xs font-bold border border-gray-300">
                                    <i class="fas fa-lock mr-1"></i>
                                    {{ $isFrench ? 'Fermée' : 'Closed' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-user text-amber-600 w-6"></i>
                            <span class="font-semibold text-gray-900">{{ $session->vendeur->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-tag text-amber-600 w-6"></i>
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $session->categorie == 'boulangerie' ? 'bg-yellow-100 text-yellow-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $isFrench ? ucfirst($session->categorie) : ($session->categorie == 'boulangerie' ? 'Bakery' : 'Pastry') }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-600 text-xs">{{ $isFrench ? 'Ouverture:' : 'Opening:' }}</span>
                                <p class="font-semibold text-gray-900">{{ $session->date_ouverture->format('d/m/Y H:i') }}</p>
                            </div>
                            @if($session->date_fermeture)
                                <div>
                                    <span class="text-gray-600 text-xs">{{ $isFrench ? 'Fermeture:' : 'Closing:' }}</span>
                                    <p class="font-semibold text-gray-900">{{ $session->date_fermeture->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                        @if($session->statut == 'fermee')
                            <div class="flex items-center justify-between pt-2 border-t border-amber-100">
                                <span class="text-sm font-semibold text-gray-700">{{ $isFrench ? 'Manquant:' : 'Missing:' }}</span>
                                <span class="{{ $session->manquant > 0 ? 'text-red-600 font-bold' : ($session->manquant < 0 ? 'text-green-600' : 'text-gray-600') }}">
                                    {{ number_format($session->manquant, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        @endif
                        <div class="flex flex-wrap gap-2 pt-3 border-t border-amber-100">
                            <a href="{{ route('sessions.show', $session->id) }}" 
                               class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-semibold text-xs transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                {{ $isFrench ? 'Voir' : 'View' }}
                            </a>
                            @if($session->statut == 'ouverte')
                                <a href="{{ route('sessions.fermer.form', $session->id) }}" 
                                   class="flex-1 text-center bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-lg font-semibold text-xs transition-colors">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    {{ $isFrench ? 'Fermer' : 'Close' }}
                                </a>
                            @endif
                            <a href="{{ route('sessions.edit', $session->id) }}" 
                               class="flex-1 text-center bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg font-semibold text-xs transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                {{ $isFrench ? 'Modifier' : 'Edit' }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white/90 rounded-xl shadow-lg p-8 text-center">
                    <i class="fas fa-inbox text-5xl text-amber-300 mb-4"></i>
                    <p class="text-lg font-semibold text-gray-600">{{ $isFrench ? 'Aucune session trouvée' : 'No session found' }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $sessions->links() }}
        </div>
    </div>
</div>
@endsection
