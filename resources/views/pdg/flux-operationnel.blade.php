@extends('layouts.app')

@section('title', $isFrench ? 'Flux Opérationnel' : 'Operational Flow')

@section('styles')
<style>
    .table-scroll  { max-height: 600px; overflow-y: auto; }
    .sticky-header { position: sticky; top: 0; z-index: 5;
                     background: linear-gradient(135deg, #D4A574, #B08554); }
    .form-row      { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
    .result-manquant { background: #fef2f2; border: 2px solid #fca5a5; border-radius: .75rem; padding: 1rem; }
    .result-excedent { background: #f0fdf4; border: 2px solid #86efac; border-radius: .75rem; padding: 1rem; }
    .result-exact    { background: #f0fdf4; border: 2px solid #86efac; border-radius: .75rem; padding: 1rem; }

    .row-vente-negative {
        background: #fef2f2 !important;
        outline: 2px solid #fca5a5;
        outline-offset: -2px;
    }
    .row-vente-negative td {
        background: #fef2f2 !important;
    }
    .badge-negatif {
        display: inline-block;
        font-size: .65rem;
        font-weight: 700;
        color: #dc2626;
        background: #fee2e2;
        border: 1px solid #fca5a5;
        border-radius: .3rem;
        padding: .1rem .35rem;
        margin-left: .3rem;
        vertical-align: middle;
    }

    @media print { .no-print { display: none !important; } }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50">
<div class="container mx-auto px-4 py-6">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-amber-700 to-amber-600 rounded-2xl shadow-xl p-6 mb-6 no-print">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white mb-1">
                    <i class="fas fa-stream mr-2"></i>{{ $isFrench ? 'Flux Opérationnel' : 'Operational Flow' }}
                </h1>
                <p class="text-amber-100 text-sm">{{ $selectedDate ?? '' }}</p>
            </div>
            <div class="flex gap-2 flex-wrap">
                {{-- FIX 2 : la vue globale est la vue par défaut, les boutons reflètent cet ordre --}}
                <button onclick="document.getElementById('view-summary').style.display='block'; document.getElementById('view-table').style.display='none'; document.getElementById('view-manquant').style.display='none';"
                        class="px-4 py-2 bg-white text-amber-700 rounded-lg font-semibold hover:bg-amber-50">
                    <i class="fas fa-calculator mr-1"></i>{{ $isFrench ? 'Global' : 'Global' }}
                </button>
                <button onclick="document.getElementById('view-table').style.display='block'; document.getElementById('view-summary').style.display='none'; document.getElementById('view-manquant').style.display='none';"
                        class="px-4 py-2 bg-white text-amber-700 rounded-lg font-semibold hover:bg-amber-50">
                    <i class="fas fa-th-list mr-1"></i>{{ $isFrench ? 'Tableau' : 'Table' }}
                </button>
                <button onclick="document.getElementById('view-table').style.display='none'; document.getElementById('view-summary').style.display='none'; document.getElementById('view-manquant').style.display='block';"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600">
                    <i class="fas fa-search-dollar mr-1"></i>{{ $isFrench ? 'Manquant' : 'Missing' }}
                </button>
                <a href="{{ route('pdg.flux.imprimer', request()->all()) }}" target="_blank"
                   class="px-4 py-2 bg-white text-amber-700 rounded-lg font-semibold hover:bg-amber-50 no-print">
                    <i class="fas fa-print mr-1"></i>{{ $isFrench ? 'Imprimer' : 'Print' }}
                </a>
            </div>
        </div>
    </div>

    {{-- FILTRES --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 no-print">
        <form method="GET" action="{{ route('pdg.flux') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar mr-1 text-amber-600"></i>{{ $isFrench ? 'Date' : 'Date' }}
                    </label>
                    <input type="date" name="date" value="{{ $selectedDate }}" required
                           class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-user mr-1 text-blue-600"></i>{{ $isFrench ? 'Vendeur' : 'Seller' }}
                    </label>
                    <select name="vendeur_id" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                        <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                        @foreach($vendeurs ?? [] as $v)
                            <option value="{{ $v->id }}" {{ $selectedVendeur == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-box mr-1 text-green-600"></i>{{ $isFrench ? 'Produit' : 'Product' }}
                    </label>
                    <select name="produit_id" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none">
                        <option value="">{{ $isFrench ? 'Tous' : 'All' }}</option>
                        @foreach($produits ?? [] as $p)
                            <option value="{{ $p->id }}" {{ $selectedProduit == $p->id ? 'selected' : '' }}>{{ $p->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="button" onclick="document.querySelector('input[name=date]').value=new Date().toISOString().split('T')[0]"
                            class="flex-1 px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
                        <i class="fas fa-calendar-day mr-1"></i>{{ $isFrench ? "Auj." : "Today" }}
                    </button>
                    <button type="button" onclick="var d=new Date();d.setDate(d.getDate()-1);document.querySelector('input[name=date]').value=d.toISOString().split('T')[0]"
                            class="flex-1 px-3 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600">
                        <i class="fas fa-calendar-minus mr-1"></i>{{ $isFrench ? "Hier" : "Yest." }}
                    </button>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-5 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                    <i class="fas fa-search mr-1"></i>{{ $isFrench ? 'Rechercher' : 'Search' }}
                </button>
                <a href="{{ route('pdg.flux') }}" class="px-5 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    <i class="fas fa-redo mr-1"></i>{{ $isFrench ? 'Reset' : 'Reset' }}
                </a>
            </div>
        </form>
    </div>

    {{-- CARTES RÉSUMÉ --}}
    @if(isset($flux['resume']))
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
            <p class="text-2xl font-bold text-gray-800">{{ count($flux['flux'] ?? []) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isFrench ? 'Vendeurs actifs' : 'Active sellers' }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
            <p class="text-lg font-bold text-gray-800">{{ number_format($flux['resume']['total_ventes'] ?? 0, 0, ',', ' ') }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isFrench ? 'Total ventes (FCFA)' : 'Total sales (FCFA)' }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-purple-500">
            <p class="text-2xl font-bold text-gray-800">{{ $flux['resume']['total_produits'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isFrench ? 'Produits vendus' : 'Products sold' }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-orange-500">
            <p class="text-2xl font-bold text-gray-800">{{ $flux['resume']['total_receptions'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $isFrench ? 'Réceptions' : 'Receptions' }}</p>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- VUE TABLEAU — masquée par défaut (FIX 2)              --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div id="view-table" style="display:none;">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="table-scroll">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="text-xs font-bold text-white uppercase tracking-wide"
                            style="background: linear-gradient(135deg, #92400e, #b45309);">
                            <th class="px-4 py-2 text-left" rowspan="2">
                                {{ $isFrench ? 'Vendeur' : 'Seller' }}
                            </th>
                            <th class="px-4 py-2 text-left" rowspan="2">
                                {{ $isFrench ? 'Produit' : 'Product' }}
                            </th>
                            <th colspan="2" class="px-4 py-1 text-center border-l border-amber-600"
                                style="background:rgba(59,130,246,.25);">
                                <i class="fas fa-arrow-down mr-1"></i>{{ $isFrench ? 'Entrées' : 'Inputs' }}
                            </th>
                            <th colspan="2" class="px-4 py-1 text-center border-l border-amber-600"
                                style="background:rgba(239,68,68,.2);">
                                <i class="fas fa-arrow-up mr-1"></i>{{ $isFrench ? 'Sorties' : 'Outputs' }}
                            </th>
                            <th colspan="2" class="px-4 py-1 text-center border-l border-amber-600"
                                style="background:rgba(34,197,94,.2);">
                                <i class="fas fa-check mr-1"></i>{{ $isFrench ? 'Résultat' : 'Result' }}
                            </th>
                        </tr>
                        <tr class="text-xs font-semibold text-white"
                            style="background: linear-gradient(135deg, #D4A574, #B08554);">
                            <th class="px-4 py-2 text-center border-l border-amber-400"
                                style="background:rgba(59,130,246,.2);"
                                title="{{ $isFrench ? 'Quantité trouvée dans l\'inventaire entrant' : 'Quantity found in opening inventory' }}">
                                <i class="fas fa-warehouse mr-1"></i>{{ $isFrench ? 'Trouvé' : 'Found' }}
                            </th>
                            <th class="px-4 py-2 text-center"
                                style="background:rgba(59,130,246,.15);"
                                title="{{ $isFrench ? 'Réceptions reçues durant la période' : 'Receptions during period' }}">
                                <i class="fas fa-truck-loading mr-1"></i>{{ $isFrench ? 'Réception' : 'Reception' }}
                            </th>
                            <th class="px-4 py-2 text-center border-l border-amber-400"
                                style="background:rgba(239,68,68,.15);"
                                title="{{ $isFrench ? 'Produits retournés' : 'Returned products' }}">
                                <i class="fas fa-undo mr-1"></i>{{ $isFrench ? 'Retour' : 'Return' }}
                            </th>
                            <th class="px-4 py-2 text-center"
                                style="background:rgba(239,68,68,.1);"
                                title="{{ $isFrench ? 'Quantité restante dans l\'inventaire sortant' : 'Remaining in closing inventory' }}">
                                <i class="fas fa-boxes mr-1"></i>{{ $isFrench ? 'Restant' : 'Remaining' }}
                            </th>
                            <th class="px-4 py-2 text-center border-l border-amber-400"
                                style="background:rgba(34,197,94,.15);"
                                title="{{ $isFrench ? 'Trouvé + Réception − Retour − Restant' : 'Found + Reception − Return − Remaining' }}">
                                <i class="fas fa-shopping-cart mr-1"></i>{{ $isFrench ? 'Vendus' : 'Sold' }}
                            </th>
                            <th class="px-4 py-2 text-right"
                                style="background:rgba(34,197,94,.1);">
                                <i class="fas fa-coins mr-1"></i>FCFA
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($flux['flux'] ?? [] as $fv)
                            <tr class="bg-amber-50 border-t-2 border-amber-300">
                                <td colspan="8" class="px-4 py-1 text-xs font-bold text-amber-800 uppercase tracking-wide">
                                    <i class="fas fa-user-circle mr-1"></i>{{ $fv['vendeur']['nom'] }}
                                    <span class="ml-2 font-normal text-amber-600">
                                        — {{ $isFrench ? 'Total' : 'Total' }} :
                                        {{ number_format($fv['total_ventes'] ?? 0, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                            </tr>

                            @foreach($fv['produits'] ?? [] as $pf)
                            @php
                                $trouvee  = $pf['quantite_trouvee']  ?? 0;
                                $recue    = $pf['quantite_recue']    ?? 0;
                                $retour   = $pf['quantite_retour']   ?? 0;
                                $restante = $pf['quantite_restante'] ?? 0;
                                $vendue   = $pf['quantite_vendue']   ?? 0;

                                $venteReelle = $trouvee + $recue - $retour - $restante;
                                $isNegative  = $venteReelle < 0 && $vendue == 0;

                                $hasData = $trouvee > 0 || $recue > 0
                                        || $retour > 0 || $restante > 0 || $vendue > 0;
                            @endphp
                            @if($hasData)
                            <tr class="hover:bg-amber-50 transition-colors {{ $isNegative ? 'row-vente-negative' : '' }}">
                                <td class="px-4 py-2 text-gray-400 text-xs"></td>
                                <td class="px-4 py-2 font-semibold text-gray-900">
                                    {{ $pf['produit_nom'] ?? '' }}
                                    @if(!empty($pf['prix_unitaire']))
                                    <span class="text-xs text-gray-400 ml-1">
                                        {{ number_format($pf['prix_unitaire'], 0, ',', ' ') }}F
                                    </span>
                                    @endif
                                    @if($isNegative)
                                        <span class="badge-negatif" title="{{ $isFrench ? 'Vente réelle négative ('.$venteReelle.')' : 'Real sale negative ('.$venteReelle.')' }}">
                                            ⚠ {{ $venteReelle }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-2 text-center font-bold text-purple-600"
                                    style="{{ $isNegative ? '' : 'background:rgba(59,130,246,.04);' }}">
                                    {{ $trouvee }}
                                </td>
                                <td class="px-4 py-2 text-center font-bold text-blue-600"
                                    style="{{ $isNegative ? '' : 'background:rgba(59,130,246,.04);' }}">
                                    {{ $recue }}
                                </td>

                                <td class="px-4 py-2 text-center text-orange-500"
                                    style="{{ $isNegative ? '' : 'background:rgba(239,68,68,.03);' }}">
                                    {{ $retour }}
                                </td>
                                <td class="px-4 py-2 text-center text-gray-500"
                                    style="{{ $isNegative ? '' : 'background:rgba(239,68,68,.03);' }}">
                                    {{ $restante }}
                                </td>

                                <td class="px-4 py-2 text-center font-bold {{ $isNegative ? 'text-red-600' : 'text-green-600' }}"
                                    style="{{ $isNegative ? '' : 'background:rgba(34,197,94,.05);' }}">
                                    {{ $vendue }}
                                </td>
                                <td class="px-4 py-2 text-right font-bold {{ $isNegative ? 'text-red-700' : 'text-gray-800' }}"
                                    style="{{ $isNegative ? '' : 'background:rgba(34,197,94,.05);' }}">
                                    {{ number_format($pf['valeur_vente'] ?? 0, 0, ',', ' ') }}
                                </td>
                            </tr>
                            @endif
                            @endforeach

                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                                <i class="fas fa-inbox text-3xl block mb-2"></i>
                                {{ $isFrench ? 'Aucune activité pour ce jour' : 'No activity for this day' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    @if(!empty($flux['flux']))
                    @php
                        $tT=0;$tR=0;$tRe=0;$tF=0;$tV=0;$tVal=0;
                        foreach($flux['flux'] as $fv){ foreach($fv['produits']??[] as $pf){
                            $tT+=$pf['quantite_trouvee']??0;
                            $tR+=$pf['quantite_recue']??0;
                            $tRe+=$pf['quantite_retour']??0;
                            $tF+=$pf['quantite_restante']??0;
                            $tV+=$pf['quantite_vendue']??0;
                            $tVal+=$pf['valeur_vente']??0;
                        }}
                    @endphp
                    <tfoot class="bg-gray-800 text-white text-sm font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-3">TOTAUX</td>
                            <td class="px-4 py-3 text-center text-purple-300">{{ $tT }}</td>
                            <td class="px-4 py-3 text-center text-blue-300">{{ $tR }}</td>
                            <td class="px-4 py-3 text-center text-orange-300">{{ $tRe }}</td>
                            <td class="px-4 py-3 text-center text-gray-300">{{ $tF }}</td>
                            <td class="px-4 py-3 text-center text-green-300">{{ $tV }}</td>
                            <td class="px-4 py-3 text-right text-yellow-300">{{ number_format($tVal, 0, ',', ' ') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- VUE GLOBALE — affichée par défaut (FIX 2)             --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    @php
        $gI=0;$gR=0;$gRe=0;$gF=0;$gIq=0;$gRq=0;$gReq=0;$gFq=0;
        foreach($flux['flux']??[] as $fv){ foreach($fv['produits']??[] as $pf){
            $pu=$pf['prix_unitaire']??0;
            $gI+=($pf['quantite_trouvee']??0)*$pu;  $gIq+=$pf['quantite_trouvee']??0;
            $gR+=($pf['quantite_recue']??0)*$pu;    $gRq+=$pf['quantite_recue']??0;
            $gRe+=($pf['quantite_retour']??0)*$pu;  $gReq+=$pf['quantite_retour']??0;
            $gF+=($pf['quantite_restante']??0)*$pu; $gFq+=$pf['quantite_restante']??0;
        }}
        $gV=$gI+$gR-$gRe-$gF; $gVq=$gIq+$gRq-$gReq-$gFq;
    @endphp

    <div id="view-summary" style="display:block;" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-purple-500">
                <p class="text-xs text-gray-500">{{ $isFrench ? 'Stock Initial' : 'Initial Stock' }}</p>
                <p class="text-xl font-bold text-purple-600 mt-1">{{ number_format($gI, 0, ',', ' ') }} FCFA</p>
                <p class="text-xs text-gray-400">{{ $gIq }} {{ $isFrench ? 'unités' : 'units' }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
                <p class="text-xs text-gray-500">{{ $isFrench ? 'Réceptions' : 'Receptions' }}</p>
                <p class="text-xl font-bold text-blue-600 mt-1">{{ number_format($gR, 0, ',', ' ') }} FCFA</p>
                <p class="text-xs text-gray-400">{{ $gRq }} {{ $isFrench ? 'unités' : 'units' }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-orange-500">
                <p class="text-xs text-gray-500">{{ $isFrench ? 'Retours' : 'Returns' }}</p>
                <p class="text-xl font-bold text-orange-600 mt-1">{{ number_format($gRe, 0, ',', ' ') }} FCFA</p>
                <p class="text-xs text-gray-400">{{ $gReq }} {{ $isFrench ? 'unités' : 'units' }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-gray-400">
                <p class="text-xs text-gray-500">{{ $isFrench ? 'Stock Final' : 'Final Stock' }}</p>
                <p class="text-xl font-bold text-gray-600 mt-1">{{ number_format($gF, 0, ',', ' ') }} FCFA</p>
                <p class="text-xs text-gray-400">{{ $gFq }} {{ $isFrench ? 'unités' : 'units' }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500 sm:col-span-2">
                <p class="text-xs text-gray-500">{{ $isFrench ? 'Total Vendu' : 'Total Sold' }}</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($gV, 0, ',', ' ') }} FCFA</p>
                <p class="text-xs text-gray-400">{{ $gVq }} {{ $isFrench ? 'unités' : 'units' }}</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-gray-700">
            <strong>{{ $isFrench ? 'Formule' : 'Formula' }} :</strong>
            Stock Initial + Réceptions − Retours − Stock Final =
            {{ number_format($gI,0,',',' ') }} + {{ number_format($gR,0,',',' ') }}
            − {{ number_format($gRe,0,',',' ') }} − {{ number_format($gF,0,',',' ') }}
            = <strong class="text-green-700">{{ number_format($gV, 0, ',', ' ') }} FCFA</strong>
        </div>

        @if(!empty($flux['flux']))
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="bg-gray-800 text-white px-4 py-3 text-sm font-bold">
                {{ $isFrench ? 'Détail par vendeur' : 'Detail by seller' }}
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-600">{{ $isFrench ? 'Vendeur' : 'Seller' }}</th>
                        <th class="px-4 py-2 text-center text-gray-600">{{ $isFrench ? 'Produits' : 'Products' }}</th>
                        <th class="px-4 py-2 text-right text-gray-600">{{ $isFrench ? 'Total' : 'Total' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($flux['flux'] as $fv)
                    <tr class="hover:bg-amber-50">
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $fv['vendeur']['nom'] }}</td>
                        <td class="px-4 py-2 text-center text-gray-600">{{ count($fv['produits'] ?? []) }}</td>
                        <td class="px-4 py-2 text-right font-bold text-amber-600">{{ number_format($fv['total_ventes'] ?? 0, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- VUE CALCULATEUR MANQUANT                                   --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div id="view-manquant" style="display:none;">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-xl mx-auto">

            <div style="background: linear-gradient(135deg, #b45309, #d97706); padding: 1.25rem 1.5rem;">
                <h2 style="color:#fff; font-size:1.1rem; font-weight:700; margin:0;">
                    <i class="fas fa-search-dollar mr-2"></i>
                    {{ $isFrench ? 'Calculer & Valider le Manquant' : 'Calculate & Validate Missing Amount' }}
                </h2>
                <p style="color:#fde68a; font-size:.75rem; margin:.25rem 0 0;">
                    {{ $isFrench ? 'Vérification de caisse — données sauvegardées par vendeur/jour' : 'Cash check — data saved per seller/day' }}
                </p>
            </div>

            <div id="alertManquantExist" style="display:none; background:#fef3c7; border-bottom:2px solid #fbbf24; padding:.75rem 1.25rem; font-size:.8rem; color:#92400e;">
                <i class="fas fa-info-circle mr-1"></i>
                {{ $isFrench ? 'Un manquant est déjà enregistré pour ce vendeur à cette date. La validation écrasera les données existantes.' : 'A missing amount is already saved for this seller on this date. Validating will overwrite it.' }}
                <a id="lienVueManquants" href="{{ route('pdg.manquants') }}" class="ml-2 underline font-bold">
                    {{ $isFrench ? 'Voir les manquants' : 'View missing amounts' }}
                </a>
            </div>

            <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">

                {{-- Sélecteur vendeur --}}
                {{-- FIX 1 : data-total calculé via la formule (trouvé+reçu−retour−restant)×prix --}}
                <div>
                    <label style="display:block; font-size:.8rem; font-weight:600; color:#374151; margin-bottom:.35rem;">
                        <i class="fas fa-user mr-1" style="color:#d97706;"></i>
                        {{ $isFrench ? 'Vendeur' : 'Seller' }}
                    </label>
                    <select id="mVendeur" onchange="mVendeurChange()"
                            style="width:100%; padding:.55rem .85rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fff;">
                        <option value="">— {{ $isFrench ? 'Choisir un vendeur' : 'Choose a seller' }} —</option>
                        @foreach($flux['flux'] ?? [] as $fv)
                        @php
                            $totalFormule = 0;
                            foreach ($fv['produits'] ?? [] as $pf) {
                                $venteReelleProd = ($pf['quantite_trouvee'] ?? 0)
                                                 + ($pf['quantite_recue']   ?? 0)
                                                 - ($pf['quantite_retour']  ?? 0)
                                                 - ($pf['quantite_restante']?? 0);
                                $totalFormule += $venteReelleProd * ($pf['prix_unitaire'] ?? 0);
                            }
                        @endphp
                        <option value="{{ $fv['vendeur']['id'] ?? '' }}"
                                data-total="{{ $totalFormule }}"
                                data-nom="{{ $fv['vendeur']['nom'] ?? '' }}">
                            {{ $fv['vendeur']['nom'] }} — {{ number_format($totalFormule, 0, ',', ' ') }} FCFA
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:.8rem; font-weight:600; color:#374151; margin-bottom:.35rem;">
                        <i class="fas fa-tag mr-1" style="color:#d97706;"></i>
                        {{ $isFrench ? 'Total ventes attendu' : 'Expected total sales' }}
                    </label>
                    <input type="number" id="mAttendu" placeholder="0" min="0" oninput="mCalculer()"
                           style="width:100%; padding:.55rem .85rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fef3c7; color:#92400e; font-weight:700; box-sizing:border-box;">
                    <p style="font-size:.7rem; color:#9ca3af; margin:.3rem 0 0;">
                        {{ $isFrench ? 'Pré-rempli via la formule : Trouvé + Reçu − Retour − Restant, modifiable si besoin' : 'Pre-filled via formula: Found + Received − Return − Remaining, editable if needed' }}
                    </p>
                </div>

                <hr style="border:none; border-top:1px solid #e5e7eb;">

                <div style="background:#eff6ff; border:2px solid #bfdbfe; border-radius:.5rem; padding:.85rem 1rem;">
                    <label style="display:block; font-size:.8rem; font-weight:700; color:#1d4ed8; margin-bottom:.35rem;">
                        <i class="fas fa-cash-register mr-1"></i>
                        {{ $isFrench ? 'Fond de caisse (ajouté au total attendu)' : 'Cash float (added to expected total)' }}
                    </label>
                    <input type="number" id="mFondCaisse" placeholder="0" min="0" oninput="mCalculer()"
                           style="width:100%; padding:.55rem .85rem; border:2px solid #93c5fd; border-radius:.5rem; font-size:.9rem; background:#fff; color:#1e40af; font-weight:600; box-sizing:border-box;">
                </div>

                <p style="font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin:0;">
                    <i class="fas fa-money-bill-wave mr-1" style="color:#d97706;"></i>
                    {{ $isFrench ? 'Versements' : 'Payments' }}
                </p>

                <div class="form-row">
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">{{ $isFrench ? 'Versement 1' : 'Payment 1' }}</label>
                        <input type="number" id="mV1" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fff; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">{{ $isFrench ? 'Versement 2' : 'Payment 2' }}</label>
                        <input type="number" id="mV2" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fff; box-sizing:border-box;">
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">{{ $isFrench ? 'Versement 3' : 'Payment 3' }}</label>
                        <input type="number" id="mV3" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fff; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">{{ $isFrench ? 'Versement Extra' : 'Extra Payment' }}</label>
                        <input type="number" id="mVExtra" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #86efac; border-radius:.5rem; font-size:.9rem; background:#f0fdf4; box-sizing:border-box;">
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid #e5e7eb;">

                <p style="font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin:0;">
                    <i class="fas fa-mobile-alt mr-1" style="color:#d97706;"></i>Mobile Money
                </p>

                <div class="form-row">
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">OM {{ $isFrench ? 'Final' : 'Final' }} (Orange)</label>
                        <input type="number" id="mOmF" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #fdba74; border-radius:.5rem; font-size:.9rem; background:#fff7ed; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">MoMo {{ $isFrench ? 'Final' : 'Final' }} (MTN)</label>
                        <input type="number" id="mMomoF" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #fcd34d; border-radius:.5rem; font-size:.9rem; background:#fffbeb; box-sizing:border-box;">
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">
                        <i class="fas fa-comment mr-1"></i>
                        {{ $isFrench ? 'Notes (optionnel)' : 'Notes (optional)' }}
                    </label>
                    <textarea id="mNotes" placeholder="..." rows="2"
                              style="width:100%; padding:.5rem .75rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; box-sizing:border-box; resize:none;"></textarea>
                </div>

                <p style="font-size:.72rem; color:#9ca3af; background:#f9fafb; border:1px solid #e5e7eb; border-radius:.5rem; padding:.6rem .85rem; margin:0;">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ $isFrench ? 'Formule' : 'Formula' }} :
                    (Total Vendu + Fond de caisse) − (V1 + V2 + V3 + V.Extra + OM Final + MoMo Final)
                </p>

                <div id="mResultat" style="display:none;">
                    <div id="mResultatBox" style="border-radius:.75rem; padding:1rem 1.25rem;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:.4rem;">
                            <span style="font-size:.875rem; color:#4b5563; font-weight:600;">{{ $isFrench ? 'Total ventes (formule)' : 'Total sales (formula)' }}</span>
                            <span id="mResAttendu" style="font-weight:700; color:#1f2937;"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:.4rem;">
                            <span style="font-size:.875rem; color:#1d4ed8; font-weight:600;">
                                <i class="fas fa-cash-register" style="font-size:.75rem; margin-right:.2rem;"></i>
                                {{ $isFrench ? '+ Fond de caisse' : '+ Cash float' }}
                            </span>
                            <span id="mResFond" style="font-weight:700; color:#1d4ed8;"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:.4rem; padding-top:.4rem; border-top:1px dashed #e5e7eb;">
                            <span style="font-size:.875rem; color:#4b5563; font-weight:600;">{{ $isFrench ? 'Total attendu' : 'Total expected' }}</span>
                            <span id="mResAttenduNet" style="font-weight:700; color:#1f2937;"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:.4rem;">
                            <span style="font-size:.875rem; color:#4b5563; font-weight:600;">{{ $isFrench ? 'Total versé' : 'Total paid' }}</span>
                            <span id="mResPercu" style="font-weight:700; color:#1f2937;"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:.75rem; font-size:.75rem; color:#9ca3af;">
                            <span>V1 + V2 + V3 + Extra + OM + MoMo</span>
                            <span id="mResDetail" style="font-style:italic;"></span>
                        </div>
                        <div style="border-top:1px solid #e5e7eb; padding-top:.75rem; display:flex; justify-content:space-between; align-items:center;">
                            <span id="mResLabel" style="font-size:1rem; font-weight:700;"></span>
                            <span id="mResValeur" style="font-size:1.5rem; font-weight:700;"></span>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:.75rem; justify-content:flex-end; padding-top:.5rem; border-top:1px solid #e5e7eb; flex-wrap:wrap;">
                    <button onclick="mReset()"
                            style="padding:.5rem 1.25rem; background:#f3f4f6; border:none; border-radius:.5rem; font-size:.875rem; font-weight:600; color:#374151; cursor:pointer;"
                            onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <i class="fas fa-redo mr-1"></i>{{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                    </button>
                    <button onclick="mValider()" id="btnValider"
                            style="display:none; padding:.5rem 1.5rem; background:#dc2626; border:none; border-radius:.5rem; font-size:.875rem; font-weight:700; color:#fff; cursor:pointer;"
                            onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                        <i class="fas fa-check mr-1"></i>{{ $isFrench ? 'Valider le manquant' : 'Validate missing' }}
                    </button>
                </div>

                <div id="mValidationMsg" style="display:none; border-radius:.5rem; padding:.75rem 1rem; font-size:.85rem; font-weight:600; text-align:center;"></div>

            </div>
        </div>
    </div>

</div>
</div>

<script>
(function(){
    function el(id){ return document.getElementById(id); }
    function n(id){ return parseFloat(el(id)?.value) || 0; }
    function fmt(v){ return Math.round(v).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '\u00a0'); }

    const fluxDate = '{{ $selectedDate ?? "" }}';

    window.mVendeurChange = function(){
        const sel   = el('mVendeur');
        const opt   = sel.options[sel.selectedIndex];
        // FIX 1 : data-total contient désormais le total calculé par formule côté PHP
        const tot   = (opt && opt.dataset.total) ? parseFloat(opt.dataset.total) : 0;
        const vendId= sel.value;

        el('mAttendu').value = tot > 0 ? tot : '';
        el('mResultat').style.display = 'none';
        el('btnValider').style.display = 'none';
        el('mValidationMsg').style.display = 'none';
        el('alertManquantExist').style.display = 'none';

        if (vendId && fluxDate) {
            fetch(`/pdg/manquants/check?vendeur_id=${vendId}&date=${fluxDate}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.exists) {
                    el('alertManquantExist').style.display = 'block';
                    if (data.manquant) {
                        const m = data.manquant;
                        // FIX 1 : on ne pré-remplit PAS mAttendu depuis la DB (total_ventes),
                        // on garde la valeur issue de la formule déjà chargée via data-total
                        setIfPos('mFondCaisse',m.fond_caisse);
                        setIfPos('mV1',        m.versement_1);
                        setIfPos('mV2',        m.versement_2);
                        setIfPos('mV3',        m.versement_3);
                        setIfPos('mVExtra',    m.versement_extra);
                        setIfPos('mOmF',       m.om_final);
                        setIfPos('mMomoF',     m.momo_final);
                        if (m.notes) el('mNotes').value = m.notes;
                        mCalculer();
                    }
                }
            })
            .catch(() => {});
        }

        mCalculer();
    };

    function setIfPos(id, val) {
        if (val > 0) el(id).value = val;
    }

    window.mCalculer = function(){
        const attendu     = parseFloat(el('mAttendu')?.value) || 0;
        const fondCaisse  = n('mFondCaisse');
        const v1          = n('mV1');
        const v2          = n('mV2');
        const v3          = n('mV3');
        const vExtra      = n('mVExtra');
        const omF         = n('mOmF');
        const momoF       = n('mMomoF');

        const attenduNet  = attendu + fondCaisse;
        const totalVerse  = v1 + v2 + v3 + vExtra + omF + momoF;

        if(attendu === 0 && totalVerse === 0 && fondCaisse === 0){
            el('mResultat').style.display = 'none';
            el('btnValider').style.display = 'none';
            return;
        }

        const manquant = attenduNet - totalVerse;
        const box      = el('mResultatBox');
        const label    = el('mResLabel');
        const val      = el('mResValeur');

        el('mResAttendu').textContent    = fmt(attendu)     + ' FCFA';
        el('mResFond').textContent       = '+ ' + fmt(fondCaisse) + ' FCFA';
        el('mResAttenduNet').textContent = fmt(attenduNet)  + ' FCFA';
        el('mResPercu').textContent      = fmt(totalVerse)  + ' FCFA';
        el('mResDetail').textContent     =
            fmt(v1)+' + '+fmt(v2)+' + '+fmt(v3)+
            ' + '+fmt(vExtra)+' + '+fmt(omF)+' + '+fmt(momoF);

        if(manquant > 0.5){
            box.style.background = '#fef2f2';
            box.style.border     = '2px solid #fca5a5';
            label.textContent    = '{{ $isFrench ? "⚠️ Manquant" : "⚠️ Missing" }}';
            label.style.color    = '#dc2626';
            val.textContent      = '− ' + fmt(manquant) + ' FCFA';
            val.style.color      = '#dc2626';
        } else if(manquant < -0.5){
            box.style.background = '#f0fdf4';
            box.style.border     = '2px solid #86efac';
            label.textContent    = '{{ $isFrench ? "✅ Excédent" : "✅ Surplus" }}';
            label.style.color    = '#16a34a';
            val.textContent      = '+ ' + fmt(Math.abs(manquant)) + ' FCFA';
            val.style.color      = '#16a34a';
        } else {
            box.style.background = '#f0fdf4';
            box.style.border     = '2px solid #86efac';
            label.textContent    = '{{ $isFrench ? "✅ Caisse juste" : "✅ Exact" }}';
            label.style.color    = '#16a34a';
            val.textContent      = '0 FCFA';
            val.style.color      = '#16a34a';
        }

        el('mResultat').style.display = 'block';

        const hasVendeur = el('mVendeur')?.value;
        el('btnValider').style.display = hasVendeur ? 'inline-block' : 'none';
    };

    window.mReset = function(){
        ['mVendeur','mAttendu','mFondCaisse','mV1','mV2','mV3','mVExtra','mOmF','mMomoF','mNotes']
            .forEach(function(id){ const e=el(id); if(e) e.value=''; });
        el('mResultat').style.display = 'none';
        el('btnValider').style.display = 'none';
        el('mValidationMsg').style.display = 'none';
        el('alertManquantExist').style.display = 'none';
    };

    window.mValider = function(){
        const vendeurId = el('mVendeur')?.value;
        if (!vendeurId) {
            alert('{{ $isFrench ? "Veuillez sélectionner un vendeur" : "Please select a seller" }}');
            return;
        }
        if (!fluxDate) {
            alert('{{ $isFrench ? "Date invalide" : "Invalid date" }}');
            return;
        }

        const btn = el('btnValider');
        btn.disabled = true;
        btn.textContent = '{{ $isFrench ? "Enregistrement..." : "Saving..." }}';

        const body = new URLSearchParams({
            vendeur_id:      vendeurId,
            date_manquant:   fluxDate,
            total_ventes:    el('mAttendu')?.value    || 0,
            fond_caisse:     el('mFondCaisse')?.value || 0,
            versement_1:     el('mV1')?.value         || 0,
            versement_2:     el('mV2')?.value         || 0,
            versement_3:     el('mV3')?.value         || 0,
            versement_extra: el('mVExtra')?.value     || 0,
            om_final:        el('mOmF')?.value        || 0,
            momo_final:      el('mMomoF')?.value      || 0,
            notes:           el('mNotes')?.value      || '',
            _token:          document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
        });

        fetch('{{ route("pdg.manquants.valider") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: body.toString(),
        })
        .then(r => r.json())
        .then(data => {
            const msgBox = el('mValidationMsg');
            if (data.success) {
                msgBox.style.background = '#f0fdf4';
                msgBox.style.border     = '2px solid #86efac';
                msgBox.style.color      = '#16a34a';
                msgBox.innerHTML = '<i class="fas fa-check-circle mr-1"></i> {{ $isFrench ? "Manquant enregistré avec succès !" : "Missing amount saved!" }}' +
                    ' <a href="{{ route("pdg.manquants") }}" style="text-decoration:underline; font-weight:bold; margin-left:.5rem;">{{ $isFrench ? "Voir les manquants" : "View missing amounts" }}</a>';
                el('alertManquantExist').style.display = 'block';
            } else {
                msgBox.style.background = '#fef2f2';
                msgBox.style.border     = '2px solid #fca5a5';
                msgBox.style.color      = '#dc2626';
                msgBox.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> ' + (data.message || 'Erreur');
            }
            msgBox.style.display = 'block';
            btn.disabled     = false;
            btn.innerHTML    = '<i class="fas fa-check mr-1"></i>{{ $isFrench ? "Valider le manquant" : "Validate missing" }}';
        })
        .catch(() => {
            el('mValidationMsg').style.display  = 'block';
            el('mValidationMsg').style.background = '#fef2f2';
            el('mValidationMsg').style.color      = '#dc2626';
            el('mValidationMsg').textContent      = '{{ $isFrench ? "Erreur réseau" : "Network error" }}';
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-check mr-1"></i>{{ $isFrench ? "Valider le manquant" : "Validate missing" }}';
        });
    };
})();
</script>
@endsection