@extends('layouts.app')

@section('title', $isFrench ? 'Espace Manquants' : 'Missing Amounts')

@section('content')
<style>
:root {
    --red-light: #fef2f2;
    --red-border: #fca5a5;
    --red-text:   #dc2626;
    --green-light: #f0fdf4;
    --green-border:#86efac;
    --green-text:  #16a34a;
    --amber-main:  #b45309;
    --amber-light: #fef3c7;
}

.cal-grid { display:grid; gap:0; border:1px solid #e5e7eb; border-radius:.75rem; overflow:hidden; }

.cal-header-row {
    display:grid;
    grid-template-columns: 70px 1fr 1fr 1fr 1fr 1fr;
    background:linear-gradient(135deg,#92400e,#b45309);
    color:#fff; font-size:.68rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.05em;
}
.cal-header-row > div { padding:.5rem .6rem; border-right:1px solid rgba(255,255,255,.15); }
.cal-header-row > div:last-child { border-right:none; }

.cal-day-row {
    display:grid;
    grid-template-columns: 70px 1fr 1fr 1fr 1fr 1fr;
    border-top:1px solid #f3f4f6;
    transition:background .15s; cursor:pointer;
}
.cal-day-row:hover { background:#fffbeb; }
.cal-day-row.weekend { background:#fafafa; }
.cal-day-row.has-manquant-rouge { background:#fff5f5; }
.cal-day-row.has-manquant-vert  { background:#f0fdf4; }
.cal-day-row.futur { opacity:.4; pointer-events:none; }

.cal-day-row > div { padding:.4rem .6rem; border-right:1px solid #f3f4f6; font-size:.75rem; }
.cal-day-row > div:last-child { border-right:none; }

.cal-day-num { font-weight:700; color:var(--amber-main); font-size:.8rem; line-height:1.2; }
.cal-day-sub { font-size:.58rem; color:#9ca3af; text-transform:capitalize; }

.num-cell { font-size:.75rem; font-weight:600; white-space:nowrap; }
.num-cell .cur { font-size:.6rem; color:#9ca3af; }

.badge-vendeur {
    display:inline-block; font-size:.6rem; font-weight:700;
    padding:.08rem .3rem; border-radius:.25rem; margin:.08rem;
    white-space:nowrap;
}
.badge-manquant { background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; }
.badge-excedent { background:#dcfce7; color:#16a34a; border:1px solid #86efac; }
.badge-exact    { background:#f3f4f6; color:#6b7280; border:1px solid #e5e7eb; }

.vendeur-card { background:#fff; border-radius:.75rem; box-shadow:0 1px 4px rgba(0,0,0,.08); overflow:hidden; border:1px solid #e5e7eb; }
.vendeur-card-header { display:flex; justify-content:space-between; align-items:center; padding:.75rem 1rem; cursor:pointer; background:#f9fafb; border-bottom:1px solid #e5e7eb; user-select:none; }
.vendeur-card-body { display:none; padding:0; }
.vendeur-card-body.open { display:block; }
.vendeur-detail-table { width:100%; font-size:.76rem; border-collapse:collapse; }
.vendeur-detail-table th { background:#f3f4f6; padding:.45rem .65rem; text-align:left; font-weight:600; color:#6b7280; font-size:.68rem; }
.vendeur-detail-table td { padding:.45rem .65rem; border-top:1px solid #f3f4f6; }

.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
.modal-overlay.open { display:flex; }
.modal-box { background:#fff; border-radius:1rem; width:100%; max-width:500px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.25); }
.modal-header { background:linear-gradient(135deg,#b45309,#d97706); padding:1rem 1.25rem; border-radius:1rem 1rem 0 0; display:flex; justify-content:space-between; align-items:center; }
.modal-body { padding:1rem 1.25rem; }
.form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:.6rem; }
.inp { width:100%; padding:.45rem .65rem; border:2px solid #e5e7eb; border-radius:.5rem; font-size:.85rem; box-sizing:border-box; background:#fff; }
.inp:focus { outline:none; border-color:#d97706; }
.lbl { display:block; font-size:.72rem; font-weight:600; color:#374151; margin-bottom:.2rem; }

.res-box { border-radius:.5rem; padding:.7rem 1rem; display:flex; justify-content:space-between; align-items:center; font-weight:700; font-size:.95rem; margin-top:.65rem; }
.res-manquant { background:var(--red-light);   border:2px solid var(--red-border);   color:var(--red-text); }
.res-excedent { background:var(--green-light); border:2px solid var(--green-border); color:var(--green-text); }
.res-exact    { background:var(--green-light); border:2px solid var(--green-border); color:var(--green-text); }

::-webkit-scrollbar { width:5px; height:5px; }
::-webkit-scrollbar-track { background:#f9fafb; }
::-webkit-scrollbar-thumb { background:#d1d5db; border-radius:3px; }

@media (max-width:640px) {
    .cal-header-row, .cal-day-row { grid-template-columns:60px 1fr 1fr 1fr 1fr; }
    .col-retour-cal { display:none; }
}
</style>

<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-red-50">
<div class="container mx-auto px-4 py-6">

{{-- HEADER --}}
<div class="bg-gradient-to-r from-red-700 to-red-500 rounded-2xl shadow-xl p-5 mb-5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-white mb-1">
                <i class="fas fa-search-dollar mr-2"></i>
                {{ $isFrench ? 'Espace Manquants' : 'Missing Amounts' }}
            </h1>
            <p class="text-red-100 text-xs">{{ $dateDebut->locale('fr')->isoFormat('MMMM YYYY') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pdg.manquants', ['mois' => $moisPrecedent->month, 'annee' => $moisPrecedent->year]) }}"
               class="px-3 py-1.5 bg-white/20 text-white rounded-lg hover:bg-white/30 font-semibold text-sm">
                <i class="fas fa-chevron-left"></i>
            </a>
            <span class="px-3 py-1.5 bg-white text-red-700 rounded-lg font-bold text-sm">
                {{ $dateDebut->locale('fr')->isoFormat('MMM YYYY') }}
            </span>
            <a href="{{ route('pdg.manquants', ['mois' => $moisSuivant->month, 'annee' => $moisSuivant->year]) }}"
               class="px-3 py-1.5 bg-white/20 text-white rounded-lg hover:bg-white/30 font-semibold text-sm">
                <i class="fas fa-chevron-right"></i>
            </a>
            <a href="{{ route('pdg.manquants') }}" class="px-3 py-1.5 bg-white/20 text-white rounded-lg hover:bg-white/30 font-semibold text-sm">
                <i class="fas fa-calendar-day"></i>
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-300 text-green-800 rounded-xl px-4 py-2.5 mb-4 flex items-center gap-2 text-sm">
    <i class="fas fa-check-circle text-green-600"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bg-red-50 border border-red-300 text-red-800 rounded-xl px-4 py-2.5 mb-4 flex items-center gap-2 text-sm">
    <i class="fas fa-exclamation-circle text-red-600"></i> {{ session('error') }}
</div>
@endif

{{-- CALENDRIER --}}
<div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-7">
    <div class="bg-gray-800 text-white px-4 py-2.5 flex justify-between items-center">
        <span class="font-bold text-sm">
            <i class="fas fa-calendar-alt mr-2"></i>{{ $isFrench ? 'Détail par jour' : 'Daily detail' }}
        </span>
        <span class="text-xs text-gray-400">
            {{ $isFrench ? 'Cliquez sur un jour pour saisir' : 'Click a day to enter' }}
        </span>
    </div>

    <div class="cal-header-row">
        <div>{{ $isFrench ? 'Jour' : 'Day' }}</div>
        <div><i class="fas fa-search mr-1"></i>{{ $isFrench ? 'Ventes att.' : 'Exp. sales' }}</div>
        <div><i class="fas fa-arrow-down mr-1"></i>{{ $isFrench ? 'Fond' : 'Float' }}</div>
        <div><i class="fas fa-hand-holding-usd mr-1"></i>{{ $isFrench ? 'Versé' : 'Paid' }}</div>
        <div class="col-retour-cal"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $isFrench ? 'Manquant' : 'Missing' }}</div>
        <div><i class="fas fa-users mr-1"></i>{{ $isFrench ? 'Vendeurs' : 'Sellers' }}</div>
    </div>

    @php
        $totalMoisVentes   = 0;
        $totalMoisFond     = 0;
        $totalMoisVerse    = 0;
        $totalMoisManquant = 0;
    @endphp

    @foreach($jours as $jour)
    @php
        $aManquant  = count($jour['vendeurs']) > 0;
        // Rouge si au moins un vrai manquant positif dans le jour
        $hasManquantPositif = collect($jour['vendeurs'])->where('montant_manquant', '>', 0)->count() > 0;
        $isRouge    = $aManquant && $hasManquantPositif;
        $isVert     = $aManquant && !$hasManquantPositif;
        $rowClass   = $jour['est_weekend'] ? 'weekend' : '';
        $rowClass  .= $isRouge ? ' has-manquant-rouge' : ($isVert ? ' has-manquant-vert' : '');
        $rowClass  .= $jour['est_futur'] ? ' futur' : '';

        $totalMoisVentes   += $jour['total_trouve'];
        $totalMoisFond     += $jour['total_entree'];
        $totalMoisVerse    += $jour['total_verse'];
        $totalMoisManquant += $jour['total_manquant'];
    @endphp

    <div class="cal-day-row {{ $rowClass }}"
         onclick="@if(!$jour['est_futur'])ouvrirModal('{{ $jour['date'] }}', {{ json_encode($jour['vendeurs']) }})@endif">

        <div>
            <div class="cal-day-num">{{ str_pad($jour['jour'], 2, '0', STR_PAD_LEFT) }}</div>
            <div class="cal-day-sub">{{ $jour['jour_semaine'] }}</div>
        </div>

        <div class="num-cell text-gray-700">
            @if($jour['total_trouve'] > 0)
                {{ number_format($jour['total_trouve'], 0, ',', ' ') }}<span class="cur"> F</span>
            @else <span class="text-gray-300">—</span> @endif
        </div>

        <div class="num-cell text-blue-600">
            @if($jour['total_entree'] > 0)
                {{ number_format($jour['total_entree'], 0, ',', ' ') }}<span class="cur"> F</span>
            @else <span class="text-gray-300">—</span> @endif
        </div>

        <div class="num-cell text-gray-600">
            @if($jour['total_verse'] > 0)
                {{ number_format($jour['total_verse'], 0, ',', ' ') }}<span class="cur"> F</span>
            @else <span class="text-gray-300">—</span> @endif
        </div>

        <div class="col-retour-cal num-cell font-bold">
            @if($aManquant)
                @if($jour['total_manquant'] > 0.5)
                    <span class="text-red-600">−{{ number_format($jour['total_manquant'], 0, ',', ' ') }} F</span>
                @else
                    <span class="text-green-600 text-xs">✓</span>
                @endif
            @else
                <span class="text-gray-300">—</span>
            @endif
        </div>

        <div>
            @if($aManquant)
                @foreach($jour['vendeurs'] as $lv)
                    <span class="badge-vendeur badge-{{ $lv['type'] }}">
                        {{ explode(' ', $lv['vendeur_nom'])[0] }}
                        @if($lv['montant_manquant'] > 0.5)
                            −{{ number_format($lv['montant_manquant'], 0, ',', ' ') }}F
                        @elseif($lv['montant_manquant'] < -0.5)
                            +{{ number_format(abs($lv['montant_manquant']), 0, ',', ' ') }}F
                        @else ✓ @endif
                    </span>
                @endforeach
            @else
                <span class="text-gray-300 text-xs">
                    {{ $jour['est_futur'] ? '' : ($isFrench ? 'Aucun' : 'None') }}
                </span>
            @endif
            @if(!$jour['est_futur'])
            <button onclick="event.stopPropagation(); ouvrirModal('{{ $jour['date'] }}', {{ json_encode($jour['vendeurs']) }})"
                    class="ml-1 text-gray-300 hover:text-amber-600 text-xs">
                <i class="fas fa-plus-circle"></i>
            </button>
            @endif
        </div>
    </div>
    @endforeach

    {{-- Totaux mois --}}
    <div class="bg-gray-800 text-white" style="display:grid; grid-template-columns:70px 1fr 1fr 1fr 1fr 1fr;">
        <div style="padding:.5rem .6rem; font-weight:700; font-size:.72rem; color:#fbbf24;">TOTAL</div>
        <div style="padding:.5rem .6rem; font-weight:700; font-size:.72rem; color:#e5e7eb;">{{ number_format($totalMoisVentes, 0, ',', ' ') }} F</div>
        <div style="padding:.5rem .6rem; font-weight:700; font-size:.72rem; color:#93c5fd;">{{ number_format($totalMoisFond, 0, ',', ' ') }} F</div>
        <div style="padding:.5rem .6rem; font-weight:700; font-size:.72rem; color:#e5e7eb;">{{ number_format($totalMoisVerse, 0, ',', ' ') }} F</div>
        <div class="col-retour-cal" style="padding:.5rem .6rem; font-weight:700; font-size:.72rem; color:{{ $totalMoisManquant > 0 ? '#fca5a5' : '#86efac' }};">
            @if($totalMoisManquant > 0.5)
                −{{ number_format($totalMoisManquant, 0, ',', ' ') }} F
            @else
                ✓ 0 F
            @endif
        </div>
        <div style="padding:.5rem .6rem;"></div>
    </div>
</div>

{{-- TOTAUX VENDEURS --}}
<div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gray-800 text-white px-4 py-2.5 flex justify-between items-center">
        <span class="font-bold text-sm">
            <i class="fas fa-users mr-2"></i>{{ $isFrench ? 'Totaux par vendeur' : 'Totals by seller' }}
            <span class="ml-2 text-gray-400 font-normal text-xs">{{ $dateDebut->locale('fr')->isoFormat('MMMM YYYY') }}</span>
        </span>
    </div>

    @if(empty($totauxVendeurs) || collect($totauxVendeurs)->sum('nb_jours') === 0)
    <div class="px-6 py-8 text-center text-gray-400">
        <i class="fas fa-inbox text-3xl block mb-2"></i>
        {{ $isFrench ? 'Aucun manquant saisi pour ce mois' : 'No missing amounts for this month' }}
    </div>
    @else
    <div class="p-3 space-y-2.5">
        @foreach($totauxVendeurs as $tv)
        @if($tv['nb_jours'] > 0)
        <div class="vendeur-card">
            <div class="vendeur-card-header" onclick="toggleCard('card-{{ $tv['vendeur_id'] }}')">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs
                                {{ $tv['total_manquant'] > 0.5 ? 'bg-red-500' : 'bg-gray-400' }}">
                        {{ strtoupper(substr($tv['vendeur_nom'], 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800 text-sm">{{ $tv['vendeur_nom'] }}</div>
                        <div class="text-xs text-gray-400">{{ $tv['nb_jours'] }} jour(s)</div>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    @if($tv['total_manquant'] > 0.5)
                    <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full font-bold text-xs border border-red-200">
                        <i class="fas fa-minus mr-1"></i>{{ number_format($tv['total_manquant'], 0, ',', ' ') }} F
                    </span>
                    @else
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-full font-bold text-xs border border-gray-200">
                        ✓ OK
                    </span>
                    @endif
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" id="chevron-{{ $tv['vendeur_id'] }}"></i>
                </div>
            </div>
            <div class="vendeur-card-body" id="card-{{ $tv['vendeur_id'] }}">
                <table class="vendeur-detail-table">
                    <thead>
                        <tr>
                            <th>{{ $isFrench ? 'Date' : 'Date' }}</th>
                            <th>{{ $isFrench ? 'Ventes att.' : 'Exp. sales' }}</th>
                            <th>{{ $isFrench ? 'Total versé' : 'Total paid' }}</th>
                            <th>{{ $isFrench ? 'Résultat' : 'Result' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tv['details'] as $det)
                        <tr class="{{ $det['type'] === 'manquant' ? 'bg-red-50' : ($det['type'] === 'excedent' ? 'bg-green-50' : '') }}">
                            <td class="font-semibold text-gray-800">{{ $det['date'] }}</td>
                            <td class="text-gray-600">{{ number_format($det['total_ventes'], 0, ',', ' ') }} F</td>
                            <td class="text-gray-600">{{ number_format($det['total_verse'], 0, ',', ' ') }} F</td>
                            <td class="font-bold {{ $det['type'] === 'manquant' ? 'text-red-600' : ($det['type'] === 'excedent' ? 'text-green-600' : 'text-gray-500') }}">
                                @if($det['type'] === 'manquant')
                                    −{{ number_format($det['montant_manquant'], 0, ',', ' ') }} F
                                @elseif($det['type'] === 'excedent')
                                    +{{ number_format(abs($det['montant_manquant']), 0, ',', ' ') }} F
                                @else
                                    ✓ Exact
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-bold">
                            <td>Total</td>
                            <td>{{ number_format($tv['total_ventes'], 0, ',', ' ') }} F</td>
                            <td>{{ number_format($tv['total_verse'], 0, ',', ' ') }} F</td>
                            <td class="{{ $tv['total_manquant'] > 0.5 ? 'text-red-600' : 'text-gray-600' }}">
                                @if($tv['total_manquant'] > 0.5)
                                    −{{ number_format($tv['total_manquant'], 0, ',', ' ') }} F
                                @else
                                    ✓ 0 F
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif
</div>

</div>
</div>

{{-- MODAL --}}
<div class="modal-overlay" id="modalManquant">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <h2 class="text-white font-bold text-base m-0">
                    <i class="fas fa-search-dollar mr-2"></i>{{ $isFrench ? 'Saisie Manquant' : 'Enter Missing Amount' }}
                </h2>
                <p class="text-amber-100 text-xs mt-0.5 m-0" id="modalDateLabel"></p>
            </div>
            <button onclick="fermerModal()" class="text-white text-xl hover:text-amber-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('pdg.manquants.valider') }}" id="formManquant">
                @csrf
                <input type="hidden" name="date_manquant" id="inputDate">

                <div class="mb-3">
                    <label class="lbl"><i class="fas fa-user mr-1" style="color:#d97706;"></i>{{ $isFrench ? 'Vendeur' : 'Seller' }} *</label>
                    <select name="vendeur_id" id="inputVendeur" class="inp" onchange="onVendeurChange()" required>
                        <option value="">— {{ $isFrench ? 'Choisir' : 'Choose' }} —</option>
                        @foreach($vendeurs as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-amber-600 mt-1" id="hintManquantExist" style="display:none;">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ $isFrench ? 'Données existantes — elles seront écrasées.' : 'Existing data — will be overwritten.' }}
                    </p>
                </div>

                <div class="mb-3">
                    <label class="lbl"><i class="fas fa-tag mr-1" style="color:#d97706;"></i>{{ $isFrench ? 'Total ventes attendu (FCFA)' : 'Expected sales (FCFA)' }} *</label>
                    <input type="number" name="total_ventes" id="inputTotalVentes" class="inp font-bold"
                           style="background:#fef3c7; color:#92400e;" placeholder="0" min="0" oninput="calcModal()" required>
                </div>

                <div class="mb-3 p-2.5 rounded-lg" style="background:#eff6ff; border:2px solid #bfdbfe;">
                    <label class="lbl" style="color:#1d4ed8;"><i class="fas fa-cash-register mr-1"></i>{{ $isFrench ? 'Fond de caisse' : 'Cash float' }}</label>
                    <input type="number" name="fond_caisse" id="inputFondCaisse" class="inp" style="border-color:#93c5fd; color:#1e40af;" placeholder="0" min="0" oninput="calcModal()">
                </div>

                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                    <i class="fas fa-money-bill-wave mr-1" style="color:#d97706;"></i>{{ $isFrench ? 'Versements' : 'Payments' }}
                </p>
                <div class="form-row-2 mb-2.5">
                    <div>
                        <label class="lbl">{{ $isFrench ? 'Versement 1' : 'Payment 1' }}</label>
                        <input type="number" name="versement_1" id="mV1" class="inp" placeholder="0" min="0" oninput="calcModal()">
                    </div>
                    <div>
                        <label class="lbl">{{ $isFrench ? 'Versement 2' : 'Payment 2' }}</label>
                        <input type="number" name="versement_2" id="mV2" class="inp" placeholder="0" min="0" oninput="calcModal()">
                    </div>
                </div>
                <div class="form-row-2 mb-2.5">
                    <div>
                        <label class="lbl">{{ $isFrench ? 'Versement 3' : 'Payment 3' }}</label>
                        <input type="number" name="versement_3" id="mV3" class="inp" placeholder="0" min="0" oninput="calcModal()">
                    </div>
                    <div>
                        <label class="lbl">{{ $isFrench ? 'Extra' : 'Extra' }}</label>
                        <input type="number" name="versement_extra" id="mVE" class="inp" placeholder="0" min="0"
                               style="background:#f0fdf4; border-color:#86efac;" oninput="calcModal()">
                    </div>
                </div>

                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                    <i class="fas fa-mobile-alt mr-1" style="color:#d97706;"></i>Mobile Money
                </p>
                <div class="form-row-2 mb-3">
                    <div>
                        <label class="lbl">OM Final (Orange)</label>
                        <input type="number" name="om_final" id="mOM" class="inp" placeholder="0" min="0"
                               style="background:#fff7ed; border-color:#fdba74;" oninput="calcModal()">
                    </div>
                    <div>
                        <label class="lbl">MoMo Final (MTN)</label>
                        <input type="number" name="momo_final" id="mMM" class="inp" placeholder="0" min="0"
                               style="background:#fffbeb; border-color:#fcd34d;" oninput="calcModal()">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="lbl">{{ $isFrench ? 'Notes' : 'Notes' }}</label>
                    <textarea name="notes" id="inputNotes" class="inp" rows="2" placeholder="..."></textarea>
                </div>

                <div id="resCal" style="display:none;" class="mb-3">
                    <div id="resCalBox" class="res-box">
                        <span id="resCalLabel"></span>
                        <span id="resCalVal" class="text-lg"></span>
                    </div>
                </div>

                <div class="flex gap-2.5 justify-end pt-2 border-t border-gray-100">
                    <button type="button" onclick="fermerModal()"
                            class="px-4 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold text-sm">
                        {{ $isFrench ? 'Annuler' : 'Cancel' }}
                    </button>
                    <button type="submit"
                            class="px-4 py-1.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 font-semibold text-sm">
                        <i class="fas fa-check mr-1"></i>{{ $isFrench ? 'Valider' : 'Validate' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function(){
    const manquantsMap = {};
    @foreach($jours as $jour)
        @foreach($jour['vendeurs'] as $lv)
        if (!manquantsMap['{{ $lv['vendeur_id'] }}_{{ $jour['date'] }}']) {
            manquantsMap['{{ $lv['vendeur_id'] }}_{{ $jour['date'] }}'] = @json($lv);
        }
        @endforeach
    @endforeach

    let currentDate = '';

    window.ouvrirModal = function(date, vendeursJour) {
        currentDate = date;
        document.getElementById('inputDate').value = date;
        document.getElementById('modalDateLabel').textContent = '{{ $isFrench ? "Date" : "Date" }} : ' + date;
        resetForm();
        if (vendeursJour && vendeursJour.length === 1) {
            const sel = document.getElementById('inputVendeur');
            sel.value = vendeursJour[0].vendeur_id;
            preRemplir(vendeursJour[0]);
        }
        document.getElementById('modalManquant').classList.add('open');
        document.body.style.overflow = 'hidden';
    };

    window.fermerModal = function() {
        document.getElementById('modalManquant').classList.remove('open');
        document.body.style.overflow = '';
    };

    window.onVendeurChange = function() {
        const vendeurId = document.getElementById('inputVendeur').value;
        const key       = vendeurId + '_' + currentDate;
        const hint      = document.getElementById('hintManquantExist');
        if (manquantsMap[key]) {
            hint.style.display = 'block';
            preRemplir(manquantsMap[key]);
        } else {
            hint.style.display = 'none';
            resetValeurs();
        }
    };

    function preRemplir(data) {
        setVal('inputTotalVentes', data.total_ventes);
        setVal('inputFondCaisse', data.fond_caisse);
        setVal('mV1', data.versement_1);
        setVal('mV2', data.versement_2);
        setVal('mV3', data.versement_3);
        setVal('mVE', data.versement_extra);
        setVal('mOM', data.om_final);
        setVal('mMM', data.momo_final);
        document.getElementById('inputNotes').value = data.notes || '';
        calcModal();
    }

    function setVal(id, val) {
        const el = document.getElementById(id);
        if (el && val != null && val > 0) el.value = val;
    }

    function resetValeurs() {
        ['inputTotalVentes','inputFondCaisse','mV1','mV2','mV3','mVE','mOM','mMM'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        document.getElementById('inputNotes').value = '';
        document.getElementById('resCal').style.display = 'none';
    }

    function resetForm() {
        document.getElementById('inputVendeur').value = '';
        document.getElementById('hintManquantExist').style.display = 'none';
        resetValeurs();
    }

    function n(id) { return parseFloat(document.getElementById(id)?.value) || 0; }
    function fmt(v) { return Math.round(v).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '\u00a0'); }

    window.calcModal = function() {
        const attendu    = n('inputTotalVentes');
        const fond       = n('inputFondCaisse');
        const attenduNet = attendu + fond;
        const verse      = n('mV1') + n('mV2') + n('mV3') + n('mVE') + n('mOM') + n('mMM');
        const manquant   = attenduNet - verse;

        if (attendu === 0 && verse === 0) {
            document.getElementById('resCal').style.display = 'none';
            return;
        }

        const box   = document.getElementById('resCalBox');
        const label = document.getElementById('resCalLabel');
        const val   = document.getElementById('resCalVal');

        if (manquant > 0.5) {
            box.className = 'res-box res-manquant';
            label.textContent = '⚠️ {{ $isFrench ? "Manquant" : "Missing" }}';
            val.textContent   = '− ' + fmt(manquant) + ' FCFA';
        } else if (manquant < -0.5) {
            box.className = 'res-box res-excedent';
            label.textContent = '✅ {{ $isFrench ? "Excédent" : "Surplus" }}';
            val.textContent   = '+ ' + fmt(Math.abs(manquant)) + ' FCFA';
        } else {
            box.className = 'res-box res-exact';
            label.textContent = '✅ {{ $isFrench ? "Caisse juste" : "Exact" }}';
            val.textContent   = '0 FCFA';
        }
        document.getElementById('resCal').style.display = 'block';
    };

    document.getElementById('modalManquant').addEventListener('click', function(e) {
        if (e.target === this) fermerModal();
    });

    window.toggleCard = function(id) {
        const body    = document.getElementById(id);
        const chevron = document.getElementById('chevron-' + id.replace('card-', ''));
        if (!body) return;
        body.classList.toggle('open');
        if (chevron) {
            chevron.style.transform = body.classList.contains('open') ? 'rotate(180deg)' : '';
        }
    };
})();
</script>
@endsection