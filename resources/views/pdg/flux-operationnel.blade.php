@extends('layouts.app')

@section('title', $isFrench ? 'Flux Opérationnel' : 'Operational Flow')

@section('styles')
<style>
    .bread-gradient {
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
    }
    
    @media print {
        .no-print { display: none !important; }
        .print-full-width { width: 100% !important; }
        body { font-size: 10pt; }
    }
    
    /* Optimisation pour grands volumes - Vue compacte */
    .compact-view .produit-card {
        padding: 0.75rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    .compact-view .metric {
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
    }
    
    /* Vue tableau pour grands volumes */
    .table-view {
        max-height: 600px;
        overflow-y: auto;
    }
    
    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
    }
    
    /* Mise en surbrillance des valeurs importantes */
    .highlight-value {
        background-color: #fef3c7;
        font-weight: bold;
    }
    
    /* Indicateur de performance */
    .perf-good { color: #10b981; }
    .perf-warning { color: #f59e0b; }
    .perf-danger { color: #ef4444; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 sm:p-8 mb-6 no-print">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                        <i class="fas fa-stream mr-3"></i>
                        {{ $isFrench ? 'Flux Opérationnel' : 'Operational Flow' }}
                    </h1>
                    <p class="text-amber-50 text-sm sm:text-base">
                        {{ $isFrench ? 'Suivi des opérations -' : 'Operations tracking -' }} {{ $selectedDate ?? '' }}
                    </p>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button onclick="showCardView()" class="px-4 py-2 bg-white text-amber-700 rounded-lg hover:bg-amber-50 transition-all">
                        <i class="fas fa-th-large mr-2"></i>
                        <span>{{ $isFrench ? 'Vue Cartes' : 'Card View' }}</span>
                    </button>
                    <button onclick="showTableView()" class="px-4 py-2 bg-white text-amber-700 rounded-lg hover:bg-amber-50 transition-all">
                        <i class="fas fa-th-list mr-2"></i>
                        <span>{{ $isFrench ? 'Vue Tableau' : 'Table View' }}</span>
                    </button>
                    <button onclick="showSummaryView()" class="px-4 py-2 bg-white text-amber-700 rounded-lg hover:bg-amber-50 transition-all">
                        <i class="fas fa-calculator mr-2"></i>
                        <span>{{ $isFrench ? 'Vue Résumée' : 'Summary View' }}</span>
                    </button>
                    <a href="{{ route('pdg.flux.imprimer', request()->all()) }}" target="_blank"
                       class="px-4 py-2 bg-white text-amber-700 rounded-lg hover:bg-amber-50 transition-all shadow-md">
                        <i class="fas fa-print mr-2"></i>{{ $isFrench ? 'Imprimer' : 'Print' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 no-print">
            <form method="GET" action="{{ route('pdg.flux') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1 text-amber-600"></i>
                            {{ $isFrench ? 'Date' : 'Date' }}
                        </label>
                        <input type="date" name="date" value="{{ $selectedDate }}" required
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-amber-500">
                    </div>

                    <!-- Vendeur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1 text-blue-600"></i>
                            {{ $isFrench ? 'Vendeur' : 'Seller' }}
                        </label>
                        <select name="vendeur_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500">
                            <option value="">{{ $isFrench ? 'Tous les vendeurs' : 'All sellers' }}</option>
                            @foreach($vendeurs ?? [] as $vendeur)
                                <option value="{{ $vendeur->id }}" {{ $selectedVendeur == $vendeur->id ? 'selected' : '' }}>
                                    {{ $vendeur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Produit -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-box mr-1 text-green-600"></i>
                            {{ $isFrench ? 'Produit' : 'Product' }}
                        </label>
                        <select name="produit_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500">
                            <option value="">{{ $isFrench ? 'Tous les produits' : 'All products' }}</option>
                            @foreach($produits ?? [] as $produit)
                                <option value="{{ $produit->id }}" {{ $selectedProduit == $produit->id ? 'selected' : '' }}>
                                    {{ $produit->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Raccourcis -->
                    <div class="flex items-end space-x-2">
                        <button type="button" onclick="setToday()" 
                                class="flex-1 px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
                            <i class="fas fa-calendar-day mr-1"></i>
                            {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                        </button>
                        <button type="button" onclick="setYesterday()" 
                                class="flex-1 px-3 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600">
                            <i class="fas fa-calendar-minus mr-1"></i>
                            {{ $isFrench ? 'Hier' : 'Yesterday' }}
                        </button>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 shadow-md">
                        <i class="fas fa-search mr-2"></i>{{ $isFrench ? 'Rechercher' : 'Search' }}
                    </button>
                    <a href="{{ route('pdg.flux') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 shadow-md">
                        <i class="fas fa-redo mr-2"></i>{{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Résumé Global -->
        @if(isset($flux['resume']))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-users text-3xl text-green-500"></i>
                    <span class="text-3xl font-bold text-gray-800">{{ count($flux['flux'] ?? []) }}</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-600">{{ $isFrench ? 'Vendeurs Actifs' : 'Active Sellers' }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-shopping-cart text-3xl text-blue-500"></i>
                    <span class="text-2xl font-bold text-gray-800">{{ number_format($flux['resume']['total_ventes'] ?? 0, 0, ',', ' ') }}</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-600">{{ $isFrench ? 'Valeur Totale (FCFA)' : 'Total Value (FCFA)' }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-boxes text-3xl text-purple-500"></i>
                    <span class="text-3xl font-bold text-gray-800">{{ $flux['resume']['total_produits'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-600">{{ $isFrench ? 'Produits Vendus' : 'Products Sold' }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-truck text-3xl text-orange-500"></i>
                    <span class="text-3xl font-bold text-gray-800">{{ $flux['resume']['total_receptions'] ?? 0 }}</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-600">{{ $isFrench ? 'Réceptions Jour' : 'Daily Receptions' }}</h3>
            </div>
        </div>
        @endif

        <!-- Vue Cartes (par défaut) -->
        <div id="cardView" class="space-y-6">
            @forelse($flux['flux'] ?? [] as $fluxVendeur)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <!-- En-tête Vendeur -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-amber-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ $fluxVendeur['vendeur']['nom'] }}</h2>
                            <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $fluxVendeur['vendeur']['role'])) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $isFrench ? 'Valeur Totale' : 'Total Value' }}</p>
                        <p class="text-2xl font-bold text-amber-600">{{ number_format($fluxVendeur['total_ventes'] ?? 0, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>

                <!-- Produits -->
                <div class="space-y-3">
                    @forelse($fluxVendeur['produits'] ?? [] as $produitFlux)
                    <div class="produit-card bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-bold text-gray-800 text-lg">{{ $produitFlux['produit_nom'] ?? $produitFlux['produit'] ?? 'Produit' }}</h4>
                                <p class="text-sm text-gray-600">Prix unitaire: {{ number_format($produitFlux['prix_unitaire'] ?? 0, 0, ',', ' ') }} FCFA</p>
                            </div>
                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                {{ $produitFlux['quantite_vendue'] ?? 0 }} {{ $isFrench ? 'vendus' : 'sold' }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            <!-- Réception du jour -->
                            <div class="metric bg-blue-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">
                                    <i class="fas fa-truck text-blue-600 mr-1"></i>
                                    {{ $isFrench ? 'Réception' : 'Reception' }}
                                </p>
                                <p class="text-xl font-bold text-blue-600">{{ $produitFlux['quantite_recue'] ?? 0 }}</p>
                            </div>

                            <!-- Stock Initial -->
                            <div class="metric bg-purple-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">
                                    <i class="fas fa-box-open text-purple-600 mr-1"></i>
                                    {{ $isFrench ? 'Stock Initial' : 'Initial Stock' }}
                                </p>
                                <p class="text-xl font-bold text-purple-600">{{ $produitFlux['quantite_trouvee'] ?? 0 }}</p>
                            </div>

                            <!-- Vendus -->
                            <div class="metric bg-green-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">
                                    <i class="fas fa-shopping-cart text-green-600 mr-1"></i>
                                    {{ $isFrench ? 'Vendus' : 'Sold' }}
                                </p>
                                <p class="text-xl font-bold text-green-600">{{ $produitFlux['quantite_vendue'] ?? 0 }}</p>
                            </div>

                            <!-- Retours -->
                            <div class="metric bg-orange-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">
                                    <i class="fas fa-undo text-orange-600 mr-1"></i>
                                    {{ $isFrench ? 'Retours' : 'Returns' }}
                                </p>
                                <p class="text-xl font-bold text-orange-600">{{ $produitFlux['quantite_retour'] ?? 0 }}</p>
                            </div>

                            <!-- Stock Final -->
                            <div class="metric bg-gray-100 p-3 rounded-lg">
                                <p class="text-xs text-gray-600 mb-1">
                                    <i class="fas fa-boxes text-gray-600 mr-1"></i>
                                    {{ $isFrench ? 'Stock Final' : 'Final Stock' }}
                                </p>
                                <p class="text-xl font-bold text-gray-700">{{ $produitFlux['quantite_restante'] ?? 0 }}</p>
                            </div>
                        </div>

                          
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">{{ $isFrench ? 'Aucun produit' : 'No products' }}</p>
                    @endforelse
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">{{ $isFrench ? 'Aucune activité pour cette période' : 'No activity for this period' }}</p>
            </div>
            @endforelse
        </div>

        <!-- Vue Tableau -->
        <div id="tableView" class="hidden bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="table-view">
                <table class="w-full text-sm">
                    <thead class="sticky-header text-blue-600">
                        <tr>
                            <th class="px-3 py-2 text-left">{{ $isFrench ? 'Vendeur' : 'Seller' }}</th>
                            <th class="px-3 py-2 text-left">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                            <th class="px-3 py-2 text-center">{{ $isFrench ? 'Réception' : 'Reception' }}</th>
                            <th class="px-3 py-2 text-center">{{ $isFrench ? 'Stock Ini.' : 'Init. Stock' }}</th>
                            <th class="px-3 py-2 text-center">{{ $isFrench ? 'Vendus' : 'Sold' }}</th>
                            <th class="px-3 py-2 text-center">{{ $isFrench ? 'Retours' : 'Returns' }}</th>
                            <th class="px-3 py-2 text-center">{{ $isFrench ? 'Stock Fin.' : 'Final Stock' }}</th>
                            <th class="px-3 py-2 text-right">{{ $isFrench ? 'Valeur' : 'Value' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($flux['flux'] ?? [] as $fluxVendeur)
                            @foreach($fluxVendeur['produits'] ?? [] as $produitFlux)
                            @php
                                $total_dispo = ($produitFlux['quantite_recue'] ?? 0) + ($produitFlux['quantite_trouvee'] ?? 0);
                                $taux = $total_dispo > 0 ? (($produitFlux['quantite_vendue'] ?? 0) / $total_dispo) * 100 : 0;
                            @endphp
                            <tr class="hover:bg-amber-50">
                                <td class="px-3 py-2">{{ $fluxVendeur['vendeur']['nom'] }}</td>
                                <td class="px-3 py-2 font-semibold">{{ $produitFlux['produit_nom'] ?? '' }}-{{ $produitFlux['prix_unitaire'] ?? '' }}</td>
                                <td class="px-3 py-2 text-center font-bold text-blue-600">{{ $produitFlux['quantite_recue'] ?? 0 }}</td>
                                <td class="px-3 py-2 text-center">{{ $produitFlux['quantite_trouvee'] ?? 0 }}</td>
                                <td class="px-3 py-2 text-center font-bold text-green-600">{{ $produitFlux['quantite_vendue'] ?? 0 }}</td>
                                <td class="px-3 py-2 text-center text-orange-600">{{ $produitFlux['quantite_retour'] ?? 0 }}</td>
                                <td class="px-3 py-2 text-center">{{ $produitFlux['quantite_restante'] ?? 0 }}</td>
                                <td class="px-3 py-2 text-right font-bold">{{ number_format($produitFlux['valeur_vente'] ?? 0, 0, ',', ' ') }}</td>
                                
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vue Résumée CORRIGÉE -->
        <!-- Vue Résumée avec DEBUG -->
<div id="summaryView" class="hidden">
    @php
        // ========================================
        // SYSTÈME DE DEBUG COMPLET
        // ========================================
        $totalVenduMethode1 = 0;      // Calcul comme getFluxParVendeur
        $totalVenduMethode2 = 0;      // Calcul actuel de la vue
        
        $valeurReceptions = 0;
        $valeurRetours = 0;
        $valeurArrivee = 0;
        $valeurSortie = 0;
        
        $totalStockInitialQte = 0;
        $totalStockFinalQte = 0;
        $totalReceptionsQte = 0;
        $totalRetoursQte = 0;
        
        $debug = [];
        $produitsAnalyses = 0;
        
        foreach($flux['flux'] ?? [] as $fluxVendeur) {
            foreach($fluxVendeur['produits'] ?? [] as $produitFlux) {
                $produitsAnalyses++;
                
                $quantiteRecue = $produitFlux['quantite_recue'] ?? 0;
                $quantiteRetour = $produitFlux['quantite_retour'] ?? 0;
                $quantiteTrouvee = $produitFlux['quantite_trouvee'] ?? 0;
                $quantiteRestante = $produitFlux['quantite_restante'] ?? 0;
                $prixUnitaire = $produitFlux['prix_unitaire'] ?? 0;
                $quantiteVendue = $produitFlux['quantite_vendue'] ?? 0;
                $valeurVente = $produitFlux['valeur_vente'] ?? 0;
                
                // ============================================
                // MÉTHODE 1 : Comme dans getFluxParVendeur
                // ============================================
                $qteVendueCalculee = max(0, $quantiteTrouvee + $quantiteRecue - $quantiteRetour - $quantiteRestante);
                $valeur1 = $qteVendueCalculee * $prixUnitaire;
                $totalVenduMethode1 += $valeur1;
                
                // ============================================
                // MÉTHODE 2 : Calcul actuel de la vue
                // ============================================
                $valArrivee = $quantiteTrouvee * $prixUnitaire;
                $valRecep = $quantiteRecue * $prixUnitaire;
                $valRetour = $quantiteRetour * $prixUnitaire;
                $valSortie = $quantiteRestante * $prixUnitaire;
                $valeur2 = $valArrivee + $valRecep - $valRetour - $valSortie;
                
                // Accumuler pour méthode 2
                $valeurReceptions += $valRecep;
                $valeurRetours += $valRetour;
                $valeurArrivee += $valArrivee;
                $valeurSortie += $valSortie;
                
                // Quantités pour stats
                $totalReceptionsQte += $quantiteRecue;
                $totalRetoursQte += $quantiteRetour;
                $totalStockInitialQte += $quantiteTrouvee;
                $totalStockFinalQte += $quantiteRestante;
                
                // ============================================
                // DÉTECTER LES DIFFÉRENCES
                // ============================================
                $difference = abs($valeur1 - $valeur2);
                
                // Capturer TOUS les produits avec activité OU différence
                if ($difference > 0.01 || $quantiteTrouvee > 0 || $quantiteRecue > 0 || $quantiteRetour > 0 || $quantiteRestante > 0) {
                    $debug[] = [
                        'produit' => $produitFlux['produit_nom'] ?? 'N/A',
                        'prix' => $prixUnitaire,
                        'trouve' => $quantiteTrouvee,
                        'recu' => $quantiteRecue,
                        'retour' => $quantiteRetour,
                        'restant' => $quantiteRestante,
                        'qte_vendue_flux' => $quantiteVendue,
                        'valeur_vente_flux' => $valeurVente,
                        'qte_calculee' => $qteVendueCalculee,
                        'methode1' => $valeur1,
                        'methode2' => $valeur2,
                        'difference' => $valeur1 - $valeur2,
                        'has_diff' => $difference > 0.01,
                    ];
                }
            }
        }
        
        $totalVenduMethode2 = $valeurArrivee + $valeurReceptions - $valeurRetours - $valeurSortie;
        $differenceGlobale = $totalVenduMethode1 - $totalVenduMethode2;
        
        // Récupérer le total du résumé pour comparaison
        $totalResumeOfficial = $flux['resume']['total_ventes'] ?? 0;
    @endphp
    
    <div class="space-y-6">
        
        <!-- ========================================== -->
        <!-- PANNEAU DE DEBUG DÉTAILLÉ -->
        <!-- ========================================== -->
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-orange-300 rounded-xl shadow-lg p-6">
            <h3 class="text-2xl font-bold text-orange-800 mb-4 flex items-center">
                <i class="fas fa-bug mr-3"></i>
                🔍 PANNEAU DE DEBUG - ANALYSE DES CALCULS
            </h3>
            
            <!-- Résumé des totaux -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg p-4 border-2 border-blue-300">
                    <p class="text-sm text-gray-600 mb-1">Total Résumé Officiel (Header)</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($totalResumeOfficial, 0, ',', ' ') }} FCFA</p>
                    <p class="text-xs text-gray-500">($flux['resume']['total_ventes'])</p>
                </div>
                
                <div class="bg-white rounded-lg p-4 border-2 border-green-300">
                    <p class="text-sm text-gray-600 mb-1">Méthode 1 (Qté × Prix)</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalVenduMethode1, 0, ',', ' ') }} FCFA</p>
                    <p class="text-xs text-gray-500">Σ(quantite_vendue × prix)</p>
                </div>
                
                <div class="bg-white rounded-lg p-4 border-2 border-purple-300">
                    <p class="text-sm text-gray-600 mb-1">Méthode 2 (Flux financier)</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($totalVenduMethode2, 0, ',', ' ') }} FCFA</p>
                    <p class="text-xs text-gray-500">Arrivée + Récep - Retours - Sortie</p>
                </div>
            </div>
            
            <!-- Différences -->
            <div class="bg-white rounded-lg p-4 mb-6">
                <h4 class="font-bold text-gray-800 mb-3">📊 Analyse des différences :</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Différence Officiel vs Méthode 1 :</span>
                        <span class="font-bold {{ abs($totalResumeOfficial - $totalVenduMethode1) > 0.01 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($totalResumeOfficial - $totalVenduMethode1, 2, ',', ' ') }} FCFA
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Différence Officiel vs Méthode 2 :</span>
                        <span class="font-bold {{ abs($totalResumeOfficial - $totalVenduMethode2) > 0.01 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($totalResumeOfficial - $totalVenduMethode2, 2, ',', ' ') }} FCFA
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Différence Méthode 1 vs Méthode 2 :</span>
                        <span class="font-bold {{ abs($differenceGlobale) > 0.01 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($differenceGlobale, 2, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-gray-100 rounded">
                    <p class="text-xs text-gray-600 mb-1">Nombre de produits analysés : <strong>{{ $produitsAnalyses }}</strong></p>
                    <p class="text-xs text-gray-600">Produits dans le tableau debug : <strong>{{ count($debug) }}</strong></p>
                </div>
            </div>
            
            <!-- Formule détaillée Méthode 2 -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <h4 class="font-bold text-blue-800 mb-2">🧮 Détail du calcul Méthode 2 :</h4>
                <div class="text-sm space-y-1">
                    <p>Valeur Arrivée (Stock Initial × Prix) : <strong>{{ number_format($valeurArrivee, 0, ',', ' ') }} FCFA</strong></p>
                    <p>Valeur Réceptions (Qté Reçue × Prix) : <strong>+ {{ number_format($valeurReceptions, 0, ',', ' ') }} FCFA</strong></p>
                    <p>Valeur Retours (Qté Retour × Prix) : <strong>- {{ number_format($valeurRetours, 0, ',', ' ') }} FCFA</strong></p>
                    <p>Valeur Sortie (Stock Final × Prix) : <strong>- {{ number_format($valeurSortie, 0, ',', ' ') }} FCFA</strong></p>
                    <hr class="my-2">
                    <p class="text-lg font-bold text-blue-600">
                        = {{ number_format($totalVenduMethode2, 0, ',', ' ') }} FCFA
                    </p>
                </div>
            </div>
            
            <!-- Tableau détaillé des produits -->
            @if(count($debug) > 0)
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="bg-gray-800 text-white p-3">
                    <h4 class="font-bold">📋 Détail par produit ({{ count($debug) }} produits)</h4>
                </div>
                <div class="overflow-x-auto" style="max-height: 500px; overflow-y: auto;">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-200 sticky top-0">
                            <tr>
                                <th class="p-2 text-left">Produit</th>
                                <th class="p-2 text-right">Prix</th>
                                <th class="p-2 text-center bg-purple-100">T</th>
                                <th class="p-2 text-center bg-blue-100">R</th>
                                <th class="p-2 text-center bg-orange-100">Ret</th>
                                <th class="p-2 text-center bg-gray-100">Rest</th>
                                <th class="p-2 text-center bg-green-100">Qté V. (Flux)</th>
                                <th class="p-2 text-right bg-green-100">Val. (Flux)</th>
                                <th class="p-2 text-center bg-yellow-100">Qté Calc</th>
                                <th class="p-2 text-right bg-yellow-100">M1</th>
                                <th class="p-2 text-right bg-purple-100">M2</th>
                                <th class="p-2 text-right">Diff</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($debug as $d)
                            <tr class="border-t {{ $d['has_diff'] ? 'bg-red-50' : '' }} hover:bg-yellow-50">
                                <td class="p-2">
                                    {{ $d['produit'] }}
                                    @if($d['has_diff'])
                                        <span class="ml-1 text-red-600">⚠️</span>
                                    @endif
                                </td>
                                <td class="p-2 text-right font-semibold">{{ number_format($d['prix'], 0, ',', ' ') }}</td>
                                <td class="p-2 text-center bg-purple-50">{{ $d['trouve'] }}</td>
                                <td class="p-2 text-center bg-blue-50">{{ $d['recu'] }}</td>
                                <td class="p-2 text-center bg-orange-50">{{ $d['retour'] }}</td>
                                <td class="p-2 text-center bg-gray-50">{{ $d['restant'] }}</td>
                                <td class="p-2 text-center bg-green-50 font-bold">{{ $d['qte_vendue_flux'] }}</td>
                                <td class="p-2 text-right bg-green-50">{{ number_format($d['valeur_vente_flux'], 0, ',', ' ') }}</td>
                                <td class="p-2 text-center bg-yellow-50 font-bold">{{ $d['qte_calculee'] }}</td>
                                <td class="p-2 text-right bg-yellow-50">{{ number_format($d['methode1'], 0, ',', ' ') }}</td>
                                <td class="p-2 text-right bg-purple-50">{{ number_format($d['methode2'], 0, ',', ' ') }}</td>
                                <td class="p-2 text-right font-bold {{ $d['has_diff'] ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($d['difference'], 2, ',', ' ') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-800 text-white font-bold">
                            <tr>
                                <td colspan="9" class="p-2 text-right">TOTAUX :</td>
                                <td class="p-2 text-right">{{ number_format($totalVenduMethode1, 0, ',', ' ') }}</td>
                                <td class="p-2 text-right">{{ number_format($totalVenduMethode2, 0, ',', ' ') }}</td>
                                <td class="p-2 text-right {{ abs($differenceGlobale) > 0.01 ? 'text-red-400' : 'text-green-400' }}">
                                    {{ number_format($differenceGlobale, 2, ',', ' ') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="bg-gray-100 p-3 text-xs text-gray-600">
                    <p><strong>Légende :</strong></p>
                    <p>T = Trouvé (Stock Initial) | R = Reçu | Ret = Retour | Rest = Restant (Stock Final)</p>
                    <p>Qté V. (Flux) = quantite_vendue du flux | Val. (Flux) = valeur_vente du flux</p>
                    <p>M1 = Méthode 1 (Qté calculée × Prix) | M2 = Méthode 2 (Flux financier)</p>
                    <p class="mt-1 text-red-600">⚠️ = Différence détectée entre M1 et M2</p>
                </div>
            </div>
            @else
            <div class="bg-yellow-100 border border-yellow-300 rounded p-4 text-center">
                <p class="text-yellow-800">Aucun produit avec activité trouvé</p>
            </div>
            @endif
        </div>
        
        <!-- ========================================== -->
        <!-- STATISTIQUES GLOBALES (Version originale) -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Total Réceptions' : 'Total Receptions' }}</p>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($valeurReceptions, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($totalReceptionsQte, 0, ',', ' ') }} unités</p>
                    </div>
                    <i class="fas fa-truck text-4xl text-blue-200"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Total Retours' : 'Total Returns' }}</p>
                        <p class="text-3xl font-bold text-orange-600">{{ number_format($valeurRetours, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($totalRetoursQte, 0, ',', ' ') }} unités</p>
                    </div>
                    <i class="fas fa-undo text-4xl text-orange-200"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Valeur Arrivée' : 'Incoming Value' }}</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($valeurArrivee, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($totalStockInitialQte, 0, ',', ' ') }} unités</p>
                    </div>
                    <i class="fas fa-arrow-down text-4xl text-purple-200"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-gray-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Valeur Sortie' : 'Outgoing Value' }}</p>
                        <p class="text-2xl font-bold text-gray-600">{{ number_format($valeurSortie, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($totalStockFinalQte, 0, ',', ' ') }} unités</p>
                    </div>
                    <i class="fas fa-arrow-up text-4xl text-gray-200"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Total Vendu (M2)' : 'Total Sold (M2)' }}</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($totalVenduMethode2, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($totalStockInitialQte + $totalReceptionsQte - $totalRetoursQte - $totalStockFinalQte, 0, ',', ' ') }} unités</p>
                    </div>
                    <i class="fas fa-money-bill-wave text-4xl text-green-200"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-amber-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">{{ $isFrench ? 'Attendu (Officiel)' : 'Expected (Official)' }}</p>
                        <p class="text-2xl font-bold text-amber-600">{{ number_format($totalResumeOfficial, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $isFrench ? 'Du résumé' : 'From summary' }}</p>
                    </div>
                    <i class="fas fa-calculator text-4xl text-amber-200"></i>
                </div>
            </div>
        </div>

        <!-- Explication de la formule -->
        <div class="bg-blue-50 rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                {{ $isFrench ? 'Formule de Calcul' : 'Calculation Formula' }}
            </h3>
            <div class="bg-white rounded-lg p-4">
                <p class="text-sm text-gray-700 mb-2">
                    <strong>{{ $isFrench ? 'Total Vendu' : 'Total Sold' }} = Valeur Arrivée + {{ $isFrench ? 'Réceptions' : 'Receptions' }} - {{ $isFrench ? 'Retours' : 'Returns' }} - Valeur Sortie</strong>
                </p>
                <p class="text-sm text-gray-600">
                    = {{ number_format($valeurArrivee, 0, ',', ' ') }} + {{ number_format($valeurReceptions, 0, ',', ' ') }} - {{ number_format($valeurRetours, 0, ',', ' ') }} - {{ number_format($valeurSortie, 0, ',', ' ') }} = <strong class="text-green-600">{{ number_format($totalVenduMethode2, 0, ',', ' ') }} FCFA</strong>
                </p>
            </div>
        </div>
        
        <!-- Reste du code original (Formulaire de Calcul du Manquant, etc.) -->
        <!-- ... (garder le code existant) ... -->
        
    </div>
</div>
    </div>
</div>

<script>
let currentView = 'card';
const totalVendu = {{ $totalVendu ?? 0 }};

function showCardView() {
    document.getElementById('cardView').classList.remove('hidden');
    document.getElementById('tableView').classList.add('hidden');
    document.getElementById('summaryView').classList.add('hidden');
    currentView = 'card';
}

function showTableView() {
    document.getElementById('cardView').classList.add('hidden');
    document.getElementById('tableView').classList.remove('hidden');
    document.getElementById('summaryView').classList.add('hidden');
    currentView = 'table';
}

function showSummaryView() {
    document.getElementById('cardView').classList.add('hidden');
    document.getElementById('tableView').classList.add('hidden');
    document.getElementById('summaryView').classList.remove('hidden');
    currentView = 'summary';
}

function showSimpleForm() {
    document.getElementById('simpleForm').classList.remove('hidden');
    document.getElementById('detailedForm').classList.add('hidden');
    document.getElementById('btnSimple').classList.remove('bg-gray-300', 'text-gray-700');
    document.getElementById('btnSimple').classList.add('bg-amber-600', 'text-white');
    document.getElementById('btnDetailed').classList.remove('bg-amber-600', 'text-white');
    document.getElementById('btnDetailed').classList.add('bg-gray-300', 'text-gray-700');
}

function showDetailedForm() {
    document.getElementById('simpleForm').classList.add('hidden');
    document.getElementById('detailedForm').classList.remove('hidden');
    document.getElementById('btnDetailed').classList.remove('bg-gray-300', 'text-gray-700');
    document.getElementById('btnDetailed').classList.add('bg-amber-600', 'text-white');
    document.getElementById('btnSimple').classList.remove('bg-amber-600', 'text-white');
    document.getElementById('btnSimple').classList.add('bg-gray-300', 'text-gray-700');
}

function calculateSimple() {
    const montantVerse = parseFloat(document.getElementById('montantTotalSimple').value) || 0;
    const manquant = totalVendu - montantVerse;
    
    displayResult(totalVendu, montantVerse, manquant);
    document.getElementById('detailsBreakdown').classList.add('hidden');
}

function calculateDetailed() {
    const fondInitial = parseFloat(document.getElementById('fondInitial').value) || 0;
    const mtnInitial = parseFloat(document.getElementById('mtnInitial').value) || 0;
    const orangeInitial = parseFloat(document.getElementById('orangeInitial').value) || 0;
    
    const fondFinal = parseFloat(document.getElementById('fondFinal').value) || 0;
    const mtnFinal = parseFloat(document.getElementById('mtnFinal').value) || 0;
    const orangeFinal = parseFloat(document.getElementById('orangeFinal').value) || 0;
    
    const cashVerse = fondFinal - fondInitial;
    const mtnVerse = mtnFinal - mtnInitial;
    const orangeVerse = orangeFinal - orangeInitial;
    const montantVerse = cashVerse + mtnVerse + orangeVerse;
    
    const manquant = totalVendu - montantVerse;
    
    document.getElementById('cashDetail').textContent = formatNumber(cashVerse) + ' FCFA';
    document.getElementById('mtnDetail').textContent = formatNumber(mtnVerse) + ' FCFA';
    document.getElementById('orangeDetail').textContent = formatNumber(orangeVerse) + ' FCFA';
    
    displayResult(totalVendu, montantVerse, manquant);
    document.getElementById('detailsBreakdown').classList.remove('hidden');
}

function displayResult(attendu, verse, manquant) {
    document.getElementById('attendu').textContent = formatNumber(attendu);
    document.getElementById('verse').textContent = formatNumber(verse);
    
    const manquantElement = document.getElementById('manquantText');
    manquantElement.textContent = formatNumber(Math.abs(manquant)) + ' FCFA';
    
    if (manquant > 0) {
        manquantElement.className = 'text-3xl font-bold text-red-600';
        manquantElement.textContent = '- ' + formatNumber(manquant) + ' FCFA ({{ $isFrench ? "Manquant" : "Missing" }})';
    } else if (manquant < 0) {
        manquantElement.className = 'text-3xl font-bold text-green-600';
        manquantElement.textContent = '+ ' + formatNumber(Math.abs(manquant)) + ' FCFA ({{ $isFrench ? "Excédent" : "Surplus" }})';
    } else {
        manquantElement.className = 'text-3xl font-bold text-green-600';
        manquantElement.textContent = '0 FCFA ({{ $isFrench ? "Juste" : "Exact" }})';
    }
    
    document.getElementById('resultDiv').classList.remove('hidden');
}

function formatNumber(num) {
    return num.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

function setToday() {
    document.querySelector('input[name="date"]').value = new Date().toISOString().split('T')[0];
}

function setYesterday() {
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    document.querySelector('input[name="date"]').value = yesterday.toISOString().split('T')[0];
}
</script>
@endsection