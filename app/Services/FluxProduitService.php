<?php

namespace App\Services;

use App\Models\Produit;
use App\Models\ReceptionPointeur;
use App\Models\RetourProduit;
use App\Models\Inventaire;
use App\Models\InventaireDetail;
use Illuminate\Support\Facades\DB;

class FluxProduitService
{
    public function getFluxParVendeur($vendeurId, $date = null)
    {
        $date = $date ?? now()->toDateString();
        
        // Récupérer l'inventaire de début (vendeur entrant)
        $inventaireDebut = Inventaire::where('vendeur_entrant_id', $vendeurId)
            ->whereDate('date_inventaire', $date)
            ->with('details.produit')
            ->first();
        
        // Récupérer l'inventaire de fin (vendeur sortant)
        $inventaireFin = Inventaire::where('vendeur_sortant_id', $vendeurId)
            ->whereDate('date_inventaire', $date)
            ->with('details.produit')
            ->first();
        
        // Récupérer toutes les réceptions du jour
        $receptions = ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
            ->whereDate('date_reception', $date)
            ->with('produit')
            ->get();
        
        // Récupérer tous les retours du jour
        $retours = RetourProduit::where('vendeur_id', $vendeurId)
            ->whereDate('date_retour', $date)
            ->with('produit')
            ->get();
        
        // Construire le flux pour chaque produit
        $flux = [];
        $produits = Produit::actif()->get();
        
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
        
        return $flux;
    }

    public function getFluxTous($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $vendeurs = \App\Models\User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])
            ->where('actif', true)
            ->get();
        
        $fluxTous = [];
        
        foreach ($vendeurs as $vendeur) {
            $fluxTous[$vendeur->id] = [
                'vendeur' => $vendeur,
                'flux' => $this->getFluxParVendeur($vendeur->id, $date),
            ];
        }
        
        return $fluxTous;
    }
}
