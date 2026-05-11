<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Flux Opérationnel' : 'Operational Flow' }} - {{ $flux['resume']['date'] ?? '' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            padding: 20px;
            background: #fff;
        }

        /* Bouton impression — masqué à l'impression */
        .btn-print {
            display: inline-block;
            margin-bottom: 16px;
            padding: 8px 20px;
            background: #b45309;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 10pt;
            cursor: pointer;
        }
        @media print { .btn-print { display: none !important; } }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #D4A574;
            padding-bottom: 10px;
        }
        .header h1 { font-size: 18pt; color: #D4A574; margin-bottom: 5px; }

        .resume {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        .resume-item { text-align: center; }
        .resume-item .label { font-size: 8pt; color: #666; margin-bottom: 5px; }
        .resume-item .value { font-size: 14pt; font-weight: bold; color: #D4A574; }

        .vendeur-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .vendeur-header {
            background: linear-gradient(135deg, #D4A574 0%, #C89968 50%, #B08554 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 5px 5px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .vendeur-header .nom { font-size: 12pt; font-weight: bold; }
        .vendeur-header .total { font-size: 11pt; }

        table { width: 100%; border-collapse: collapse; margin-top: 0; }
        table thead { background: #f0f0f0; }
        table th {
            padding: 8px;
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        table th:first-child { text-align: left; }
        table td {
            padding: 6px 8px;
            font-size: 9pt;
            border: 1px solid #ddd;
            text-align: center;
        }
        table td:first-child { text-align: left; font-weight: bold; }
        table tbody tr:nth-child(even) { background: #f9f9f9; }

        /* Surbrillance vente négative masquée */
        .row-negative {
            background: #fef2f2 !important;
            outline: 2px solid #fca5a5;
        }
        .row-negative td { background: #fef2f2 !important; color: #991b1b; }
        .badge-neg {
            display: inline-block;
            font-size: 7pt;
            font-weight: bold;
            color: #dc2626;
            background: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 3px;
            padding: 1px 4px;
            margin-left: 4px;
        }

        .text-right  { text-align: right !important; }
        .text-center { text-align: center; }
        .font-bold   { font-weight: bold; }
        .text-blue   { color: #3b82f6; }
        .text-green  { color: #10b981; }
        .text-orange { color: #f59e0b; }
        .text-red    { color: #dc2626; }

        tfoot td {
            background: #1f2937;
            color: #fff;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #374151;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">
        🖨 {{ $isFrench ? 'Imprimer' : 'Print' }}
    </button>

    <div class="header">
        <h1>{{ $isFrench ? 'FLUX OPÉRATIONNEL' : 'OPERATIONAL FLOW' }}</h1>
        <p>{{ $isFrench ? 'Date :' : 'Date:' }} {{ $flux['resume']['date'] ?? '' }}</p>
    </div>

    @if(isset($flux['resume']))
    <div class="resume">
        <div class="resume-item">
            <div class="label">{{ $isFrench ? 'Vendeurs Actifs' : 'Active Sellers' }}</div>
            <div class="value">{{ count($flux['flux'] ?? []) }}</div>
        </div>
        <div class="resume-item">
            <div class="label">{{ $isFrench ? 'Valeur Totale (FCFA)' : 'Total Value (FCFA)' }}</div>
            <div class="value">{{ number_format($flux['resume']['total_ventes'] ?? 0, 0, ',', ' ') }}</div>
        </div>
        <div class="resume-item">
            <div class="label">{{ $isFrench ? 'Produits Vendus' : 'Products Sold' }}</div>
            <div class="value">{{ $flux['resume']['total_produits'] ?? 0 }}</div>
        </div>
        <div class="resume-item">
            <div class="label">{{ $isFrench ? 'Réceptions Jour' : 'Daily Receptions' }}</div>
            <div class="value">{{ $flux['resume']['total_receptions'] ?? 0 }}</div>
        </div>
    </div>
    @endif

    @forelse($flux['flux'] ?? [] as $fluxVendeur)
    <div class="vendeur-section">
        <div class="vendeur-header">
            <div>
                <span class="nom">{{ $fluxVendeur['vendeur']['nom'] }}</span>
                <span style="font-size:9pt; opacity:.9;">
                    ({{ ucfirst(str_replace('_', ' ', $fluxVendeur['vendeur']['role'] ?? '')) }})
                </span>
            </div>
            <div class="total">
                {{ $isFrench ? 'Total :' : 'Total:' }}
                {{ number_format($fluxVendeur['total_ventes'] ?? 0, 0, ',', ' ') }} FCFA
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="text-align:left;">{{ $isFrench ? 'Produit' : 'Product' }}</th>
                    <th>{{ $isFrench ? 'Prix Unit.' : 'Unit Price' }}</th>
                    <th style="background:rgba(59,130,246,.1);">{{ $isFrench ? 'Trouvé' : 'Found' }}</th>
                    <th style="background:rgba(59,130,246,.1);">{{ $isFrench ? 'Réception' : 'Reception' }}</th>
                    <th style="background:rgba(239,68,68,.1);">{{ $isFrench ? 'Retour' : 'Return' }}</th>
                    <th style="background:rgba(239,68,68,.1);">{{ $isFrench ? 'Restant' : 'Remaining' }}</th>
                    <th style="background:rgba(34,197,94,.1);">{{ $isFrench ? 'Vendus' : 'Sold' }}</th>
                    <th style="background:rgba(34,197,94,.1); text-align:right;">{{ $isFrench ? 'Valeur (FCFA)' : 'Value (FCFA)' }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sTrouve=0; $sRecu=0; $sRetour=0; $sRestant=0; $sVendu=0; $sValeur=0;
                @endphp
                @forelse($fluxVendeur['produits'] ?? [] as $pf)
                @php
                    $trouvee  = $pf['quantite_trouvee']  ?? 0;
                    $recue    = $pf['quantite_recue']    ?? 0;
                    $retour   = $pf['quantite_retour']   ?? 0;
                    $restante = $pf['quantite_restante'] ?? 0;
                    $vendue   = $pf['quantite_vendue']   ?? 0;
                    $valeur   = $pf['valeur_vente']      ?? 0;
                    $prix     = $pf['prix_unitaire']     ?? 0;

                    $venteReelle = $trouvee + $recue - $retour - $restante;
                    $isNegative  = $venteReelle < 0 && $vendue == 0;

                    $sTrouve  += $trouvee;
                    $sRecu    += $recue;
                    $sRetour  += $retour;
                    $sRestant += $restante;
                    $sVendu   += $vendue;
                    $sValeur  += $valeur;
                @endphp
                <tr class="{{ $isNegative ? 'row-negative' : '' }}">
                    <td>
                        {{ $pf['produit_nom'] ?? '' }}
                        @if($isNegative)
                            <span class="badge-neg">⚠ {{ $venteReelle }}</span>
                        @endif
                    </td>
                    <td>{{ number_format($prix, 0, ',', ' ') }}</td>
                    <td class="font-bold" style="color:#7c3aed;">{{ $trouvee }}</td>
                    <td class="font-bold text-blue">{{ $recue }}</td>
                    <td class="text-orange">{{ $retour }}</td>
                    <td>{{ $restante }}</td>
                    <td class="font-bold {{ $isNegative ? 'text-red' : 'text-green' }}">{{ $vendue }}</td>
                    <td class="text-right font-bold {{ $isNegative ? 'text-red' : '' }}">
                        {{ number_format($valeur, 0, ',', ' ') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding:20px;">
                        {{ $isFrench ? 'Aucun produit' : 'No products' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">{{ $isFrench ? 'Sous-total' : 'Subtotal' }}</td>
                    <td>{{ $sTrouve }}</td>
                    <td>{{ $sRecu }}</td>
                    <td>{{ $sRetour }}</td>
                    <td>{{ $sRestant }}</td>
                    <td>{{ $sVendu }}</td>
                    <td class="text-right">{{ number_format($sValeur, 0, ',', ' ') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @empty
    <div style="text-align:center; padding:50px; color:#999;">
        <p style="font-size:14pt;">{{ $isFrench ? 'Aucune activité pour cette période' : 'No activity for this period' }}</p>
    </div>
    @endforelse

    <div class="footer">
        <p>{{ $isFrench ? 'Document généré le' : 'Document generated on' }} {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

</body>
</html>