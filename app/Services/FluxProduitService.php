<?php
namespace App\Services;

use App\Models\Produit;
use App\Models\ReceptionPointeur;
use App\Models\RetourProduit;
use App\Models\Inventaire;
use App\Models\InventaireDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FluxProduitService
{
    public function getFluxParVendeur($vendeurId, $date = null)
    {
        $date = $date ?? now()->toDateString();
        $dateCarbon = Carbon::parse($date);
        
        // Récupérer l'inventaire de début
        // Chercher l'inventaire où le vendeur ENTRE (devient actif)
        // Cela peut être sur la date demandée OU la veille (pour les shifts de nuit)
        $inventaireDebut = $this->trouverInventaireDebut($vendeurId, $dateCarbon);
        
        // Récupérer l'inventaire de fin
        // Chercher l'inventaire où le vendeur SORT (fin de shift)
        // Cela peut être sur la date demandée OU le lendemain (pour les shifts de nuit)
        $inventaireFin = $this->trouverInventaireFin($vendeurId, $dateCarbon);
        
        // Déterminer la période réelle du flux
        $dateDebut = $inventaireDebut ? Carbon::parse($inventaireDebut->date_inventaire) : $dateCarbon->copy()->startOfDay();
        $dateFin = $inventaireFin ? Carbon::parse($inventaireFin->date_inventaire) : $dateCarbon->copy()->endOfDay();
        
        // Récupérer toutes les réceptions entre le début et la fin du flux
        $receptions = ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
            ->whereBetween('date_reception', [$dateDebut, $dateFin])
            ->with('produit')
            ->get();
        
        // Récupérer tous les retours entre le début et la fin du flux
        $retours = RetourProduit::where('vendeur_id', $vendeurId)
            ->whereBetween('date_retour', [$dateDebut, $dateFin])
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
        
        return [
            'periode' => [
                'debut' => $inventaireDebut ? $inventaireDebut->date_inventaire : null,
                'fin' => $inventaireFin ? $inventaireFin->date_inventaire : null,
            ],
            'flux' => $flux
        ];
    }
    
    /**
     * Trouve l'inventaire de début pour un vendeur
     * Cherche d'abord sur la date demandée, puis sur la veille
     */
    private function trouverInventaireDebut($vendeurId, Carbon $date)
    {
        // D'abord chercher sur la date demandée
        $inventaire = Inventaire::where('vendeur_entrant_id', $vendeurId)
            ->whereDate('date_inventaire', $date)
            ->with('details.produit')
            ->first();
        
        if ($inventaire) {
            return $inventaire;
        }
        
        // Si pas trouvé, chercher sur la veille (pour les shifts de nuit)
        $dateVeille = $date->copy()->subDay();
        $inventaire = Inventaire::where('vendeur_entrant_id', $vendeurId)
            ->whereDate('date_inventaire', $dateVeille)
            ->with('details.produit')
            ->first();
        
        return $inventaire;
    }
    
    /**
     * Trouve l'inventaire de fin pour un vendeur
     * Cherche d'abord sur la date demandée, puis sur le lendemain
     */
    private function trouverInventaireFin($vendeurId, Carbon $date)
    {
        // D'abord chercher sur la date demandée
        $inventaire = Inventaire::where('vendeur_sortant_id', $vendeurId)
            ->whereDate('date_inventaire', $date)
            ->with('details.produit')
            ->first();
        
        if ($inventaire) {
            return $inventaire;
        }
        
        // Si pas trouvé, chercher sur le lendemain (pour les shifts de nuit)
        $dateLendemain = $date->copy()->addDay();
        $inventaire = Inventaire::where('vendeur_sortant_id', $vendeurId)
            ->whereDate('date_inventaire', $dateLendemain)
            ->with('details.produit')
            ->first();
        
        return $inventaire;
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
                'flux_data' => $this->getFluxParVendeur($vendeur->id, $date),
            ];
        }
        
        return $fluxTous;
    }
}