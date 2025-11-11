@extends('layouts.app')

@section('title', $isFrench ? 'Réceptions de Produits' : 'Product Receptions')

@section('styles')
<style>
    .bread-gradient {
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
    }
    @media print {
        .no-print { display: none !important; }
        .print-full-width { width: 100% !important; }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50">
    <div class="container mx-auto px-4 py-6 sm:py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 sm:p-8 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                        <i class="fas fa-truck mr-3"></i>
                        {{ $isFrench ? 'Réceptions de Produits' : 'Product Receptions' }}
                    </h1>
                    <p class="text-amber-50 text-sm sm:text-base">
                        {{ $isFrench ? 'Suivi des arrivages et réceptions' : 'Track arrivals and receptions' }}
                    </p>
                </div>
                
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6 no-print">
            <form method="GET" action="{{ route('pdg.receptions') }}" id="filterForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <!-- Date Début -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-start mr-1 text-amber-600"></i>
                            {{ $isFrench ? 'Date Début' : 'Start Date' }}
                        </label>
                        <input type="date" name="date_debut" value="{{ request('date_debut') }}" 
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all"
                               onchange="document.getElementById('filterForm').submit()">
                    </div>

                    <!-- Date Fin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-check mr-1 text-amber-600"></i>
                            {{ $isFrench ? 'Date Fin' : 'End Date' }}
                        </label>
                        <input type="date" name="date_fin" value="{{ request('date_fin') }}" 
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all"
                               onchange="document.getElementById('filterForm').submit()">
                    </div>

                    <!-- Produit -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-box mr-1 text-blue-600"></i>
                            {{ $isFrench ? 'Produit' : 'Product' }}
                        </label>
                        <select name="produit_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">{{ $isFrench ? 'Tous les produits' : 'All products' }}</option>
                            @foreach($produits ?? [] as $produit)
                                <option value="{{ $produit->id }}" {{ request('produit_id') == $produit->id ? 'selected' : '' }}>
                                    {{ $produit->nom }} ({{ ucfirst($produit->categorie) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pointeur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-check mr-1 text-blue-600"></i>
                            {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                        </label>
                        <select name="pointeur_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">{{ $isFrench ? 'Tous les pointeurs' : 'All pointers' }}</option>
                            @foreach($pointeurs ?? [] as $pointeur)
                                <option value="{{ $pointeur->id }}" {{ request('pointeur_id') == $pointeur->id ? 'selected' : '' }}>
                                    {{ $pointeur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Producteur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tie mr-1 text-purple-600"></i>
                            {{ $isFrench ? 'Producteur' : 'Producer' }}
                        </label>
                        <select name="producteur_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">{{ $isFrench ? 'Tous les producteurs' : 'All producers' }}</option>
                            @foreach($producteurs ?? [] as $producteur)
                                <option value="{{ $producteur->id }}" {{ request('producteur_id') == $producteur->id ? 'selected' : '' }}>
                                    {{ $producteur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Vendeur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-store mr-1 text-green-600"></i>
                            {{ $isFrench ? 'Vendeur Assigné' : 'Assigned Seller' }}
                        </label>
                        <select name="vendeur_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">{{ $isFrench ? 'Tous les vendeurs' : 'All sellers' }}</option>
                            @foreach($vendeurs ?? [] as $vendeur)
                                <option value="{{ $vendeur->id }}" {{ request('vendeur_id') == $vendeur->id ? 'selected' : '' }}>
                                    {{ $vendeur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Statut Verrou -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-1 text-red-600"></i>
                            {{ $isFrench ? 'Statut' : 'Status' }}
                        </label>
                        <select name="verrou" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">{{ $isFrench ? 'Tous les statuts' : 'All statuses' }}</option>
                            <option value="0" {{ request('verrou') === '0' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Non verrouillé' : 'Unlocked' }}
                            </option>
                            <option value="1" {{ request('verrou') === '1' ? 'selected' : '' }}>
                                {{ $isFrench ? 'Verrouillé' : 'Locked' }}
                            </option>
                        </select>
                    </div>

                    <!-- Raccourcis dates -->
                    <div class="flex items-end">
                        <div class="w-full space-y-2">
                            <button type="button" onclick="setDateFilter('today')" 
                                    class="w-full px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition-all">
                                <i class="fas fa-calendar-day mr-1"></i>
                                {{ $isFrench ? 'Aujourd\'hui' : 'Today' }}
                            </button>
                            <button type="button" onclick="setDateFilter('week')" 
                                    class="w-full px-3 py-2 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition-all">
                                <i class="fas fa-calendar-week mr-1"></i>
                                {{ $isFrench ? 'Cette semaine' : 'This week' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-filter mr-2"></i>
                        {{ $isFrench ? 'Filtrer' : 'Filter' }}
                    </button>
                    <a href="{{ route('pdg.receptions') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-redo mr-2"></i>
                        {{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                    </a>
                    <button type="button" onclick="exportToCSV()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-file-csv mr-2"></i>
                        {{ $isFrench ? 'Exporter CSV' : 'Export CSV' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-box text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $receptions->total() }}</span>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Total Réceptions' : 'Total Receptions' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-layer-group text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $receptions->sum('quantite') }}</span>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Quantité Totale' : 'Total Quantity' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-users text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $receptions->unique('producteur_id')->count() }}</span>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Producteurs Actifs' : 'Active Producers' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-boxes text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $receptions->unique('produit_id')->count() }}</span>
                </div>
                <h3 class="text-lg font-semibold">{{ $isFrench ? 'Produits Différents' : 'Different Products' }}</h3>
            </div>
        </div>

        <!-- Receptions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bread-gradient text-white">
                        <tr>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Date/Heure' : 'Date/Time' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Catégorie' : 'Category' }}</th>
                            <th class="px-4 py-4 text-center text-sm font-semibold">{{ $isFrench ? 'Quantité' : 'Quantity' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Producteur' : 'Producer' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Pointeur' : 'Pointer' }}</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold">{{ $isFrench ? 'Vendeur' : 'Seller' }}</th>
                            <th class="px-4 py-4 text-center text-sm font-semibold">{{ $isFrench ? 'Statut' : 'Status' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($receptions as $reception)
                        <tr class="hover:bg-amber-50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $reception->date_reception->format('d/m/Y') }}</div>
                                    <div class="text-gray-500">{{ $reception->date_reception->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="font-semibold text-gray-900">{{ $reception->produit->nom }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                    {{ $reception->produit->categorie == 'boulangerie' ? 'bg-amber-100 text-amber-800' : 'bg-pink-100 text-pink-800' }}">
                                    <i class="fas fa-{{ $reception->produit->categorie == 'boulangerie' ? 'bread-slice' : 'birthday-cake' }} mr-1"></i>
                                    {{ ucfirst($reception->produit->categorie) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="font-bold text-lg text-blue-600">{{ $reception->quantite }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">{{ $reception->producteur->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user-check text-blue-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">{{ $reception->pointeur->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-store text-green-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">
                                        {{ $reception->vendeurAssigne ? $reception->vendeurAssigne->name : ($isFrench ? 'Non assigné' : 'Not assigned') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($reception->verrou)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-lock mr-1"></i>
                                        {{ $isFrench ? 'Verrouillé' : 'Locked' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-lock-open mr-1"></i>
                                        {{ $isFrench ? 'Ouvert' : 'Open' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                                <p class="text-lg">{{ $isFrench ? 'Aucune réception trouvée' : 'No receptions found' }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($receptions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 no-print">
                {{ $receptions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function setDateFilter(type) {
    const today = new Date();
    const dateDebutInput = document.querySelector('input[name="date_debut"]');
    const dateFinInput = document.querySelector('input[name="date_fin"]');
    
    if (type === 'today') {
        const todayStr = today.toISOString().split('T')[0];
        dateDebutInput.value = todayStr;
        dateFinInput.value = todayStr;
    } else if (type === 'week') {
        const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
        const lastDay = new Date(today.setDate(today.getDate() - today.getDay() + 6));
        dateDebutInput.value = firstDay.toISOString().split('T')[0];
        dateFinInput.value = lastDay.toISOString().split('T')[0];
    }
    
    document.getElementById('filterForm').submit();
}

function exportToCSV() {
    try {
        // Récupérer toutes les lignes du tableau
        const table = document.querySelector('table');
        const rows = table.querySelectorAll('tbody tr');
        
        // Vérifier s'il y a des données
        if (rows.length === 0 || rows[0].cells.length === 1) {
            alert('{{ $isFrench ? "Aucune donnée à exporter" : "No data to export" }}');
            return;
        }
        
        // Créer le contenu CSV
        let csvContent = '';
        
        // En-têtes
        const headers = [
            '{{ $isFrench ? "Date" : "Date" }}',
            '{{ $isFrench ? "Heure" : "Time" }}',
            '{{ $isFrench ? "Produit" : "Product" }}',
            '{{ $isFrench ? "Catégorie" : "Category" }}',
            '{{ $isFrench ? "Quantité" : "Quantity" }}',
            '{{ $isFrench ? "Producteur" : "Producer" }}',
            '{{ $isFrench ? "Pointeur" : "Pointer" }}',
            '{{ $isFrench ? "Vendeur" : "Seller" }}',
            '{{ $isFrench ? "Statut" : "Status" }}'
        ];
        csvContent += headers.join(';') + '\n';
        
        // Parcourir toutes les lignes
        rows.forEach(row => {
            if (row.cells.length > 1) { // Ignorer la ligne "Aucune réception"
                const cells = row.querySelectorAll('td');
                const rowData = [];
                
                // Date
                const dateCell = cells[0].querySelector('.font-medium');
                rowData.push(dateCell ? dateCell.textContent.trim() : '');
                
                // Heure
                const heureCell = cells[0].querySelector('.text-gray-500');
                rowData.push(heureCell ? heureCell.textContent.trim() : '');
                
                // Produit
                const produitCell = cells[1].querySelector('.font-semibold');
                rowData.push(produitCell ? produitCell.textContent.trim() : '');
                
                // Catégorie
                const categorieSpan = cells[2].querySelector('span');
                const categorieText = categorieSpan ? categorieSpan.textContent.trim() : '';
                rowData.push(categorieText.replace(/\s+/g, ' '));
                
                // Quantité
                const quantiteCell = cells[3].querySelector('.font-bold');
                rowData.push(quantiteCell ? quantiteCell.textContent.trim() : '');
                
                // Producteur
                const producteurCell = cells[4].querySelector('.text-gray-700');
                rowData.push(producteurCell ? producteurCell.textContent.trim() : '');
                
                // Pointeur
                const pointeurCell = cells[5].querySelector('.text-gray-700');
                rowData.push(pointeurCell ? pointeurCell.textContent.trim() : '');
                
                // Vendeur
                const vendeurCell = cells[6].querySelector('.text-gray-700');
                rowData.push(vendeurCell ? vendeurCell.textContent.trim() : '');
                
                // Statut
                const statutSpan = cells[7].querySelector('span');
                const statutText = statutSpan ? statutSpan.textContent.trim() : '';
                rowData.push(statutText.replace(/\s+/g, ' '));
                
                csvContent += rowData.map(cell => '"' + cell.replace(/"/g, '""') + '"').join(';') + '\n';
            }
        });
        
        // Créer un blob et télécharger
        const BOM = '\uFEFF'; // UTF-8 BOM pour Excel
        const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        const date = new Date().toISOString().split('T')[0];
        
        link.setAttribute('href', url);
        link.setAttribute('download', 'receptions_' + date + '.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Afficher un message de succès
        const message = '{{ $isFrench ? "Export réussi !" : "Export successful!" }}';
        const div = document.createElement('div');
        div.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        div.innerHTML = '<i class="fas fa-check-circle mr-2"></i>' + message;
        document.body.appendChild(div);
        setTimeout(() => div.remove(), 3000);
        
    } catch (error) {
        console.error('Erreur export:', error);
        alert('{{ $isFrench ? "Erreur lors de l\'export" : "Error during export" }}');
    }
}
</script>
@endsection