@extends('layouts.app')

@section('title', $isFrench ? 'Inventaires' : 'Inventories')

@section('styles')
<style>
    .bread-gradient {
        background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
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
                        <i class="fas fa-clipboard-list mr-3"></i>
                        {{ $isFrench ? 'Suivi des Inventaires' : 'Inventory Tracking' }}
                    </h1>
                    <p class="text-amber-50 text-sm sm:text-base">
                        {{ $isFrench ? 'Historique des changements de garde' : 'History of shift changes' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-check-circle text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $inventaires->total() }}</span>
                </div>
                <h3 class="text-sm font-semibold">{{ $isFrench ? 'Total Inventaires' : 'Total Inventories' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-bread-slice text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $inventaires->where('categorie', 'boulangerie')->count() }}</span>
                </div>
                <h3 class="text-sm font-semibold">{{ $isFrench ? 'Boulangerie' : 'Bakery' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-birthday-cake text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $inventaires->where('categorie', 'patisserie')->count() }}</span>
                </div>
                <h3 class="text-sm font-semibold">{{ $isFrench ? 'Pâtisserie' : 'Pastry' }}</h3>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-calendar-day text-3xl opacity-80"></i>
                    <span class="text-3xl font-bold">{{ $inventaires->filter(fn($i) => $i->date_inventaire->isToday())->count() }}</span>
                </div>
                <h3 class="text-sm font-semibold">{{ $isFrench ? 'Aujourd\'hui' : 'Today' }}</h3>
            </div>
        </div>

        <!-- Inventaires List -->
        <div class="space-y-6">
            @forelse($inventaires as $inventaire)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
                <div class="bread-gradient p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/20 w-12 h-12 rounded-xl flex items-center justify-center">
                            <i class="fas fa-{{ $inventaire->categorie == 'boulangerie' ? 'bread-slice' : 'birthday-cake' }} text-2xl text-white"></i>
                        </div>
                        <div class="bg-gradient-to-r from-amber-600 to-amber-700 p-4 rounded-lg shadow-md">
    <h3 class="text-xl font-bold text-white">
        {{ $isFrench ? 'Inventaire' : 'Inventory' }} #{{ $inventaire->id }}
    </h3>
    <p class="text-amber-100 text-sm mt-1">
        {{ $inventaire->date_inventaire->format('d/m/Y') }} • {{ $inventaire->date_inventaire->format('H:i') }}
    </p>
</div>
                    </div>
                    <span class="inline-flex px-4 py-2 text-sm font-medium rounded-lg bg-white/20 text-white">
                        {{ ucfirst($inventaire->categorie) }}
                    </span>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Vendeur Sortant -->
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="font-bold text-red-800 mb-3 flex items-center">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                {{ $isFrench ? 'Vendeur Sortant' : 'Outgoing Seller' }}
                            </h4>
                            <div class="space-y-2">
                                <p class="text-gray-700"><strong>{{ $isFrench ? 'Nom:' : 'Name:' }}</strong> {{ $inventaire->vendeurSortant->name ?? 'N/A' }}</p>
                                <p class="text-gray-700"><strong>{{ $isFrench ? 'Code PIN:' : 'PIN:' }}</strong> ●●●●●●</p>
                                <p class="font-semibold {{ $inventaire->valide_sortant ? 'text-green-600' : 'text-orange-600' }}">
                                    <i class="fas fa-{{ $inventaire->valide_sortant ? 'check-circle' : 'clock' }} mr-1"></i>
                                    {{ $inventaire->valide_sortant ? ($isFrench ? 'Validé' : 'Validated') : ($isFrench ? 'En attente' : 'Pending') }}
                                </p>
                            </div>
                        </div>

                        <!-- Vendeur Entrant -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <h4 class="font-bold text-green-800 mb-3 flex items-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                {{ $isFrench ? 'Vendeur Entrant' : 'Incoming Seller' }}
                            </h4>
                            <div class="space-y-2">
                                <p class="text-gray-700"><strong>{{ $isFrench ? 'Nom:' : 'Name:' }}</strong> {{ $inventaire->vendeurEntrant->name ?? 'N/A' }}</p>
                                <p class="text-gray-700"><strong>{{ $isFrench ? 'Code PIN:' : 'PIN:' }}</strong> ●●●●●●</p>
                                <p class="font-semibold {{ $inventaire->valide_entrant ? 'text-green-600' : 'text-orange-600' }}">
                                    <i class="fas fa-{{ $inventaire->valide_entrant ? 'check-circle' : 'clock' }} mr-1"></i>
                                    {{ $inventaire->valide_entrant ? ($isFrench ? 'Validé' : 'Validated') : ($isFrench ? 'En attente' : 'Pending') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Produits Inventoriés -->
                    @if($inventaire->details->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $isFrench ? 'Quantité Laissée' : 'Quantity Left' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $isFrench ? 'Prix Unitaire' : 'Unit Price' }}</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">{{ $isFrench ? 'Valeur Total' : 'Total Value' }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php $valeurTotale = 0; @endphp
                                @foreach($inventaire->details as $detail)
                                @php 
                                    $valeurLigne = $detail->quantite_restante * ($detail->produit->prix ?? 0);
                                    $valeurTotale += $valeurLigne;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-900">{{ $detail->produit->nom ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-bold">
                                            {{ $detail->quantite_restante }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ number_format($detail->produit->prix ?? 0) }} F</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="font-bold text-green-600">{{ number_format($valeurLigne) }} F</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-800">{{ $isFrench ? 'Valeur Totale:' : 'Total Value:' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xl font-bold text-green-600">{{ number_format($valeurTotale) }} F</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-2"></i>
                        <p>{{ $isFrench ? 'Aucun produit inventorié' : 'No products inventoried' }}</p>
                    </div>
                    @endif

                   
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">{{ $isFrench ? 'Aucun inventaire trouvé' : 'No inventories found' }}</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($inventaires->hasPages())
        <div class="mt-8">
            {{ $inventaires->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function exportToCSV() {
    // Préparer les données
    const inventaires = @json($inventaires->items());
    
    if (inventaires.length === 0) {
        alert('{{ $isFrench ? "Aucun inventaire à exporter" : "No inventory to export" }}');
        return;
    }

    // En-têtes CSV
    const headers = [
        '{{ $isFrench ? "ID" : "ID" }}',
        '{{ $isFrench ? "Date" : "Date" }}',
        '{{ $isFrench ? "Heure" : "Time" }}',
        '{{ $isFrench ? "Catégorie" : "Category" }}',
        '{{ $isFrench ? "Vendeur Sortant" : "Outgoing Seller" }}',
        '{{ $isFrench ? "Validé Sortant" : "Outgoing Validated" }}',
        '{{ $isFrench ? "Vendeur Entrant" : "Incoming Seller" }}',
        '{{ $isFrench ? "Validé Entrant" : "Incoming Validated" }}',
        '{{ $isFrench ? "Nombre Produits" : "Number of Products" }}',
        '{{ $isFrench ? "Valeur Totale (F)" : "Total Value (F)" }}'
    ];

    // Construire les lignes CSV
    let csvContent = headers.join(',') + '\n';

    inventaires.forEach(inv => {
        const date = new Date(inv.date_inventaire);
        const dateStr = date.toLocaleDateString('fr-FR');
        const timeStr = date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        
        // Calculer la valeur totale
        let valeurTotale = 0;
        if (inv.details && inv.details.length > 0) {
            valeurTotale = inv.details.reduce((sum, detail) => {
                const prix = detail.produit ? detail.produit.prix : 0;
                return sum + (detail.quantite_restante * prix);
            }, 0);
        }

        const row = [
            inv.id,
            `"${dateStr}"`,
            `"${timeStr}"`,
            `"${inv.categorie}"`,
            `"${inv.vendeur_sortant ? inv.vendeur_sortant.name : 'N/A'}"`,
            inv.valide_sortant ? '{{ $isFrench ? "Oui" : "Yes" }}' : '{{ $isFrench ? "Non" : "No" }}',
            `"${inv.vendeur_entrant ? inv.vendeur_entrant.name : 'N/A'}"`,
            inv.valide_entrant ? '{{ $isFrench ? "Oui" : "Yes" }}' : '{{ $isFrench ? "Non" : "No" }}',
            inv.details ? inv.details.length : 0,
            valeurTotale.toFixed(2)
        ];

        csvContent += row.join(',') + '\n';
    });

    // Créer un Blob et télécharger
    const blob = new Blob(['\ufeff' + csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    const today = new Date().toISOString().split('T')[0];
    link.setAttribute('href', url);
    link.setAttribute('download', `inventaires_${today}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportDetailedCSV() {
    // Export détaillé avec tous les produits
    const inventaires = @json($inventaires->items());
    
    if (inventaires.length === 0) {
        alert('{{ $isFrench ? "Aucun inventaire à exporter" : "No inventory to export" }}');
        return;
    }

    // En-têtes CSV pour export détaillé
    const headers = [
        '{{ $isFrench ? "ID Inventaire" : "Inventory ID" }}',
        '{{ $isFrench ? "Date" : "Date" }}',
        '{{ $isFrench ? "Heure" : "Time" }}',
        '{{ $isFrench ? "Catégorie" : "Category" }}',
        '{{ $isFrench ? "Vendeur Sortant" : "Outgoing Seller" }}',
        '{{ $isFrench ? "Vendeur Entrant" : "Incoming Seller" }}',
        '{{ $isFrench ? "Produit" : "Product" }}',
        '{{ $isFrench ? "Quantité" : "Quantity" }}',
        '{{ $isFrench ? "Prix Unitaire (F)" : "Unit Price (F)" }}',
        '{{ $isFrench ? "Valeur Totale (F)" : "Total Value (F)" }}'
    ];

    let csvContent = headers.join(',') + '\n';

    inventaires.forEach(inv => {
        const date = new Date(inv.date_inventaire);
        const dateStr = date.toLocaleDateString('fr-FR');
        const timeStr = date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        
        const vendeurSortant = inv.vendeur_sortant ? inv.vendeur_sortant.name : 'N/A';
        const vendeurEntrant = inv.vendeur_entrant ? inv.vendeur_entrant.name : 'N/A';

        if (inv.details && inv.details.length > 0) {
            inv.details.forEach(detail => {
                const produitNom = detail.produit ? detail.produit.nom : 'N/A';
                const prix = detail.produit ? detail.produit.prix : 0;
                const valeurLigne = detail.quantite_restante * prix;

                const row = [
                    inv.id,
                    `"${dateStr}"`,
                    `"${timeStr}"`,
                    `"${inv.categorie}"`,
                    `"${vendeurSortant}"`,
                    `"${vendeurEntrant}"`,
                    `"${produitNom}"`,
                    detail.quantite_restante,
                    prix.toFixed(2),
                    valeurLigne.toFixed(2)
                ];

                csvContent += row.join(',') + '\n';
            });
        }
    });

    // Créer un Blob et télécharger
    const blob = new Blob(['\ufeff' + csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    const today = new Date().toISOString().split('T')[0];
    link.setAttribute('href', url);
    link.setAttribute('download', `inventaires_details_${today}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection