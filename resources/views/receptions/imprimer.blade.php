<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réceptions - Impression</title>
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
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18pt;
            color: #D4A574;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 11pt;
            color: #666;
        }
        
        .filters {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        .filters-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .summary-card {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        
        .summary-card .label {
            font-size: 8pt;
            color: #666;
            margin-bottom: 3px;
        }
        
        .summary-card .value {
            font-size: 16pt;
            font-weight: bold;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background: #D4A574;
            color: white;
        }
        
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        
        table th {
            font-weight: bold;
            font-size: 9pt;
        }
        
        table td {
            font-size: 9pt;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        
        .badge-boulangerie {
            background: #FEF3C7;
            color: #92400E;
        }
        
        .badge-patisserie {
            background: #FCE7F3;
            color: #9F1239;
        }
        
        .badge-locked {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        .badge-open {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        
        @media print {
            body {
                padding: 10px;
            }
            
            .no-print {
                display: none !important;
            }
            
            @page {
                size: A4 landscape;
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <!-- Bouton d'impression -->
    <div class="no-print" style="position: fixed; top: 10px; right: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #D4A574; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Imprimer
        </button>
    </div>

    <!-- En-tête -->
    <div class="header">
        <h1>📦 RÉCEPTIONS DE PRODUITS</h1>
        <div class="subtitle">Rapport généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <!-- Filtres appliqués -->
    @if(!empty($validated))
    <div class="filters">
        <div class="filters-title">Filtres appliqués:</div>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 5px;">
            @if(isset($validated['date_debut']))
                <div>📅 Date début: <strong>{{ \Carbon\Carbon::parse($validated['date_debut'])->format('d/m/Y') }}</strong></div>
            @endif
            @if(isset($validated['date_fin']))
                <div>📅 Date fin: <strong>{{ \Carbon\Carbon::parse($validated['date_fin'])->format('d/m/Y') }}</strong></div>
            @endif
            @if(isset($validated['produit_id']))
                <div>📦 Produit filtré: <strong>Oui</strong></div>
            @endif
            @if(isset($validated['pointeur_id']))
                <div>👤 Pointeur filtré: <strong>Oui</strong></div>
            @endif
            @if(isset($validated['producteur_id']))
                <div>🏭 Producteur filtré: <strong>Oui</strong></div>
            @endif
            @if(isset($validated['vendeur_id']))
                <div>🏪 Vendeur filtré: <strong>Oui</strong></div>
            @endif
        </div>
    </div>
    @endif

    <!-- Résumé -->
    <div class="summary">
        <div class="summary-card">
            <div class="label">Total Réceptions</div>
            <div class="value">{{ $receptions->count() }}</div>
        </div>
        <div class="summary-card">
            <div class="label">Quantité Totale</div>
            <div class="value">{{ $receptions->sum('quantite') }}</div>
        </div>
        <div class="summary-card">
            <div class="label">Producteurs Uniques</div>
            <div class="value">{{ $receptions->unique('producteur_id')->count() }}</div>
        </div>
        <div class="summary-card">
            <div class="label">Produits Différents</div>
            <div class="value">{{ $receptions->unique('produit_id')->count() }}</div>
        </div>
    </div>

    <!-- Tableau des réceptions -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Produit</th>
                <th>Prix</th>
                <th>Catégorie</th>
                <th class="text-center">Qté</th>
                <th>Producteur</th>
                <th>Pointeur</th>
                <th>Vendeur</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receptions as $reception)
            <tr>
                <td>{{ $reception->date_reception->format('d/m/Y') }}</td>
                <td>{{ $reception->date_reception->format('H:i') }}</td>
                <td><strong>{{ $reception->produit->nom }}</strong></td>
                <td class="text-right">{{ number_format($reception->produit->prix, 0, ',', ' ') }} FCFA</td>
                <td>
                    <span class="badge badge-{{ $reception->produit->categorie }}">
                        {{ ucfirst($reception->produit->categorie) }}
                    </span>
                </td>
                <td class="text-center"><strong>{{ $reception->quantite }}</strong></td>
                <td>{{ $reception->producteur->name }}</td>
                <td>{{ $reception->pointeur->name }}</td>
                <td>{{ $reception->vendeurAssigne ? $reception->vendeurAssigne->name : 'Non assigné' }}</td>
                <td class="text-center">
                    @if($reception->verrou)
                        <span class="badge badge-locked">🔒 Verrouillé</span>
                    @else
                        <span class="badge badge-open">🔓 Ouvert</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f5f5f5; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL:</td>
                <td class="text-center">{{ $receptions->sum('quantite') }}</td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>

    <!-- Pied de page -->
    <div class="footer">
        <div>EasyGest-BP - Système de Gestion de Boulangerie-Pâtisserie</div>
        <div>Document confidentiel - Usage interne uniquement</div>
    </div>

    <script>
        // Impression automatique au chargement (optionnel)
        // window.addEventListener('load', function() {
        //     window.print();
        // });
    </script>
</body>
</html>
