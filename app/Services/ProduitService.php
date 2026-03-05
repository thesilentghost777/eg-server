<?php

namespace App\Services;

use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class ProduitService
{
    public function getAllProduits($actifOnly = false)
    {
        $query = Produit::query();
        
        if ($actifOnly) {
            $query->actif();
        }
        
        return $query->orderBy('nom')->get();
    }

    public function getProduitsParCategorie($categorie, $actifOnly = true)
    {
        $query = Produit::categorie($categorie);
        
        if ($actifOnly) {
            $query->actif();
        }
        
        return $query->orderBy('nom')->get();
    }

    public function createProduit(array $data)
    {
        return Produit::create($data);
    }

    public function updateProduit($id, array $data)
    {
        $produit = Produit::findOrFail($id);

        $updateData = [
            'nom'       => $data['nom'] ?? $produit->nom,
            'prix'      => isset($data['prix']) ? (float) $data['prix'] : $produit->prix,
            'categorie' => $data['categorie'] ?? $produit->categorie,
            'actif'     => isset($data['actif'])
                ? filter_var($data['actif'], FILTER_VALIDATE_BOOLEAN)
                : $produit->actif,
        ];

        $produit->update($updateData);
        $produit->refresh(); // ← très utile pour renvoyer les vraies valeurs

        return $produit;
    }

    public function toggleActif($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->actif = !$produit->actif;
        $produit->save();
        return $produit;
    }

    public function deleteProduit($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();
        return true;
    }

    public function getProduitById($id)
    {
        return Produit::findOrFail($id);
    }
}
