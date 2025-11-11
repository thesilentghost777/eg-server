@extends('layouts.app')

@section('title', $isFrench ? 'Sessions de Vente Détaillées' : 'Detailed Sales Sessions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-amber-700 via-yellow-700 to-orange-700 rounded-2xl shadow-2xl overflow-hidden mb-6">
            <div class="px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                        <i class="fas fa-list-alt text-2xl sm:text-3xl text-white"></i>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
                        {{ $isFrench ? 'Sessions de Vente Détaillées' : 'Detailed Sales Sessions' }}
                    </h1>
                </div>
            </div>
        </div>

        <!-- Formulaire de filtres -->
        <div class="bg-white rounded-2xl shadow-xl mb-6 overflow-hidden border border-amber-100">
            <div class="bg-gradient-to-r from-amber-600 to-yellow-600 px-4 sm:px-6 py-4">
                <h2 class="text-lg sm:text-xl font-semibold text-white flex items-center gap-2">
                    <i class="fas fa-filter"></i>
                    {{ $isFrench ? 'Filtres' : 'Filters' }}
                </h2>
            </div>
            
            <form method="GET" action="{{ route('pdg.sessions.detaillees') }}" class="p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- Vendeur -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            {{ $isFrench ? 'Vendeur' : 'Seller' }}
                        </label>
                        <select name="vendeur_id" class="w-full rounded-xl border-2 border-amber-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-200 transition-all px-4 py-2.5 text-gray-700">
                            <option value="">{{ $isFrench ? 'Tous les vendeurs' : 'All sellers' }}</option>
                            @foreach(\App\Models\User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])->where('actif', true)->get() as $vendeur)
                                <option value="{{ $vendeur->id }}" {{ request('vendeur_id') == $vendeur->id ? 'selected' : '' }}>
                                    {{ $vendeur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Catégorie -->
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            {{ $isFrench ? 'Catégorie' : 'Category' }}
                        </label>
                        <select name="categorie" class="w-full rounded-xl border-2 border-amber-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-200 transition-all px-4 py-2.5 text-gray-700">
                            <option value="">{{ $isFrench ? 'Toutes' : 'All' }}</option>
                            <option value="boulangerie" {{ request('categorie') == 'boulangerie' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Boulangerie' : 'Bakery' }}
                            </option>
                            <option value="patisserie" {{ request('categorie') == 'patisserie' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Pâtisserie' : 'Pastry' }}
                            </option>
                        </select>
                    </div>
                    
                    <!-- Statut -->
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            {{ $isFrench ? 'Statut' : 'Status' }}
                        </label>
                        <select name="statut" class="w-full rounded-xl border-2 border-amber-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-200 transition-all px-4 py-2.5 text-gray-700">
                            <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                            <option value="ouverte" {{ request('statut') == 'ouverte' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Ouverte' : 'Open' }}
                            </option>
                            <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Fermée' : 'Closed' }}
                            </option>
                        </select>
                    </div>
                    
                    <!-- Date début -->
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            {{ $isFrench ? 'Date début' : 'Start date' }}
                        </label>
                        <input type="date" name="date_debut" value="{{ request('date_debut') }}" 
                            class="w-full rounded-xl border-2 border-amber-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-200 transition-all px-4 py-2.5 text-gray-700">
                    </div>
                    
                    <!-- Date fin -->
                    <div>
                        <label class="block text-sm font-semibold text-amber-900 mb-2">
                            {{ $isFrench ? 'Date fin' : 'End date' }}
                        </label>
                        <input type="date" name="date_fin" value="{{ request('date_fin') }}" 
                            class="w-full rounded-xl border-2 border-amber-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-200 transition-all px-4 py-2.5 text-gray-700">
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="submit" class="bg-gradient-to-r from-amber-600 to-yellow-600 hover:from-amber-700 hover:to-yellow-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i>
                        {{ $isFrench ? 'Appliquer les filtres' : 'Apply filters' }}
                    </button>
                    
                    @if(request()->hasAny(['vendeur_id', 'categorie', 'statut', 'date_debut', 'date_fin']))
                        <a href="{{ route('pdg.sessions.detaillees') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-xl shadow hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            {{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 rounded-xl shadow-lg mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded-xl shadow-lg mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Statistiques -->
        @php
            $sessionsCollection = is_array($sessions) ? collect($sessions) : $sessions;
            $totalSessions = $sessionsCollection->count();
            $sessionsFermees = $sessionsCollection->where('statut', 'fermee')->count();
            $sessionsOuvertes = $sessionsCollection->where('statut', 'ouverte')->count();
            $totalVerse = $sessionsCollection->where('statut', 'fermee')->sum(function($s) {
                return is_array($s) ? ($s['montant_verse'] ?? 0) : ($s->montant_verse ?? 0);
            });
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">
                            {{ $isFrench ? 'Total Sessions' : 'Total Sessions' }}
                        </p>
                        <p class="text-3xl sm:text-4xl font-bold">{{ $totalSessions }}</p>
                    </div>
                    <div class="bg-white/20 p-4 rounded-xl">
                        <i class="fas fa-clipboard-list text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">
                            {{ $isFrench ? 'Sessions Fermées' : 'Closed Sessions' }}
                        </p>
                        <p class="text-3xl sm:text-4xl font-bold">{{ $sessionsFermees }}</p>
                    </div>
                    <div class="bg-white/20 p-4 rounded-xl">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 text-sm font-medium mb-1">
                            {{ $isFrench ? 'Sessions Ouvertes' : 'Open Sessions' }}
                        </p>
                        <p class="text-3xl sm:text-4xl font-bold">{{ $sessionsOuvertes }}</p>
                    </div>
                    <div class="bg-white/20 p-4 rounded-xl">
                        <i class="fas fa-door-open text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium mb-1">
                            {{ $isFrench ? 'Total Versé' : 'Total Paid' }}
                        </p>
                        <p class="text-2xl sm:text-3xl font-bold">{{ number_format($totalVerse, 0, ',', ' ') }} <span class="text-lg">FCFA</span></p>
                    </div>
                    <div class="bg-white/20 p-4 rounded-xl">
                        <i class="fas fa-coins text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-amber-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-amber-600 to-yellow-600 text-white">
                        <tr>
                            <th class="px-3 sm:px-6 py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">ID</th>
                            <th class="px-3 sm:px-6 py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Vendeur' : 'Seller' }}</th>
                            <th class="px-3 sm:px-6 py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider hidden lg:table-cell">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                            <th class="px-3 sm:px-6 py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider hidden md:table-cell">{{ $isFrench ? 'Ouverture' : 'Opening' }}</th>
                            <th class="px-3 sm:px-6 py-4 text-left text-xs sm:text-sm font-semibold uppercase tracking-wider hidden xl:table-cell">{{ $isFrench ? 'Fermeture' : 'Closing' }}</th>
                            <th class="px-3 sm:px-6 py-4 text-right text-xs sm:text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Ventes' : 'Sales' }}</th>
                            <th class="px-3 sm:px-6 py-4 text-right text-xs sm:text-sm font-semibold uppercase tracking-wider hidden lg:table-cell">{{ $isFrench ? 'Versé' : 'Paid' }}</th>
                            <th class="px-3 sm:px-6 py-4 text-center text-xs sm:text-sm font-semibold uppercase tracking-wider">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                            <th class="px-3 sm:px-6 py-4 text-center text-xs sm:text-sm font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-100">
                        @forelse($sessions as $index => $session)
                            @php
                                // Gérer à la fois les objets et les arrays
                                $sessionData = is_array($session) ? $session : (array) $session;
                                $sessionId = $sessionData['id'] ?? null;
                                $vendeur = $sessionData['vendeur'] ?? null;
                                $vendeurData = is_array($vendeur) ? $vendeur : (is_object($vendeur) ? (array) $vendeur : []);
                                $categorie = $sessionData['categorie'] ?? '';
                                $statut = $sessionData['statut'] ?? '';
                                $dateOuverture = $sessionData['date_ouverture'] ?? null;
                                $dateFermeture = $sessionData['date_fermeture'] ?? null;
                                $totalVentes = $sessionData['total_ventes'] ?? 0;
                                $nombreVentes = $sessionData['nombre_ventes'] ?? 0;
                                $montantVerse = $sessionData['montant_verse'] ?? null;
                                $ventes = $sessionData['ventes'] ?? [];
                                if (is_object($ventes)) {
                                    $ventes = $ventes->toArray();
                                }
                            @endphp
                            
                            <tr class="hover:bg-amber-50 transition-colors duration-200">
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs sm:text-sm font-bold text-amber-700">#{{ $sessionId }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-4">
                                    <div class="flex items-center gap-2 sm:gap-3">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                                            {{ strtoupper(substr($vendeurData['name'] ?? 'N/A', 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs sm:text-sm font-semibold text-gray-900 truncate">{{ $vendeurData['name'] ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $vendeurData['numero_telephone'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $categorie == 'boulangerie' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $isFrench ? ucfirst($categorie) : ($categorie == 'boulangerie' ? 'Bakery' : 'Pastry') }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 hidden md:table-cell">
                                    {{ $dateOuverture ? \Carbon\Carbon::parse($dateOuverture)->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="px-3 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 hidden xl:table-cell">
                                    @if($dateFermeture)
                                        {{ \Carbon\Carbon::parse($dateFermeture)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right">
                                    <p class="text-xs sm:text-sm font-bold text-green-600">{{ number_format($totalVentes, 0, ',', ' ') }} FCFA</p>
                                    <p class="text-xs text-gray-500">({{ $nombreVentes }} {{ $isFrench ? 'ventes' : 'sales' }})</p>
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right hidden lg:table-cell">
                                    @if($montantVerse)
                                        <span class="text-xs sm:text-sm font-semibold text-gray-900">{{ number_format($montantVerse, 0, ',', ' ') }} FCFA</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                    @if($statut == 'ouverte')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 animate-pulse">
                                            <i class="fas fa-circle text-green-500 mr-1 text-[8px]"></i>
                                            {{ $isFrench ? 'Ouverte' : 'Open' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            <i class="fas fa-check mr-1"></i>
                                            {{ $isFrench ? 'Fermée' : 'Closed' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                    <button onclick="openModal{{ $sessionId }}()" 
                                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-300 inline-flex items-center gap-2">
                                        <i class="fas fa-eye"></i>
                                        <span class="hidden sm:inline">{{ $isFrench ? 'Détails' : 'Details' }}</span>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal -->
                            <div id="modal{{ $sessionId }}" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden overflow-y-auto" onclick="if(event.target === this) closeModal{{ $sessionId }}()">
                                <div class="flex items-center justify-center min-h-screen p-4">
                                    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full mx-auto overflow-hidden" onclick="event.stopPropagation()">
                                        <div class="bg-gradient-to-r from-amber-600 to-yellow-600 px-6 py-5 flex items-center justify-between">
                                            <h3 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2">
                                                <i class="fas fa-file-invoice"></i>
                                                {{ $isFrench ? 'Détails Session' : 'Session Details' }} #{{ $sessionId }}
                                            </h3>
                                            <button onclick="closeModal{{ $sessionId }}()" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                                                <i class="fas fa-times text-xl"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="p-6 max-h-[70vh] overflow-y-auto">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                                <div class="bg-amber-50 rounded-xl p-5 border-2 border-amber-200">
                                                    <h4 class="text-lg font-bold text-amber-900 mb-4 flex items-center gap-2 border-b-2 border-amber-300 pb-2">
                                                        <i class="fas fa-info-circle"></i>
                                                        {{ $isFrench ? 'Informations Générales' : 'General Information' }}
                                                    </h4>
                                                    <div class="space-y-3 text-sm">
                                                        <div class="flex justify-between">
                                                            <span class="font-semibold text-gray-700">{{ $isFrench ? 'Vendeur:' : 'Seller:' }}</span>
                                                            <span class="text-gray-900">{{ $vendeurData['name'] ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="font-semibold text-gray-700">{{ $isFrench ? 'Catégorie:' : 'Category:' }}</span>
                                                            <span class="px-2 py-1 rounded-lg {{ $categorie == 'boulangerie' ? 'bg-amber-200 text-amber-900' : 'bg-blue-200 text-blue-900' }}">
                                                                {{ $isFrench ? ucfirst($categorie) : ($categorie == 'boulangerie' ? 'Bakery' : 'Pastry') }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="font-semibold text-gray-700">{{ $isFrench ? 'Ouverture:' : 'Opening:' }}</span>
                                                            <span class="text-gray-900">{{ $dateOuverture ? \Carbon\Carbon::parse($dateOuverture)->format('d/m/Y H:i:s') : '-' }}</span>
                                                        </div>
                                                        @if($dateFermeture)
                                                            <div class="flex justify-between">
                                                                <span class="font-semibold text-gray-700">{{ $isFrench ? 'Fermeture:' : 'Closing:' }}</span>
                                                                <span class="text-gray-900">{{ \Carbon\Carbon::parse($dateFermeture)->format('d/m/Y H:i:s') }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="bg-green-50 rounded-xl p-5 border-2 border-green-200">
                                                    <h4 class="text-lg font-bold text-green-900 mb-4 flex items-center gap-2 border-b-2 border-green-300 pb-2">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                        {{ $isFrench ? 'Montants' : 'Amounts' }}
                                                    </h4>
                                                    <div class="space-y-3 text-sm">
                                                        <div class="flex justify-between">
                                                            <span class="font-semibold text-gray-700">{{ $isFrench ? 'Fond de vente:' : 'Sales fund:' }}</span>
                                                            <span class="text-gray-900 font-bold">{{ number_format($sessionData['fond_vente'] ?? 0, 0, ',', ' ') }} FCFA</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="font-semibold text-gray-700">Orange Money:</span>
                                                            <span class="text-gray-900">{{ number_format($sessionData['orange_money_initial'] ?? 0, 0, ',', ' ') }} FCFA</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="font-semibold text-gray-700">MTN Money:</span>
                                                            <span class="text-gray-900">{{ number_format($sessionData['mtn_money_initial'] ?? 0, 0, ',', ' ') }} FCFA</span>
                                                        </div>
                                                        @if($statut == 'fermee')
                                                            <hr class="border-green-300">
                                                            <div class="flex justify-between">
                                                                <span class="font-semibold text-gray-700">{{ $isFrench ? 'Montant versé:' : 'Amount paid:' }}</span>
                                                                <span class="text-green-700 font-bold">{{ number_format($montantVerse ?? 0, 0, ',', ' ') }} FCFA</span>
                                                            </div>
                                                            <div class="flex justify-between items-center">
                                                                <span class="font-semibold text-gray-700">{{ $isFrench ? 'Manquant:' : 'Missing:' }}</span>
                                                                <span class="px-3 py-1 rounded-lg font-bold {{ ($sessionData['manquant'] ?? 0) == 0 ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                                                    {{ number_format($sessionData['manquant'] ?? 0, 0, ',', ' ') }} FCFA
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            @if(is_array($ventes) && count($ventes) > 0)
                                                <div class="bg-blue-50 rounded-xl p-5 border-2 border-blue-200">
                                                    <h4 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                                                        <i class="fas fa-shopping-cart"></i>
                                                        {{ $isFrench ? 'Liste des Ventes' : 'Sales List' }}
                                                    </h4>
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-sm">
                                                            <thead class="bg-blue-200 text-blue-900">
                                                                <tr>
                                                                    <th class="px-3 py-2 text-left font-semibold">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                                                    <th class="px-3 py-2 text-center font-semibold">{{ $isFrench ? 'Qté' : 'Qty' }}</th>
                                                                    <th class="px-3 py-2 text-right font-semibold">{{ $isFrench ? 'Prix U.' : 'Unit P.' }}</th>
                                                                    <th class="px-3 py-2 text-right font-semibold">Total</th>
                                                                    <th class="px-3 py-2 text-center font-semibold">Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-blue-200">
                                                                @php
                                                                    $totalVentesModal = 0;
                                                                @endphp
                                                                @foreach($ventes as $vente)
                                                                    @php
                                                                        $venteData = is_array($vente) ? $vente : (array) $vente;
                                                                        $produit = $venteData['produit'] ?? null;
                                                                        $produitData = is_array($produit) ? $produit : (is_object($produit) ? (array) $produit : []);
                                                                        $montantVente = $venteData['montant_total'] ?? 0;
                                                                        $totalVentesModal += $montantVente;
                                                                    @endphp
                                                                    <tr class="hover:bg-blue-100 transition-colors">
                                                                        <td class="px-3 py-2 font-medium text-gray-900">{{ $produitData['nom'] ?? 'N/A' }}</td>
                                                                        <td class="px-3 py-2 text-center text-gray-700">{{ $venteData['quantite'] ?? 0 }}</td>
                                                                        <td class="px-3 py-2 text-right text-gray-700">{{ number_format($venteData['prix_unitaire'] ?? 0, 0, ',', ' ') }}</td>
                                                                        <td class="px-3 py-2 text-right font-bold text-green-700">{{ number_format($montantVente, 0, ',', ' ') }}</td>
                                                                        <td class="px-3 py-2 text-center text-gray-600 text-xs">{{ isset($venteData['created_at']) ? \Carbon\Carbon::parse($venteData['created_at'])->format('d/m/Y H:i') : '-' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr class="bg-green-200 font-bold">
                                                                    <td colspan="3" class="px-3 py-3 text-right text-green-900 uppercase">{{ $isFrench ? 'Total:' : 'Total:' }}</td>
                                                                    <td colspan="2" class="px-3 py-3 text-right text-green-900 text-lg">
                                                                        {{ number_format($totalVentesModal, 0, ',', ' ') }} FCFA
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-6 flex items-center gap-3">
                                                    <i class="fas fa-info-circle text-3xl text-yellow-600"></i>
                                                    <span class="text-yellow-800 font-medium">
                                                        {{ $isFrench ? 'Aucune vente enregistrée pour cette session' : 'No sales recorded for this session' }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="bg-gray-50 px-6 py-4 flex justify-end">
                                            <button onclick="closeModal{{ $sessionId }}()" 
                                                class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                                                <i class="fas fa-times"></i>
                                                {{ $isFrench ? 'Fermer' : 'Close' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function openModal{{ $sessionId }}() {
                                    document.getElementById('modal{{ $sessionId }}').classList.remove('hidden');
                                    document.body.style.overflow = 'hidden';
                                }
                                
                                function closeModal{{ $sessionId }}() {
                                    document.getElementById('modal{{ $sessionId }}').classList.add('hidden');
                                    document.body.style.overflow = 'auto';
                                }
                            </script>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-inbox text-4xl text-amber-400"></i>
                                        </div>
                                        <p class="text-xl font-semibold text-gray-600">
                                            {{ $isFrench ? 'Aucune session trouvée' : 'No sessions found' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $isFrench ? 'Essayez de modifier vos filtres de recherche' : 'Try adjusting your search filters' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination si nécessaire -->
        @if(is_object($sessions) && method_exists($sessions, 'links'))
            <div class="mt-6">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection