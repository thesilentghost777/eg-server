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
    
    /* Optimisation pour grands volumes */
    .compact-row td {
        padding: 0.5rem !important;
        font-size: 0.875rem;
    }
    
    .sticky-header th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
    }
    
    /* Mise en cache visuelle pour le défilement */
    .table-container {
        height: 600px;
        overflow-y: auto;
        contain: layout style paint;
    }
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
                        <i class="fas fa-truck mr-3"></i>
                        {{ $isFrench ? 'Réceptions de Produits' : 'Product Receptions' }}
                    </h1>
                    <p class="text-amber-50 text-sm sm:text-base">
                        {{ $isFrench ? 'Suivi des arrivages et réceptions' : 'Track arrivals and receptions' }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('pdg.receptions.imprimer', request()->all()) }}" target="_blank"
                       class="px-4 py-2 bg-white text-amber-700 rounded-lg hover:bg-amber-50 transition-all shadow-md">
                        <i class="fas fa-print mr-2"></i>{{ $isFrench ? 'Imprimer' : 'Print' }}
                    </a>
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
                                    {{ $produit->nom }} - {{ $produit->prix }} FCFA ({{ ucfirst($produit->categorie) }})
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
                {{ $vendeur->name }} ({{ ucfirst(str_replace('vendeur_', '', $vendeur->role)) }})
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

        

        <!-- Receptions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                        {{ $isFrench ? 'Date/Heure' : 'Date/Time' }}
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                        {{ $isFrench ? 'Produit' : 'Product' }}
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-white">
                        {{ $isFrench ? 'Qté' : 'Qty' }}
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                        {{ $isFrench ? 'Pointeur' : 'Pointer' }}
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">
                        {{ $isFrench ? 'Vendeur' : 'Seller' }}
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-white no-print">
                        {{ $isFrench ? 'Actions' : 'Actions' }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($receptions as $reception)
                <tr class="hover:bg-blue-50 transition-colors duration-150">
                    <!-- Date/Heure -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900">
                                {{ $reception->date_reception->format('d/m/Y') }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $reception->date_reception->format('H:i') }}
                            </span>
                        </div>
                    </td>
                    
                    <!-- Produit -->
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $reception->produit->nom }}
                            </span>
                            <span class="text-xs text-gray-500 mt-1">
                                {{ number_format($reception->produit->prix, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </td>
                    
                    <!-- Quantité -->
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                            {{ $reception->quantite }}
                        </span>
                    </td>
                    
                    <!-- Pointeur -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-600 text-xs"></i>
                            </div>
                            <span class="ml-3 text-sm text-gray-900">
                                {{ $reception->pointeur->name }}
                            </span>
                        </div>
                    </td>
                    
                    <!-- Vendeur -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($reception->vendeurAssigne)
                                <div class="flex-shrink-0 h-8 w-8 bg-green-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-green-600 text-xs"></i>
                                </div>
                                <span class="ml-3 text-sm text-gray-900">
                                    {{ $reception->vendeurAssigne->name }}
                                </span>
                            @else
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-amber-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-slash text-amber-600 text-xs"></i>
                                    </div>
                                    <span class="ml-3 text-sm text-amber-600 italic">
                                        {{ $isFrench ? 'Non assigné' : 'Not assigned' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </td>
                    
                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-center no-print">
                        @if(!$reception->verrou)
                            <a href="{{ route('pdg.receptions.edit', $reception->id) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-150 shadow-sm hover:shadow-md">
                                <i class="fas fa-edit mr-2"></i>
                                {{ $isFrench ? 'Modifier' : 'Edit' }}
                            </a>
                        @else
                            <span class="inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg border border-red-200">
                                <i class="fas fa-lock mr-2"></i>
                                {{ $isFrench ? 'Verrouillé' : 'Locked' }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-inbox text-6xl mb-4"></i>
                            <p class="text-lg font-medium text-gray-500">
                                {{ $isFrench ? 'Aucune réception trouvée' : 'No receptions found' }}
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($receptions->hasPages())
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 no-print">
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
        const table = document.querySelector('table');
        const rows = table.querySelectorAll('tbody tr');
        
        if (rows.length === 0 || rows[0].cells.length === 1) {
            alert('{{ $isFrench ? "Aucune donnée à exporter" : "No data to export" }}');
            return;
        }
        
        let csvContent = '';
        
        const headers = [
            '{{ $isFrench ? "Date" : "Date" }}',
            '{{ $isFrench ? "Heure" : "Time" }}',
            '{{ $isFrench ? "Produit" : "Product" }}',
            '{{ $isFrench ? "Prix Unitaire" : "Unit Price" }}',
            '{{ $isFrench ? "Quantité" : "Quantity" }}',
            '{{ $isFrench ? "Producteur" : "Producer" }}',
            '{{ $isFrench ? "Pointeur" : "Pointer" }}',
            '{{ $isFrench ? "Vendeur" : "Seller" }}'
        ];
        csvContent += headers.join(';') + '\n';
        
        rows.forEach(row => {
            if (row.cells.length > 1) {
                const cells = row.querySelectorAll('td');
                const rowData = [];
                
                const dateDiv = cells[0].querySelector('.font-medium');
                rowData.push(dateDiv ? dateDiv.textContent.trim() : '');
                
                const heureDiv = cells[0].querySelector('.text-gray-500');
                rowData.push(heureDiv ? heureDiv.textContent.trim() : '');
                
                const produitDiv = cells[1].querySelector('.font-semibold');
                rowData.push(produitDiv ? produitDiv.textContent.trim() : '');
                
                const prixDiv = cells[1].querySelector('.text-gray-500');
                rowData.push(prixDiv ? prixDiv.textContent.trim().replace(' FCFA', '') : '');
                
                const quantiteSpan = cells[2].querySelector('.font-bold');
                rowData.push(quantiteSpan ? quantiteSpan.textContent.trim() : '');
                
                rowData.push(cells[3].textContent.trim());
                rowData.push(cells[4].textContent.trim());
                rowData.push(cells[5].textContent.trim());
                
                csvContent += rowData.map(cell => '"' + cell.replace(/"/g, '""') + '"').join(';') + '\n';
            }
        });
        
        const BOM = '\uFEFF';
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
