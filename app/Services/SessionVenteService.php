<?php

namespace App\Services;

use App\Models\SessionVente;
use App\Models\Inventaire;
use App\Models\ReceptionPointeur;
use App\Models\RetourProduit;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SessionVenteService
{
    /**
     * Fermer une session de vente avec calcul automatique des ventes
     */
   public function fermerSession($sessionId, array $data, $pdgId)
{
    DB::beginTransaction();
    try {
        Log::info("Début fermeture session", [
            'session_id' => $sessionId,
            'pdg_id' => $pdgId,
            'data' => $data
        ]);

        $session = SessionVente::findOrFail($sessionId);
        
        if ($session->statut === 'fermee') {
            Log::warning("Tentative de fermeture d'une session déjà fermée", [
                'session_id' => $sessionId
            ]);
            throw new \Exception("Cette session est déjà fermée");
        }

        // Calculer les ventes totales automatiquement
        $ventesTotales = $this->calculerVentesTotales($session);
        Log::info("Ventes totales calculées", [
            'session_id' => $sessionId,
            'ventes_totales' => $ventesTotales
        ]);

        // Calculer les différences Mobile Money
        $diffOM = $data['orange_money_final'] - $session->orange_money_initial;
        $diffMTN = $data['mtn_money_final'] - $session->mtn_money_initial;
        
        Log::info("Différences Mobile Money calculées", [
            'session_id' => $sessionId,
            'diff_om' => $diffOM,
            'om_initial' => $session->orange_money_initial,
            'om_final' => $data['orange_money_final'],
            'diff_mtn' => $diffMTN,
            'mtn_initial' => $session->mtn_money_initial,
            'mtn_final' => $data['mtn_money_final']
        ]);

        $manquant = ($ventesTotales + $session->fond_vente) -
            ($data['montant_verse'] + $diffOM + $diffMTN);
        
        Log::info("Calcul du manquant", [
            'session_id' => $sessionId,
            'ventes_totales' => $ventesTotales,
            'fond_vente' => $session->fond_vente,
            'montant_verse' => $data['montant_verse'],
            'diff_om' => $diffOM,
            'diff_mtn' => $diffMTN,
            'manquant_calcule' => $manquant
        ]);

        $session->update([
            'montant_verse' => $data['montant_verse'],
            'orange_money_final' => $data['orange_money_final'],
            'mtn_money_final' => $data['mtn_money_final'],
            'manquant' => $manquant,
            'statut' => 'fermee',
            'fermee_par' => $pdgId,
            'date_fermeture' => now(),
        ]);

        Log::info("Session fermée avec succès", [
            'session_id' => $sessionId,
            'manquant' => $manquant,
            'statut' => 'fermee'
        ]);

        DB::commit();

        return [
            'session' => $session->load(['vendeur', 'fermeePar']),
            'ventes_totales' => $ventesTotales,
            'details_calcul' => $this->getDetailsCalcul($session)
        ];

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Erreur lors de la fermeture de session", [
            'session_id' => $sessionId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
}

    /**
     * Calculer les ventes totales de la session
     * Formule: Ventes = (Stock initial + Entrées - Retours - Stock final) × Prix
     */
    public function calculerVentesTotales(SessionVente $session)
{
    $vendeurId = $session->vendeur_id;
    $categorie = $session->categorie;
    $dateDebut = Carbon::parse($session->date_ouverture)->startOfDay();
    $dateFin = now()->endOfDay();

    \Log::info('=== DÉBUT CALCUL VENTES TOTALES ===', [
        'session_id' => $session->id,
        'vendeur_id' => $vendeurId,
        'categorie' => $categorie,
        'date_debut' => $dateDebut->toDateTimeString(),
        'date_fin' => $dateFin->toDateTimeString(),
    ]);

    // Récupérer tous les produits de la catégorie
    $produits = Produit::where('categorie', $categorie)->get();
    
    \Log::info('Produits trouvés', [
        'nombre_produits' => $produits->count(),
        'produits' => $produits->pluck('nom', 'id')->toArray()
    ]);

    $ventesTotales = 0;

    foreach ($produits as $produit) {
        \Log::info("--- Traitement produit: {$produit->nom} (ID: {$produit->id}) ---");

        // 1. STOCK INITIAL (dernier inventaire validé avant/lors de l'ouverture)
        $stockInitial = $this->getStockInitial($vendeurId, $produit->id, $dateDebut);
        \Log::info('Stock initial', [
            'produit' => $produit->nom,
            'quantite' => $stockInitial
        ]);

        // 2. ENTRÉES (réceptions du pointeur pendant la session)
        $entrees = $this->getEntrees($vendeurId, $produit->id, $dateDebut, $dateFin);
        \Log::info('Entrées', [
            'produit' => $produit->nom,
            'quantite' => $entrees
        ]);

        // 3. RETOURS (produits retournés pendant la session)
        $retours = $this->getRetours($vendeurId, $produit->id, $dateDebut, $dateFin);
        \Log::info('Retours', [
            'produit' => $produit->nom,
            'quantite' => $retours
        ]);

        // 4. STOCK FINAL (inventaire de sortie si existe, sinon 0)
        $stockFinal = $this->getStockFinal($vendeurId, $produit->id,$dateDebut, $dateFin);
        \Log::info('Stock final', [
            'produit' => $produit->nom,
            'quantite' => $stockFinal
        ]);

        // Calcul des quantités vendues
        $quantiteVendue = $stockInitial + $entrees - $retours - $stockFinal;
        
        \Log::info('Calcul quantité vendue', [
            'produit' => $produit->nom,
            'formule' => "$stockInitial + $entrees - $retours - $stockFinal",
            'quantite_brute' => $quantiteVendue
        ]);

        // Sécurité: pas de ventes négatives
        if ($quantiteVendue < 0) {
            \Log::warning('Quantité vendue négative détectée', [
                'produit' => $produit->nom,
                'quantite_negative' => $quantiteVendue,
                'action' => 'Remise à 0'
            ]);
            $quantiteVendue = 0;
        }

        // Calcul du montant pour ce produit
        $montantProduit = $quantiteVendue * $produit->prix;
        $ventesTotales += $montantProduit;

        \Log::info('Montant calculé pour le produit', [
            'produit' => $produit->nom,
            'quantite_vendue' => $quantiteVendue,
            'prix_unitaire' => $produit->prix,
            'montant_produit' => $montantProduit,
            'ventes_totales_cumulees' => $ventesTotales
        ]);
    }

    $ventesTotalesFinales = round($ventesTotales, 2);

    \Log::info('=== FIN CALCUL VENTES TOTALES ===', [
        'session_id' => $session->id,
        'ventes_totales_brutes' => $ventesTotales,
        'ventes_totales_arrondies' => $ventesTotalesFinales
    ]);

    return $ventesTotalesFinales;
}

    /**
     * Récupérer le stock initial du dernier inventaire validé
     */
    public function getStockInitial($vendeurId, $produitId, $dateDebut)
    {
        // Chercher le dernier inventaire où le vendeur est ENTRANT et validé
        // avant ou à la date d'ouverture de session
        $inventaire = Inventaire::where('vendeur_entrant_id', $vendeurId)
            ->where('valide_entrant', true)
            ->where('date_inventaire', '<=', $dateDebut)
            ->orderBy('date_inventaire', 'desc')
            ->first();

        if (!$inventaire) {
            return 0;
        }

        $detail = DB::table('inventaire_details')
            ->where('inventaire_id', $inventaire->id)
            ->where('produit_id', $produitId)
            ->first();

        return $detail ? $detail->quantite_restante : 0;
    }

    /**
     * Récupérer les entrées (réceptions) pendant la session
     */
    public function getEntrees($vendeurId, $produitId, $dateDebut, $dateFin)
{
    \Log::info('getEntrees appelé', [
        'vendeur_id' => $vendeurId,
        'produit_id' => $produitId,
        'date_debut' => $dateDebut,
        'date_fin' => $dateFin
    ]);

    $query = ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
        ->where('produit_id', $produitId)
        ->whereBetween('date_reception', [$dateDebut, $dateFin]);


    $resultat = $query->sum('quantite');

    \Log::info('Résultat getEntrees', [
        'total_entrees' => $resultat,
        'nombre_enregistrements' => $query->count()
    ]);

    return $resultat;
}

    /**
     * Récupérer les retours pendant la session
     */
    public function getRetours($vendeurId, $produitId, $dateDebut, $dateFin)
    {
        return RetourProduit::where('vendeur_id', $vendeurId)
            ->where('produit_id', $produitId)
            ->whereBetween('date_retour', [$dateDebut, $dateFin])
            ->sum('quantite');
    }

    /**
     * Récupérer le stock final (inventaire de sortie)
     */
    public function getStockFinal($vendeurId, $produitId, $dateDebut, $dateFin)
    {
        // Chercher un inventaire où le vendeur est SORTANT
        // autour de la date de fin (±30 minutes de tolérance)
        $inventaire = Inventaire::where('vendeur_sortant_id', $vendeurId)
            ->where('valide_sortant', true)
            ->whereBetween('date_inventaire', [$dateDebut, $dateFin])
            ->orderBy('date_inventaire', 'desc')
            ->first();

        if (!$inventaire) {
            // Si pas d'inventaire de sortie, considérer stock final = 0
            return 0;
        }

        $detail = DB::table('inventaire_details')
            ->where('inventaire_id', $inventaire->id)
            ->where('produit_id', $produitId)
            ->first();

        return $detail ? $detail->quantite_restante : 0;
    }

    /**
     * Obtenir les détails du calcul pour vérification
     */
    public function getDetailsCalcul(SessionVente $session)
    {
        $vendeurId = $session->vendeur_id;
        $categorie = $session->categorie;
        $dateDebut = Carbon::parse($session->date_ouverture);
        $dateFin = now();

        $produits = Produit::where('categorie', $categorie)->get();
        $details = [];

        foreach ($produits as $produit) {
            $stockInitial = $this->getStockInitial($vendeurId, $produit->id, $dateDebut);
            $entrees = $this->getEntrees($vendeurId, $produit->id, $dateDebut, $dateFin);
            $retours = $this->getRetours($vendeurId, $produit->id, $dateDebut, $dateFin);
            $stockFinal = $this->getStockFinal($vendeurId, $produit->id, $dateDebut, $dateFin);
            $quantiteVendue = max(0, $stockInitial + $entrees - $retours - $stockFinal);
            $montantVendu = $quantiteVendue * $produit->prix;

            if ($quantiteVendue > 0) {
                $details[] = [
                    'produit' => $produit->nom,
                    'prix_unitaire' => $produit->prix,
                    'stock_initial' => $stockInitial,
                    'entrees' => $entrees,
                    'retours' => $retours,
                    'stock_final' => $stockFinal,
                    'quantite_vendue' => $quantiteVendue,
                    'montant_vendu' => $montantVendu,
                ];
            }
        }

        return $details;
    }

    /**
     * Ouvrir une session de vente
     */
    public function ouvrirSession(array $data, $vendeurId)
    {
        DB::beginTransaction();
        try {
            // Vérifier qu'aucune session n'est déjà ouverte pour ce vendeur
            $sessionOuverte = SessionVente::where('vendeur_id', $vendeurId)
                ->where('statut', 'ouverte')
                ->exists();

            if ($sessionOuverte) {
                throw new \Exception("Vous avez déjà une session ouverte");
            }

            $session = SessionVente::create([
                'vendeur_id' => $vendeurId,
                'categorie' => $data['categorie'],
                'fond_vente' => $data['fond_vente'],
                'orange_money_initial' => $data['orange_money_initial'] ?? 0,
                'mtn_money_initial' => $data['mtn_money_initial'] ?? 0,
                'statut' => 'ouverte',
                'date_ouverture' => now(),
            ]);

            DB::commit();
            return $session->load('vendeur');
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtenir la session active d'un vendeur
     */
    public function getSessionActive($vendeurId)
    {
        return SessionVente::where('vendeur_id', $vendeurId)
            ->where('statut', 'ouverte')
            ->with('vendeur')
            ->first();
    }

    /**
     * Obtenir l'historique des sessions
     */
    public function getHistorique($vendeurId = null, $filters = [])
    {
        $query = SessionVente::with(['vendeur', 'fermeePar'])
            ->orderBy('date_ouverture', 'desc');

        if ($vendeurId) {
            $query->where('vendeur_id', $vendeurId);
        }

        if (isset($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (isset($filters['date_debut'])) {
            $query->where('date_ouverture', '>=', $filters['date_debut']);
        }

        if (isset($filters['date_fin'])) {
            $query->where('date_ouverture', '<=', $filters['date_fin']);
        }

        return $query->paginate(20);
    }
}