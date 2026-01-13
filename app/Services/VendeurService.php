<?php

namespace App\Services;

use App\Models\Produit;
use App\Models\ReceptionPointeur;
use App\Models\RetourProduit;
use App\Models\Inventaire;
use App\Models\InventaireDetail;
use App\Models\SessionVente;
use App\Models\VendeurActif;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VendeurService
{
    /**
     * Récupérer la catégorie du vendeur
     */
    public function getCategorieVendeur(User $vendeur): ?string
    {
        if ($vendeur->role === 'vendeur_boulangerie') {
            return 'boulangerie';
        } elseif ($vendeur->role === 'vendeur_patisserie') {
            return 'patisserie';
        }
        return null;
    }

    /**
     * Vérifier si le vendeur est actuellement actif
     */
    public function estVendeurActif(User $vendeur): bool
    {
        $categorie = $this->getCategorieVendeur($vendeur);
        if (!$categorie) return false;
        
        return VendeurActif::getVendeurActif($categorie) === $vendeur->id;
    }

    /**
     * Récupérer les entrées du jour (réceptions assignées au vendeur)
     */
    public function getEntreesDuJour(int $vendeurId)
    {
        return ReceptionPointeur::with(['produit', 'pointeur', 'producteur'])
            ->where('vendeur_assigne_id', $vendeurId)
            ->whereDate('date_reception', today())
            ->orderBy('date_reception', 'desc')
            ->get();
    }

    /**
     * Récupérer les retours du jour
     */
    public function getRetoursDuJour(int $vendeurId)
    {
        return RetourProduit::with(['produit', 'pointeur'])
            ->where('vendeur_id', $vendeurId)
            ->whereDate('date_retour', today())
            ->orderBy('date_retour', 'desc')
            ->get();
    }

    /**
     * Créer une session de vente
     */
    public function creerSession(array $data, User $vendeur)
    {
        $categorie = $this->getCategorieVendeur($vendeur);
        
        if (!$categorie) {
            throw new \Exception("Rôle vendeur invalide");
        }

        // Vérifier qu'il n'y a pas déjà une session ouverte pour ce vendeur
        $sessionOuverte = SessionVente::where('vendeur_id', $vendeur->id)
            ->where('statut', 'ouverte')
            ->first();

        if ($sessionOuverte) {
            throw new \Exception("Vous avez déjà une session de vente ouverte");
        }

        return SessionVente::create([
            'vendeur_id' => $vendeur->id,
            'categorie' => $categorie,
            'fond_vente' => $data['fond_vente'] ?? 0,
            'orange_money_initial' => $data['orange_money_initial'] ?? 0,
            'mtn_money_initial' => $data['mtn_money_initial'] ?? 0,
            'statut' => 'ouverte',
            'date_ouverture' => now(),
        ]);
    }

    /**
     * Récupérer la session active du vendeur
     */
    public function getSessionActive(int $vendeurId)
    {
        return SessionVente::where('vendeur_id', $vendeurId)
            ->where('statut', 'ouverte')
            ->first();
    }

    /**
     * Récupérer l'historique des sessions
     */
    public function getHistoriqueSessions(int $vendeurId)
    {
        return SessionVente::where('vendeur_id', $vendeurId)
            ->orderBy('date_ouverture', 'desc')
            ->paginate(10);
    }

    /**
     * Récupérer les autres vendeurs de la même catégorie pour l'inventaire
     */
    public function getVendeursCategorie(User $vendeur)
    {
        $categorie = $this->getCategorieVendeur($vendeur);
        $role = $vendeur->role;
        
        return User::where('role', $role)
            ->where('id', '!=', $vendeur->id)
            ->where('actif', true)
            ->get();
    }

    /**
     * Créer un inventaire (vendeur sortant)
     */
    public function creerInventaire(array $data, User $vendeurSortant)
    {
        $categorie = $this->getCategorieVendeur($vendeurSortant);
        
        if (!$this->estVendeurActif($vendeurSortant)) {
            throw new \Exception("Vous n'êtes pas le vendeur actif actuellement");
        }

        // Vérifier le vendeur entrant
        $vendeurEntrant = User::findOrFail($data['vendeur_entrant_id']);
        if ($this->getCategorieVendeur($vendeurEntrant) !== $categorie) {
            throw new \Exception("Le vendeur entrant doit être de la même catégorie");
        }

        return DB::transaction(function () use ($data, $vendeurSortant, $vendeurEntrant, $categorie) {
            $inventaire = Inventaire::create([
                'vendeur_sortant_id' => $vendeurSortant->id,
                'vendeur_entrant_id' => $vendeurEntrant->id,
                'categorie' => $categorie,
                'valide_sortant' => false,
                'valide_entrant' => false,
                'date_inventaire' => now(),
            ]);

            // Créer les détails d'inventaire
            foreach ($data['produits'] as $produitData) {
                InventaireDetail::create([
                    'inventaire_id' => $inventaire->id,
                    'produit_id' => $produitData['produit_id'],
                    'quantite_restante' => $produitData['quantite_restante'],
                ]);
            }

            return $inventaire->load('details.produit');
        });
    }

    /**
     * Récupérer l'inventaire en cours (en attente de validation)
     */
    public function getInventaireEnCours(User $vendeur)
    {
        return Inventaire::with(['details.produit', 'vendeurSortant', 'vendeurEntrant'])
            ->where(function ($query) use ($vendeur) {
                $query->where('vendeur_sortant_id', $vendeur->id)
                    ->orWhere('vendeur_entrant_id', $vendeur->id);
            })
            ->where(function ($query) {
                $query->where('valide_sortant', false)
                    ->orWhere('valide_entrant', false);
            })
            ->first();
    }

    /**
     * Valider l'inventaire (avec code PIN)
     */
    public function validerInventaire(int $inventaireId, User $vendeur, string $codePin)
    {
        $inventaire = Inventaire::findOrFail($inventaireId);
        
        // Vérifier le code PIN
        if ($vendeur->code_pin !== $codePin) {
            throw new \Exception("Code PIN incorrect");
        }

        $estSortant = $inventaire->vendeur_sortant_id === $vendeur->id;
        $estEntrant = $inventaire->vendeur_entrant_id === $vendeur->id;

        if (!$estSortant && !$estEntrant) {
            throw new \Exception("Vous n'êtes pas concerné par cet inventaire");
        }

        return DB::transaction(function () use ($inventaire, $estSortant, $estEntrant) {
            if ($estSortant && !$inventaire->valide_sortant) {
                $inventaire->update(['valide_sortant' => true]);
            } elseif ($estEntrant && !$inventaire->valide_entrant) {
                $inventaire->update(['valide_entrant' => true]);
            }

            $inventaire->refresh();

            // Si les deux ont validé, effectuer le changement de vendeur
            if ($inventaire->valide_sortant && $inventaire->valide_entrant) {
                VendeurActif::setVendeurActif(
                    $inventaire->categorie,
                    $inventaire->vendeur_entrant_id
                );
            }

            return $inventaire;
        });
    }

    /**
     * Ajouter un produit à l'inventaire en cours
     */
    public function ajouterProduitInventaire(int $inventaireId, array $data, User $vendeur)
    {
        $inventaire = Inventaire::findOrFail($inventaireId);
        
        if ($inventaire->vendeur_sortant_id !== $vendeur->id) {
            throw new \Exception("Seul le vendeur sortant peut modifier l'inventaire");
        }

        if ($inventaire->valide_sortant) {
            throw new \Exception("L'inventaire a déjà été validé par le vendeur sortant");
        }

        // Vérifier si le produit existe déjà dans l'inventaire
        $detailExistant = InventaireDetail::where('inventaire_id', $inventaireId)
            ->where('produit_id', $data['produit_id'])
            ->first();

        if ($detailExistant) {
            $detailExistant->update(['quantite_restante' => $data['quantite_restante']]);
            return $detailExistant;
        }

        return InventaireDetail::create([
            'inventaire_id' => $inventaireId,
            'produit_id' => $data['produit_id'],
            'quantite_restante' => $data['quantite_restante'],
        ]);
    }

    /**
     * Récupérer les produits de la catégorie du vendeur
     */
    public function getProduitsCategorie(User $vendeur)
    {
        $categorie = $this->getCategorieVendeur($vendeur);
        return Produit::actif()->where('categorie', $categorie)->orderBy('nom')->get();
    }

    /**
     * Statistiques du vendeur
     */
    public function getStatistiques(User $vendeur)
    {
        $today = today();
        
        return [
            'est_actif' => $this->estVendeurActif($vendeur),
            'entrees_jour' => ReceptionPointeur::where('vendeur_assigne_id', $vendeur->id)
                ->whereDate('date_reception', $today)->count(),
            'quantite_recue_jour' => ReceptionPointeur::where('vendeur_assigne_id', $vendeur->id)
                ->whereDate('date_reception', $today)->sum('quantite'),
            'retours_jour' => RetourProduit::where('vendeur_id', $vendeur->id)
                ->whereDate('date_retour', $today)->count(),
            'session_active' => $this->getSessionActive($vendeur->id),
            'inventaire_en_cours' => $this->getInventaireEnCours($vendeur),
        ];
    }
}
