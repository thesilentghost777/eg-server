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
                <button onclick="document.getElementById('view-table').style.display='block'; document.getElementById('view-summary').style.display='none'; document.getElementById('view-manquant').style.display='none';"
                        class="px-4 py-2 bg-white text-amber-700 rounded-lg font-semibold hover:bg-amber-50">
                    <i class="fas fa-th-list mr-1"></i>{{ $isFrench ? 'Tableau' : 'Table' }}
                </button>
                <button onclick="document.getElementById('view-table').style.display='none'; document.getElementById('view-summary').style.display='block'; document.getElementById('view-manquant').style.display='none';"
                        class="px-4 py-2 bg-white text-amber-700 rounded-lg font-semibold hover:bg-amber-50">
                    <i class="fas fa-calculator mr-1"></i>{{ $isFrench ? 'Global' : 'Global' }}
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
    {{-- VUE TABLEAU                                            --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div id="view-table" style="display:block;">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="table-scroll">
                <table class="w-full text-sm">
                    <thead class="sticky-header text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">{{ $isFrench ? 'Vendeur' : 'Seller' }}</th>
                            <th class="px-4 py-3 text-left">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                            <th class="px-4 py-3 text-center">{{ $isFrench ? 'Reçu' : 'Received' }}</th>
                            <th class="px-4 py-3 text-center">{{ $isFrench ? 'Stock Ini.' : 'Init. Stock' }}</th>
                            <th class="px-4 py-3 text-center">{{ $isFrench ? 'Vendus' : 'Sold' }}</th>
                            <th class="px-4 py-3 text-center">{{ $isFrench ? 'Retours' : 'Returns' }}</th>
                            <th class="px-4 py-3 text-center">{{ $isFrench ? 'Stock Fin.' : 'Final Stock' }}</th>
                            <th class="px-4 py-3 text-right">{{ $isFrench ? 'Valeur (FCFA)' : 'Value (FCFA)' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($flux['flux'] ?? [] as $fv)
                            @foreach($fv['produits'] ?? [] as $pf)
                            <tr class="hover:bg-amber-50">
                                <td class="px-4 py-2 text-gray-800 font-medium">{{ $fv['vendeur']['nom'] }}</td>
                                <td class="px-4 py-2 font-semibold text-gray-900">
                                    {{ $pf['produit_nom'] ?? '' }}
                                    @if(!empty($pf['prix_unitaire']))
                                    <span class="text-xs text-gray-400 ml-1">{{ number_format($pf['prix_unitaire'], 0, ',', ' ') }}F</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center font-bold text-blue-600">{{ $pf['quantite_recue'] ?? 0 }}</td>
                                <td class="px-4 py-2 text-center text-purple-600">{{ $pf['quantite_trouvee'] ?? 0 }}</td>
                                <td class="px-4 py-2 text-center font-bold text-green-600">{{ $pf['quantite_vendue'] ?? 0 }}</td>
                                <td class="px-4 py-2 text-center text-orange-500">{{ $pf['quantite_retour'] ?? 0 }}</td>
                                <td class="px-4 py-2 text-center text-gray-600">{{ $pf['quantite_restante'] ?? 0 }}</td>
                                <td class="px-4 py-2 text-right font-bold text-gray-800">{{ number_format($pf['valeur_vente'] ?? 0, 0, ',', ' ') }}</td>
                            </tr>
                            @endforeach
                        @empty
                        <tr><td colspan="8" class="px-4 py-10 text-center text-gray-400">
                            <i class="fas fa-inbox text-3xl block mb-2"></i>
                            {{ $isFrench ? 'Aucune activité' : 'No activity' }}
                        </td></tr>
                        @endforelse
                    </tbody>
                    @if(!empty($flux['flux']))
                    @php
                        $tR=0;$tI=0;$tV=0;$tRe=0;$tF=0;$tVal=0;
                        foreach($flux['flux'] as $fv){ foreach($fv['produits']??[] as $pf){
                            $tR+=$pf['quantite_recue']??0; $tI+=$pf['quantite_trouvee']??0;
                            $tV+=$pf['quantite_vendue']??0; $tRe+=$pf['quantite_retour']??0;
                            $tF+=$pf['quantite_restante']??0; $tVal+=$pf['valeur_vente']??0;
                        }}
                    @endphp
                    <tfoot class="bg-gray-800 text-white text-sm font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-3">TOTAUX</td>
                            <td class="px-4 py-3 text-center text-blue-300">{{ $tR }}</td>
                            <td class="px-4 py-3 text-center text-purple-300">{{ $tI }}</td>
                            <td class="px-4 py-3 text-center text-green-300">{{ $tV }}</td>
                            <td class="px-4 py-3 text-center text-orange-300">{{ $tRe }}</td>
                            <td class="px-4 py-3 text-center">{{ $tF }}</td>
                            <td class="px-4 py-3 text-right text-yellow-300">{{ number_format($tVal, 0, ',', ' ') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- VUE GLOBALE                                            --}}
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

    <div id="view-summary" style="display:none;" class="space-y-4">
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

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- VUE CALCULATEUR MANQUANT                              --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div id="view-manquant" style="display:none;">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-xl mx-auto">

            {{-- Titre --}}
            <div style="background: linear-gradient(135deg, #b45309, #d97706); padding: 1.25rem 1.5rem;">
                <h2 style="color:#fff; font-size:1.1rem; font-weight:700; margin:0;">
                    <i class="fas fa-search-dollar mr-2"></i>
                    {{ $isFrench ? 'Calculer le Manquant' : 'Calculate Missing Amount' }}
                </h2>
                <p style="color:#fde68a; font-size:.75rem; margin:.25rem 0 0;">
                    {{ $isFrench ? 'Vérification de caisse par vendeur' : 'Cash verification by seller' }}
                </p>
            </div>

            <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">

                {{-- Vendeur --}}
                <div>
                    <label style="display:block; font-size:.8rem; font-weight:600; color:#374151; margin-bottom:.35rem;">
                        <i class="fas fa-user mr-1" style="color:#d97706;"></i>
                        {{ $isFrench ? 'Vendeur' : 'Seller' }}
                    </label>
                    <select id="mVendeur" onchange="mVendeurChange()"
                            style="width:100%; padding:.55rem .85rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fff;">
                        <option value="">— {{ $isFrench ? 'Choisir un vendeur' : 'Choose a seller' }} —</option>
                        @foreach($flux['flux'] ?? [] as $fv)
                        <option value="{{ $fv['vendeur']['id'] ?? '' }}" data-total="{{ $fv['total_ventes'] ?? 0 }}">
                            {{ $fv['vendeur']['nom'] }} — {{ number_format($fv['total_ventes'] ?? 0, 0, ',', ' ') }} FCFA
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Total ventes attendu (readonly) --}}
                <div>
                    <label style="display:block; font-size:.8rem; font-weight:600; color:#374151; margin-bottom:.35rem;">
                        <i class="fas fa-tag mr-1" style="color:#d97706;"></i>
                        {{ $isFrench ? 'Total ventes attendu' : 'Expected total sales' }}
                    </label>
                    <input type="number" id="mAttendu" readonly placeholder="0"
                           style="width:100%; padding:.55rem .85rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fef3c7; color:#92400e; font-weight:700; box-sizing:border-box;">
                </div>

                <hr style="border:none; border-top:1px solid #e5e7eb;">

                {{-- Versements --}}
                <p style="font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin:0;">
                    <i class="fas fa-money-bill-wave mr-1" style="color:#d97706;"></i>
                    {{ $isFrench ? 'Versements' : 'Payments' }}
                </p>

                <div class="form-row">
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">
                            {{ $isFrench ? 'Versement 1' : 'Payment 1' }}
                        </label>
                        <input type="number" id="mV1" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fff; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">
                            {{ $isFrench ? 'Versement 2' : 'Payment 2' }}
                        </label>
                        <input type="number" id="mV2" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fff; box-sizing:border-box;">
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">
                            {{ $isFrench ? 'Versement 3' : 'Payment 3' }}
                        </label>
                        <input type="number" id="mV3" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.9rem; background:#fff; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">
                            {{ $isFrench ? 'Versement Extra' : 'Extra Payment' }}
                        </label>
                        <input type="number" id="mVExtra" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #86efac; border-radius:.5rem; font-size:.9rem; background:#f0fdf4; box-sizing:border-box;">
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid #e5e7eb;">

                {{-- Mobile Money --}}
                <p style="font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin:0;">
                    <i class="fas fa-mobile-alt mr-1" style="color:#d97706;"></i>
                    Mobile Money
                </p>

                <div class="form-row">
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">
                            OM {{ $isFrench ? 'Final' : 'Final' }} (Orange)
                        </label>
                        <input type="number" id="mOmF" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #fdba74; border-radius:.5rem; font-size:.9rem; background:#fff7ed; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:.2rem;">
                            MoMo {{ $isFrench ? 'Final' : 'Final' }} (MTN)
                        </label>
                        <input type="number" id="mMomoF" placeholder="0" min="0" oninput="mCalculer()"
                               style="width:100%; padding:.5rem .75rem; border:2px solid #fcd34d; border-radius:.5rem; font-size:.9rem; background:#fffbeb; box-sizing:border-box;">
                    </div>
                </div>

                {{-- Formule rappel --}}
                <p style="font-size:.72rem; color:#9ca3af; background:#f9fafb; border:1px solid #e5e7eb; border-radius:.5rem; padding:.6rem .85rem; margin:0;">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ $isFrench ? 'Formule' : 'Formula' }} :
                    Total Vendu − (V1 + V2 + V3 + V.Extra + OM Final + MoMo Final)
                </p>

                {{-- RÉSULTAT --}}
                <div id="mResultat" style="display:none;">
                    <div id="mResultatBox" style="border-radius:.75rem; padding:1rem 1.25rem;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:.4rem;">
                            <span style="font-size:.875rem; color:#4b5563; font-weight:600;">{{ $isFrench ? 'Attendu' : 'Expected' }}</span>
                            <span id="mResAttendu" style="font-weight:700; color:#1f2937;"></span>
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

                {{-- Bouton reset --}}
                <button onclick="mReset()"
                        style="padding:.5rem 1.25rem; background:#f3f4f6; border:none; border-radius:.5rem; font-size:.875rem; font-weight:600; color:#374151; cursor:pointer; align-self:flex-end;"
                        onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <i class="fas fa-redo mr-1"></i>{{ $isFrench ? 'Réinitialiser' : 'Reset' }}
                </button>

            </div>
        </div>
    </div>

