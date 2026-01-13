<?php

namespace App\Services;

use App\Models\Produit;
use App\Models\ReceptionPointeur;
use App\Models\RetourProduit;
use App\Models\VendeurActif;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PointeurService
{
    /**
     * Récupérer tous les produits actifs
     */
    public function getProduits()
    {
        return Produit::actif()->orderBy('categorie')->orderBy('nom')->get();
    }

    /**
     * Récupérer les producteurs
     */
    public function getProducteurs()
    {
        return User::where('role', 'producteur')->where('actif', true)->get();
    }

    /**
     * Enregistrer une réception de produit
     */
    public function enregistrerReception(array $data, int $pointeurId)
    {
        $produit = Produit::findOrFail($data['produit_id']);
        
        // Récupérer le vendeur actif pour la catégorie du produit
        $vendeurActifId = VendeurActif::getVendeurActif($produit->categorie);
        
        if (!$vendeurActifId) {
            throw new \Exception("Aucun vendeur actif pour la catégorie {$produit->categorie}");
        }

        return ReceptionPointeur::create([
            'pointeur_id' => $pointeurId,
            'producteur_id' => $data['producteur_id'],
            'produit_id' => $data['produit_id'],
            'quantite' => $data['quantite'],
            'vendeur_assigne_id' => $vendeurActifId,
            'date_reception' => now(),
            'notes' => $data['notes'] ?? null,
            'verrou' => false,
        ]);
    }

    /**
     * Récupérer les réceptions du jour
     */
    public function getReceptionsDuJour(int $pointeurId)
    {
        return ReceptionPointeur::with(['produit', 'producteur', 'vendeurAssigne'])
            ->where('pointeur_id', $pointeurId)
            ->whereDate('date_reception', today())
            ->orderBy('date_reception', 'desc')
            ->get();
    }

    /**
     * Récupérer toutes les réceptions
     */
    public function getAllReceptions(int $pointeurId)
    {
        return ReceptionPointeur::with(['produit', 'producteur', 'vendeurAssigne'])
            ->where('pointeur_id', $pointeurId)
            ->orderBy('date_reception', 'desc')
            ->paginate(20);
    }

    /**
     * Modifier une réception
     */
    public function modifierReception(int $receptionId, array $data, int $pointeurId)
    {
        $reception = ReceptionPointeur::where('id', $receptionId)
            ->where('pointeur_id', $pointeurId)
            ->firstOrFail();

        // Vérifier si verrouillé
        if ($reception->verrou) {
            throw new \Exception("Cette réception est verrouillée et ne peut plus être modifiée.");
        }

        // Vérifier si le vendeur est toujours actif
        $produit = Produit::find($reception->produit_id);
        $vendeurActifId = VendeurActif::getVendeurActif($produit->categorie);
        
        if ($vendeurActifId !== $reception->vendeur_assigne_id) {
            throw new \Exception("Le vendeur assigné n'est plus actif. Modification impossible.");
        }

        $reception->update([
            'quantite' => $data['quantite'] ?? $reception->quantite,
            'notes' => $data['notes'] ?? $reception->notes,
        ]);

        return $reception->fresh();
    }

    /**
     * Supprimer une réception
     */
    public function supprimerReception(int $receptionId, int $pointeurId)
    {
        $reception = ReceptionPointeur::where('id', $receptionId)
            ->where('pointeur_id', $pointeurId)
            ->firstOrFail();

        if ($reception->verrou) {
            throw new \Exception("Cette réception est verrouillée et ne peut plus être supprimée.");
        }

        $produit = Produit::find($reception->produit_id);
        $vendeurActifId = VendeurActif::getVendeurActif($produit->categorie);
        
        if ($vendeurActifId !== $reception->vendeur_assigne_id) {
            throw new \Exception("Le vendeur assigné n'est plus actif. Suppression impossible.");
        }

        return $reception->delete();
    }

    /**
     * Enregistrer un retour de produit
     */
    public function enregistrerRetour(array $data, int $pointeurId)
    {
        $produit = Produit::findOrFail($data['produit_id']);
        
        // Récupérer le vendeur actif pour la catégorie du produit
        $vendeurActifId = VendeurActif::getVendeurActif($produit->categorie);
        
        if (!$vendeurActifId) {
            throw new \Exception("Aucun vendeur actif pour la catégorie {$produit->categorie}");
        }

        return RetourProduit::create([
            'pointeur_id' => $pointeurId,
            'vendeur_id' => $vendeurActifId,
            'produit_id' => $data['produit_id'],
            'quantite' => $data['quantite'],
            'raison' => $data['raison'],
            'description' => $data['description'] ?? null,
            'date_retour' => now(),
            'verrou' => false,
        ]);
    }

    /**
     * Récupérer les retours du jour
     */
    public function getRetoursDuJour(int $pointeurId)
    {
        return RetourProduit::with(['produit', 'vendeur'])
            ->where('pointeur_id', $pointeurId)
            ->whereDate('date_retour', today())
            ->orderBy('date_retour', 'desc')
            ->get();
    }

    /**
     * Modifier un retour
     */
    public function modifierRetour(int $retourId, array $data, int $pointeurId)
    {
        $retour = RetourProduit::where('id', $retourId)
            ->where('pointeur_id', $pointeurId)
            ->firstOrFail();

        if ($retour->verrou) {
            throw new \Exception("Ce retour est verrouillé et ne peut plus être modifié.");
        }

        $produit = Produit::find($retour->produit_id);
        $vendeurActifId = VendeurActif::getVendeurActif($produit->categorie);
        
        if ($vendeurActifId !== $retour->vendeur_id) {
            throw new \Exception("Le vendeur assigné n'est plus actif. Modification impossible.");
        }

        $retour->update([
            'quantite' => $data['quantite'] ?? $retour->quantite,
            'raison' => $data['raison'] ?? $retour->raison,
            'description' => $data['description'] ?? $retour->description,
        ]);

        return $retour->fresh();
    }

    /**
     * Statistiques du pointeur
     */
    public function getStatistiques(int $pointeurId)
    {
        $today = today();
        
        return [
            'receptions_jour' => ReceptionPointeur::where('pointeur_id', $pointeurId)
                ->whereDate('date_reception', $today)->count(),
            'quantite_totale_jour' => ReceptionPointeur::where('pointeur_id', $pointeurId)
                ->whereDate('date_reception', $today)->sum('quantite'),
            'retours_jour' => RetourProduit::where('pointeur_id', $pointeurId)
                ->whereDate('date_retour', $today)->count(),
            'vendeur_boulangerie' => VendeurActif::with('vendeur')
                ->where('categorie', 'boulangerie')->first(),
            'vendeur_patisserie' => VendeurActif::with('vendeur')
                ->where('categorie', 'patisserie')->first(),
        ];
    }
}
