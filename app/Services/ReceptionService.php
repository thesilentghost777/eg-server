<?php

namespace App\Services;

use App\Models\ReceptionPointeur;
use App\Models\VendeurActif;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class ReceptionService
{
    public function createReception(array $data, $pointeurId)
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
            
            $reception = ReceptionPointeur::create([
                'pointeur_id' => $pointeurId,
                'producteur_id' => $data['producteur_id'],
                'produit_id' => $data['produit_id'],
                'quantite' => $data['quantite'],
                'vendeur_assigne_id' => $vendeurActifId,
                'date_reception' => now(),
                'notes' => $data['notes'] ?? null,
            ]);
            
            DB::commit();
            return $reception->load(['produit', 'producteur', 'vendeurAssigne']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateReception($id, array $data, $pointeurId)
    {
        DB::beginTransaction();
        
        try {
            $reception = ReceptionPointeur::findOrFail($id);
            
            // Vérifier que c'est bien le pointeur qui a créé la réception
            if ($reception->pointeur_id !== $pointeurId) {
                throw new \Exception("Non autorisé à modifier cette réception");
            }
            
            // Vérifier que le verrou n'est pas activé
            if ($reception->verrou) {
                throw new \Exception("Cette réception est verrouillée");
            }
            
            // Vérifier que le vendeur assigné est toujours actif
            $produit = $reception->produit;
            $vendeurActifId = VendeurActif::getVendeurActif($produit->categorie);
            
            if ($reception->vendeur_assigne_id !== $vendeurActifId) {
                throw new \Exception("Le vendeur n'est plus actif, modification impossible");
            }
            
            $reception->update([
                'quantite' => $data['quantite'] ?? $reception->quantite,
                'notes' => $data['notes'] ?? $reception->notes,
            ]);
            
            DB::commit();
            return $reception->load(['produit', 'producteur', 'vendeurAssigne']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getReceptionsParVendeur($vendeurId, $date = null)
    {
        $query = ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
            ->with(['produit', 'producteur', 'pointeur']);
        
        if ($date) {
            $query->byDate($date);
        } else {
            $query->byDate(now()->toDateString());
        }
        
        return $query->orderBy('date_reception', 'desc')->get();
    }

    public function getReceptionsParPointeur($pointeurId, $date = null)
    {
        $query = ReceptionPointeur::where('pointeur_id', $pointeurId)
            ->with(['produit', 'producteur', 'vendeurAssigne']);
        
        if ($date) {
            $query->byDate($date);
        } else {
            $query->byDate(now()->toDateString());
        }
        
        return $query->orderBy('date_reception', 'desc')->get();
    }

    public function verrouillerReception($id, $pdgId)
    {
        $reception = ReceptionPointeur::findOrFail($id);
        $reception->verrou = true;
        $reception->save();
        return $reception;
    }
}
