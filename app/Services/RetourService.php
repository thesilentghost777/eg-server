<?php

namespace App\Services;

use App\Models\RetourProduit;
use App\Models\VendeurActif;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class RetourService
{
    public function createRetour(array $data, $pointeurId)
    {
        DB::beginTransaction();
        
        try {
            // Récupérer le produit pour connaître sa catégorie
            $produit = Produit::findOrFail($data['produit_id']);
            
            // Récupérer le vendeur actif pour cette catégorie
            $vendeurActifId = VendeurActif::getVendeurActif($produit->categorie);
            
            if (!$vendeurActifId) {
                throw new \Exception("Aucun vendeur actif pour la catégorie {$produit->categorie}");
            }
            
            $retour = RetourProduit::create([
                'pointeur_id' => $pointeurId,
                'vendeur_id' => $vendeurActifId,
                'produit_id' => $data['produit_id'],
                'quantite' => $data['quantite'],
                'raison' => $data['raison'],
                'description' => $data['description'] ?? null,
                'date_retour' => now(),
            ]);
            
            DB::commit();
            return $retour->load(['produit', 'vendeur']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateRetour($id, array $data, $pointeurId)
    {
        DB::beginTransaction();
        
        try {
            $retour = RetourProduit::findOrFail($id);
            
            // Vérifier que c'est bien le pointeur qui a créé le retour
            if ($retour->pointeur_id !== $pointeurId) {
                throw new \Exception("Non autorisé à modifier ce retour");
            }
            
            // Vérifier que le verrou n'est pas activé
            if ($retour->verrou) {
                throw new \Exception("Ce retour est verrouillé");
            }
            
            // Vérifier que le vendeur est toujours actif
            $produit = $retour->produit;
            $vendeurActifId = VendeurActif::getVendeurActif($produit->categorie);
            
            if ($retour->vendeur_id !== $vendeurActifId) {
                throw new \Exception("Le vendeur n'est plus actif, modification impossible");
            }
            
            $retour->update([
                'quantite' => $data['quantite'] ?? $retour->quantite,
                'raison' => $data['raison'] ?? $retour->raison,
                'description' => $data['description'] ?? $retour->description,
            ]);
            
            DB::commit();
            return $retour->load(['produit', 'vendeur']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getRetoursParVendeur($vendeurId, $date = null)
    {
        $query = RetourProduit::where('vendeur_id', $vendeurId)
            ->with(['produit', 'pointeur']);
        
        if ($date) {
            $query->byDate($date);
        } else {
            $query->byDate(now()->toDateString());
        }
        
        return $query->orderBy('date_retour', 'desc')->get();
    }

    public function verrouillerRetour($id, $pdgId)
    {
        $retour = RetourProduit::findOrFail($id);
        $retour->verrou = true;
        $retour->save();
        return $retour;
    }
}
