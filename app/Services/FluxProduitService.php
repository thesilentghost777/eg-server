<?php
namespace App\Services;

use App\Models\Produit;
use App\Models\ReceptionPointeur;
use App\Models\RetourProduit;
use App\Models\Inventaire;
use App\Models\InventaireDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FluxProduitService
{
    public function getFluxParVendeur($vendeurId, $date = null)
    {
        $date = $date ?? now()->toDateString();
        $dateCarbon = Carbon::parse($date);
        
        Log::info("=== DEBUT FLUX PRODUIT ===");
        Log::info("Vendeur ID: {$vendeurId}");
        Log::info("Date demandée: {$date}");
        
        // Définir la période : début et fin de la journée sélectionnée
        $dateDebut = $dateCarbon->copy()->startOfDay(); // 00:00:00
        $dateFin = $dateCarbon->copy()->endOfDay(); // 23:59:59
        
        Log::info("Période analysée: de {$dateDebut} à {$dateFin}");
        
        // Récupérer l'inventaire de début (vendeur_entrant) sur la date sélectionnée
        $inventaireDebut = Inventaire::where('vendeur_entrant_id', $vendeurId)
            ->whereDate('date_inventaire', $dateCarbon)
            ->with('details.produit')
            ->first();
        
        if ($inventaireDebut) {
            Log::info("Inventaire de début trouvé - ID: {$inventaireDebut->id}, Date: {$inventaireDebut->date_inventaire}");
            Log::info("Nombre de détails inventaire début: " . $inventaireDebut->details->count());
        } else {
            Log::warning("Aucun inventaire de début trouvé pour ce vendeur à cette date");
        }
        
        // Récupérer l'inventaire de fin (vendeur_sortant) sur la date sélectionnée
        $inventaireFin = Inventaire::where('vendeur_sortant_id', $vendeurId)
            ->whereDate('date_inventaire', $dateCarbon)
            ->with('details.produit')
            ->first();
        
        if ($inventaireFin) {
            Log::info("Inventaire de fin trouvé - ID: {$inventaireFin->id}, Date: {$inventaireFin->date_inventaire}");
            Log::info("Nombre de détails inventaire fin: " . $inventaireFin->details->count());
        } else {
            Log::warning("Aucun inventaire de fin trouvé pour ce vendeur à cette date");
        }
        
        // Récupérer toutes les réceptions dans l'intervalle de la journée
        $receptions = ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
            ->whereBetween('date_reception', [$dateDebut, $dateFin])
            ->with('produit')
            ->get();
        
        Log::info("Nombre de réceptions trouvées: " . $receptions->count());
        if ($receptions->count() > 0) {
            Log::info("Détail réceptions: " . $receptions->map(function($r) {
                return "Produit {$r->produit_id}: {$r->quantite} unités à {$r->date_reception}";
            })->join(', '));
        }
        
        // Récupérer tous les retours dans l'intervalle de la journée
        $retours = RetourProduit::where('vendeur_id', $vendeurId)
            ->whereBetween('date_retour', [$dateDebut, $dateFin])
            ->with('produit')
            ->get();
        
        Log::info("Nombre de retours trouvés: " . $retours->count());
        if ($retours->count() > 0) {
            Log::info("Détail retours: " . $retours->map(function($r) {
                return "Produit {$r->produit_id}: {$r->quantite} unités à {$r->date_retour}";
            })->join(', '));
        }
        
        // Construire le flux pour chaque produit
        $flux = [];
        $produits = Produit::actif()->get();
        
        Log::info("Nombre de produits actifs: " . $produits->count());
        
        $produitsAvecActivite = 0;
        
        foreach ($produits as $produit) {
            $quantiteTrouvee = 0;
            $quantiteRecue = 0;
            $quantiteRetour = 0;
            $quantiteRestante = 0;
            
            // Quantité trouvée (inventaire début)
            if ($inventaireDebut) {
                $detail = $inventaireDebut->details->where('produit_id', $produit->id)->first();
                $quantiteTrouvee = $detail ? $detail->quantite_restante : 0;
            }
            
            // Quantité reçue
            $quantiteRecue = $receptions->where('produit_id', $produit->id)->sum('quantite');
            
            // Quantité retour
            $quantiteRetour = $retours->where('produit_id', $produit->id)->sum('quantite');
            
            // Quantité restante (inventaire fin)
            if ($inventaireFin) {
                $detail = $inventaireFin->details->where('produit_id', $produit->id)->first();
                $quantiteRestante = $detail ? $detail->quantite_restante : 0;
            }
            
            // Quantité vendue = Trouvée + Reçue - Retour - Restante
            $quantiteVendue = $quantiteTrouvee + $quantiteRecue - $quantiteRetour - $quantiteRestante;
            
            // Log seulement si le produit a une activité
            if ($quantiteTrouvee > 0 || $quantiteRecue > 0 || $quantiteRetour > 0 || $quantiteRestante > 0) {
                $produitsAvecActivite++;
                Log::info("Produit [{$produit->id}] {$produit->nom}: Trouvée={$quantiteTrouvee}, Reçue={$quantiteRecue}, Retour={$quantiteRetour}, Restante={$quantiteRestante}, Vendue={$quantiteVendue}");
            }
            
            $flux[] = [
                'produit' => $produit,
                'quantite_trouvee' => $quantiteTrouvee,
                'quantite_recue' => $quantiteRecue,
                'quantite_retour' => $quantiteRetour,
                'quantite_restante' => $quantiteRestante,
                'quantite_vendue' => $quantiteVendue,
                'valeur_vente' => $quantiteVendue * $produit->prix,
            ];
        }
        
        Log::info("Nombre de produits avec activité: {$produitsAvecActivite}");
        Log::info("=== FIN FLUX PRODUIT ===");
        
        return [
            'periode' => [
                'debut' => $dateDebut->toDateTimeString(),
                'fin' => $dateFin->toDateTimeString(),
                'date' => $dateCarbon->toDateString(),
            ],
            'flux' => $flux,
            'stats' => [
                'total_produits' => $produits->count(),
                'produits_avec_activite' => $produitsAvecActivite,
                'has_inventaire_debut' => $inventaireDebut ? true : false,
                'has_inventaire_fin' => $inventaireFin ? true : false,
                'total_receptions' => $receptions->count(),
                'total_retours' => $retours->count(),
            ]
        ];
    }
    
    public function getFluxTous($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        Log::info("=== FLUX TOUS LES VENDEURS ===");
        Log::info("Date: {$date}");
        
        $vendeurs = \App\Models\User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
            ->where('actif', true)
            ->get();
        
        Log::info("Nombre de vendeurs actifs: " . $vendeurs->count());
        
        $fluxTous = [];
        
        foreach ($vendeurs as $vendeur) {
            Log::info("Traitement vendeur: {$vendeur->name} (ID: {$vendeur->id})");
            $fluxTous[$vendeur->id] = [
                'vendeur' => $vendeur,
                'flux_data' => $this->getFluxParVendeur($vendeur->id, $date),
            ];
        }
        
        Log::info("=== FIN FLUX TOUS LES VENDEURS ===");
        
        return $fluxTous;
    }
}