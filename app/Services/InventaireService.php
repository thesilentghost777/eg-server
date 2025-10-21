<?php

namespace App\Services;

use App\Models\Inventaire;
use App\Models\InventaireDetail;
use App\Models\VendeurActif;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InventaireService
{
    public function creerInventaire(array $data)
    {
        DB::beginTransaction();
        
        try {
            // Récupérer les deux vendeurs
            $vendeurSortant = User::findOrFail($data['vendeur_sortant_id']);
            $vendeurEntrant = User::findOrFail($data['vendeur_entrant_id']);
            
            // Vérifier que les deux vendeurs existent et ont le bon rôle
            if (!str_starts_with($vendeurSortant->role, 'vendeur_')) {
                throw new \Exception("Le vendeur sortant n'a pas un rôle de vendeur valide");
            }
            
            if (!str_starts_with($vendeurEntrant->role, 'vendeur_')) {
                throw new \Exception("Le vendeur entrant n'a pas un rôle de vendeur valide");
            }
            
            // Vérifier que les deux vendeurs sont de la même catégorie
            $categorieSortant = str_replace('vendeur_', '', $vendeurSortant->role);
            $categorieEntrant = str_replace('vendeur_', '', $vendeurEntrant->role);
            
            if ($categorieSortant !== $categorieEntrant) {
                throw new \Exception("Les deux vendeurs doivent être de la même catégorie");
            }
            
            // Vérifier les codes PIN des deux vendeurs
            if ($vendeurSortant->code_pin !== $data['code_pin_sortant']) {
                throw new \Exception("Code PIN incorrect pour le vendeur sortant");
            }
            
            if ($vendeurEntrant->code_pin !== $data['code_pin_entrant']) {
                throw new \Exception("Code PIN incorrect pour le vendeur entrant");
            }
            
            // Vérifier qu'il n'y a pas déjà un inventaire en cours pour cette catégorie
            $inventaireEnCours = Inventaire::where('categorie', $categorieSortant)
                ->where(function($query) {
                    $query->where('valide_sortant', false)
                          ->orWhere('valide_entrant', false);
                })
                ->exists();
                
            if ($inventaireEnCours) {
                throw new \Exception("Un inventaire est déjà en cours pour cette catégorie");
            }
            
            // Créer l'inventaire avec validation immédiate des deux parties
            $inventaire = Inventaire::create([
                'vendeur_sortant_id' => $data['vendeur_sortant_id'],
                'vendeur_entrant_id' => $data['vendeur_entrant_id'],
                'categorie' => $categorieSortant,
                'date_inventaire' => now(),
                'valide_sortant' => true, // Validé directement
                'valide_entrant' => true, // Validé directement
            ]);
            
            // Créer les détails
            foreach ($data['produits'] as $produitData) {
                InventaireDetail::create([
                    'inventaire_id' => $inventaire->id,
                    'produit_id' => $produitData['produit_id'],
                    'quantite_restante' => $produitData['quantite_restante'],
                ]);
            }
            
            // Changer le vendeur actif immédiatement
            VendeurActif::setVendeurActif(
                $inventaire->categorie,
                $inventaire->vendeur_entrant_id
            );
            
            DB::commit();
            return $inventaire->load(['details.produit', 'vendeurSortant', 'vendeurEntrant']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getInventairesParVendeur($vendeurId)
    {
        return Inventaire::where('vendeur_sortant_id', $vendeurId)
            ->orWhere('vendeur_entrant_id', $vendeurId)
            ->with(['details.produit', 'vendeurSortant', 'vendeurEntrant'])
            ->orderBy('date_inventaire', 'desc')
            ->get();
    }

    public function getInventaireEnCours($vendeurId)
    {
        return Inventaire::where(function($query) use ($vendeurId) {
                $query->where('vendeur_sortant_id', $vendeurId)
                      ->where('valide_sortant', false);
            })
            ->orWhere(function($query) use ($vendeurId) {
                $query->where('vendeur_entrant_id', $vendeurId)
                      ->where('valide_entrant', false);
            })
            ->with(['details.produit', 'vendeurSortant', 'vendeurEntrant'])
            ->first();
    }
}