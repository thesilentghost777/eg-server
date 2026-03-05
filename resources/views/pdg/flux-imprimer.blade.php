<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Flux Opérationnel' : 'Operational Flow' }} - {{ $flux['resume']['date'] ?? '' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #D4A574;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18pt;
            color: #D4A574;
            margin-bottom: 5px;
        }
        
        .resume {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        
        .resume-item {
            text-align: center;
        }
        
        .resume-item .label {
            font-size: 8pt;
            color: #666;
            margin-bottom: 5px;
        }
        
        .resume-item .value {
            font-size: 14pt;
            font-weight: bold;
            color: #D4A574;
        }
        
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
        
        .vendeur-header .nom {
            font-size: 12pt;
            font-weight: bold;
        }
        
        .vendeur-header .total {
            font-size: 11pt;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }
        
        table thead {
            background: #f0f0f0;
        }
        
        table th {
            padding: 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        
        table td {
            padding: 6px 8px;
            font-size: 9pt;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        table tbody tr:hover {
            background: #f5f5f5;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .text-blue {
            color: #3b82f6;
        }
        
        .text-green {
            color: #10b981;
        }
        
        .text-orange {
            color: #f59e0b;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .vendeur-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $isFrench ? 'FLUX OPÉRATIONNEL' : 'OPERATIONAL FLOW' }}</h1>
        <p>{{ $isFrench ? 'Date:' : 'Date:' }} {{ $flux['resume']['date'] ?? '' }}</p>
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
                <span style="font-size: 9pt; opacity: 0.9;">
                    ({{ ucfirst(str_replace('_', ' ', $fluxVendeur['vendeur']['role'])) }})
                </span>
            </div>
            <div class="total">
                {{ $isFrench ? 'Total:' : 'Total:' }} {{ number_format($fluxVendeur['total_ventes'] ?? 0, 0, ',', ' ') }} FCFA
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>{{ $isFrench ? 'Produit' : 'Product' }}</th>
                    <th class="text-center">{{ $isFrench ? 'Prix' : 'Price' }}</th>
                    <th class="text-center">{{ $isFrench ? 'Réception' : 'Reception' }}</th>
                    <th class="text-center">{{ $isFrench ? 'Stock Ini.' : 'Init. Stock' }}</th>
                    <th class="text-center">{{ $isFrench ? 'Vendus' : 'Sold' }}</th>
                    <th class="text-center">{{ $isFrench ? 'Retours' : 'Returns' }}</th>
                    <th class="text-center">{{ $isFrench ? 'Stock Fin.' : 'Final Stock' }}</th>
                    <th class="text-right">{{ $isFrench ? 'Valeur' : 'Value' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fluxVendeur['produits'] ?? [] as $produitFlux)
                <tr>
                    <td class="font-bold">{{ $produitFlux['produit_nom'] ?? $produitFlux['produit'] ?? '' }}</td>
                    <td class="text-center">{{ number_format($produitFlux['prix_unitaire'] ?? 0, 0, ',', ' ') }}</td>
                    <td class="text-center font-bold text-blue">{{ $produitFlux['reception'] ?? 0 }}</td>
                    <td class="text-center">{{ $produitFlux['stock_initial'] ?? 0 }}</td>
                    <td class="text-center font-bold text-green">{{ $produitFlux['quantite_vendue'] ?? 0 }}</td>
                    <td class="text-center text-orange">{{ $produitFlux['retours'] ?? 0 }}</td>
                    <td class="text-center">{{ $produitFlux['stock_final'] ?? 0 }}</td>
                    <td class="text-right font-bold">{{ number_format($produitFlux['valeur_vente'] ?? 0, 0, ',', ' ') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">
                        {{ $isFrench ? 'Aucun produit' : 'No products' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @empty
    <div style="text-align: center; padding: 50px; color: #999;">
        <p style="font-size: 14pt;">{{ $isFrench ? 'Aucune activité pour cette période' : 'No activity for this period' }}</p>
    </div>
    @endforelse

    <div class="footer">
        <p>{{ $isFrench ? 'Document généré le' : 'Document generated on' }} {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>