</div>
</div>

<script>
(function(){

    function el(id){ return document.getElementById(id); }
    function n(id){ return parseFloat(el(id).value) || 0; }
    function fmt(v){ return Math.round(v).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '\u00a0'); }

    /* Vendeur sélectionné → remplir le montant attendu */
    window.mVendeurChange = function(){
        var sel = el('mVendeur');
        var opt = sel.options[sel.selectedIndex];
        var tot = (opt && opt.dataset.total) ? parseFloat(opt.dataset.total) : 0;
        el('mAttendu').value = tot > 0 ? tot : '';
        el('mResultat').style.display = 'none';
        window.mCalculer();
    };

    /* Calcul principal : manquant = attendu − (v1+v2+v3+vextra+omfinal+momofinal) */
    window.mCalculer = function(){
        var attendu = parseFloat(el('mAttendu').value) || 0;

        var v1     = n('mV1');
        var v2     = n('mV2');
        var v3     = n('mV3');
        var vExtra = n('mVExtra');
        var omF    = n('mOmF');
        var momoF  = n('mMomoF');

        var totalVerse = v1 + v2 + v3 + vExtra + omF + momoF;

        if(attendu === 0 && totalVerse === 0){
            el('mResultat').style.display = 'none';
            return;
        }

        var manquant = attendu - totalVerse;
        var box   = el('mResultatBox');
        var label = el('mResLabel');
        var val   = el('mResValeur');

        el('mResAttendu').textContent = fmt(attendu)     + ' FCFA';
        el('mResPercu').textContent   = fmt(totalVerse)  + ' FCFA';
        el('mResDetail').textContent  =
            fmt(v1) + ' + ' + fmt(v2) + ' + ' + fmt(v3) +
            ' + ' + fmt(vExtra) + ' + ' + fmt(omF) + ' + ' + fmt(momoF);

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
    };

    /* Reset complet */
    window.mReset = function(){
        ['mVendeur', 'mAttendu', 'mV1', 'mV2', 'mV3', 'mVExtra', 'mOmF', 'mMomoF']
            .forEach(function(id){ var e = el(id); if(e) e.value = ''; });
        el('mResultat').style.display = 'none';
    };

})();
</script>
@endsection