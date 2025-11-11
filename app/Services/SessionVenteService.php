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
/**
 * Fermer une session de vente
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

        // Calculer les ventes totales automatiquement avec la nouvelle logique
        $ventesTotales = $this->calculerVentesTotales($session);
        
        if ($ventesTotales === null) {
            throw new \Exception("Impossible de calculer les ventes: inventaires incohérents ou manquants");
        }
        
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

        // Formule: Manquant = (Ventes + Fond) - (Versé + Diff OM + Diff MTN)
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
            'valeur_vente' => $ventesTotales,
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
 * Retourne null si les inventaires sont incohérents
 */
public function calculerVentesTotales(SessionVente $session)
{
    $vendeurId = $session->vendeur_id;
    $categorie = $session->categorie;
    $dateSession = Carbon::parse($session->date_ouverture);

    Log::info('=== DÉBUT CALCUL VENTES TOTALES ===', [
        'session_id' => $session->id,
        'vendeur_id' => $vendeurId,
        'categorie' => $categorie,
        'date_session' => $dateSession->toDateString(),
    ]);

    // Récupérer l'inventaire d'entrée (jour demandé OU veille)
    $inventaireDebut = $this->trouverInventaireDebut($vendeurId, $dateSession);
    
    // Récupérer l'inventaire de sortie (jour demandé OU lendemain) avec validation
    $inventaireFin = $inventaireDebut 
        ? $this->trouverInventaireFin($vendeurId, $dateSession, $inventaireDebut)
        : null;
    
    if (!$inventaireDebut || !$inventaireFin) {
        Log::warning('Inventaires incomplets pour le calcul', [
            'has_debut' => $inventaireDebut ? true : false,
            'has_fin' => $inventaireFin ? true : false
        ]);
        return null;
    }

    // Définir la plage opérationnelle
    $dateDebut = Carbon::parse($inventaireDebut->date_inventaire)->startOfDay();
    $dateFin = Carbon::parse($inventaireFin->date_inventaire)->endOfDay();

    // Vérifier que la période ne dépasse pas 24h
    $dureeHeures = $dateDebut->diffInHours($dateFin);
    if ($dureeHeures > 24) {
        Log::error('Période opérationnelle dépasse 24h - Inventaires incohérents', [
            'duree_heures' => $dureeHeures,
            'date_debut' => $dateDebut->toDateTimeString(),
            'date_fin' => $dateFin->toDateTimeString()
        ]);
        return null;
    }

    Log::info('Plage opérationnelle validée', [
        'date_debut' => $dateDebut->toDateTimeString(),
        'date_fin' => $dateFin->toDateTimeString(),
        'duree_heures' => $dureeHeures
    ]);

    // Récupérer tous les produits de la catégorie
    $produits = Produit::where('categorie', $categorie)->where('actif', true)->get();
    
    Log::info('Produits trouvés', [
        'nombre_produits' => $produits->count(),
        'produits' => $produits->pluck('nom', 'id')->toArray()
    ]);

    $ventesTotales = 0;

    foreach ($produits as $produit) {
        Log::info("--- Traitement produit: {$produit->nom} (ID: {$produit->id}) ---");

        // 1. STOCK INITIAL (depuis inventaire début)
        $stockInitial = 0;
        if ($inventaireDebut) {
            $detail = $inventaireDebut->details->where('produit_id', $produit->id)->first();
            $stockInitial = $detail ? $detail->quantite_restante : 0;
        }
        
        Log::info('Stock initial', [
            'produit' => $produit->nom,
            'quantite' => $stockInitial
        ]);

        // 2. ENTRÉES (réceptions dans la plage)
        $entrees = ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
            ->where('produit_id', $produit->id)
            ->whereBetween('date_reception', [$dateDebut, $dateFin])
            ->sum('quantite');
            
        Log::info('Entrées', [
            'produit' => $produit->nom,
            'quantite' => $entrees
        ]);

        // 3. RETOURS (dans la plage)
        $retours = RetourProduit::where('vendeur_id', $vendeurId)
            ->where('produit_id', $produit->id)
            ->whereBetween('date_retour', [$dateDebut, $dateFin])
            ->sum('quantite');
            
        Log::info('Retours', [
            'produit' => $produit->nom,
            'quantite' => $retours
        ]);

        // 4. STOCK FINAL (depuis inventaire fin)
        $stockFinal = 0;
        if ($inventaireFin) {
            $detail = $inventaireFin->details->where('produit_id', $produit->id)->first();
            $stockFinal = $detail ? $detail->quantite_restante : 0;
        }
        
        Log::info('Stock final', [
            'produit' => $produit->nom,
            'quantite' => $stockFinal
        ]);

        // Calcul des quantités vendues
        $quantiteVendue = $stockInitial + $entrees - $retours - $stockFinal;
        
        Log::info('Calcul quantité vendue', [
            'produit' => $produit->nom,
            'formule' => "$stockInitial + $entrees - $retours - $stockFinal",
            'quantite_brute' => $quantiteVendue
        ]);

        // Sécurité: pas de ventes négatives
        if ($quantiteVendue < 0) {
            Log::warning('Quantité vendue négative détectée', [
                'produit' => $produit->nom,
                'quantite_negative' => $quantiteVendue,
                'action' => 'Remise à 0'
            ]);
            $quantiteVendue = 0;
        }

        // Calcul du montant pour ce produit
        $montantProduit = $quantiteVendue * $produit->prix;
        $ventesTotales += $montantProduit;

        Log::info('Montant calculé pour le produit', [
            'produit' => $produit->nom,
            'quantite_vendue' => $quantiteVendue,
            'prix_unitaire' => $produit->prix,
            'montant_produit' => $montantProduit,
            'ventes_totales_cumulees' => $ventesTotales
        ]);
    }

    $ventesTotalesFinales = round($ventesTotales, 2);

    Log::info('=== FIN CALCUL VENTES TOTALES ===', [
        'session_id' => $session->id,
        'ventes_totales_brutes' => $ventesTotales,
        'ventes_totales_arrondies' => $ventesTotalesFinales
    ]);

    return $ventesTotalesFinales;
}

// ========== MÉTHODES PRIVÉES AVEC VALIDATION ==========

/**
 * Trouver l'inventaire d'entrée (vendeur entrant)
 * Cherche sur la date demandée (J) ou la veille (J-1)
 * S'assure que l'inventaire de la veille n'a pas été fermé la veille
 */
private function trouverInventaireDebut($vendeurId, Carbon $date)
{
    Log::info("Recherche inventaire début", [
        'vendeur_id' => $vendeurId,
        'date' => $date->toDateString()
    ]);

    // Chercher le jour même d'abord
    $inventaire = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', $date)
        ->with('details')
        ->first();

    if ($inventaire) {
        Log::info("Inventaire début trouvé le jour même", [
            'inventaire_id' => $inventaire->id,
            'date' => $inventaire->date_inventaire
        ]);
        return $inventaire;
    }

    // Sinon chercher la veille
    $veille = $date->copy()->subDay();
    $inventaireVeille = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', $veille)
        ->with('details')
        ->first();

    if (!$inventaireVeille) {
        Log::info("Aucun inventaire début trouvé (jour ou veille)");
        return null;
    }

    // Vérifier que cet inventaire n'a pas été fermé la veille
    // (c'est-à-dire qu'il n'existe pas d'inventaire sortant pour ce vendeur sur J-1)
    $inventaireFermeVeille = Inventaire::where('vendeur_sortant_id', $vendeurId)
        ->where('valide_sortant', true)
        ->whereDate('date_inventaire', $veille)
        ->exists();

    if ($inventaireFermeVeille) {
        Log::info("L'inventaire de J-1 a déjà été fermé la veille - Non valide pour J", [
            'date_veille' => $veille->toDateString()
        ]);
        return null;
    }

    Log::info("Inventaire début trouvé la veille et non fermé - Valide pour J", [
        'inventaire_id' => $inventaireVeille->id,
        'date' => $inventaireVeille->date_inventaire
    ]);

    return $inventaireVeille;
}

/**
 * Trouver l'inventaire de sortie (vendeur sortant)
 * Cherche sur la date demandée (J) ou le lendemain (J+1)
 * S'assure que l'inventaire du lendemain n'a pas été ouvert le lendemain
 * Vérifie aussi la cohérence avec l'inventaire de début
 */
private function trouverInventaireFin($vendeurId, Carbon $date, $inventaireDebut)
{
    Log::info("Recherche inventaire fin", [
        'vendeur_id' => $vendeurId,
        'date' => $date->toDateString()
    ]);

    // Chercher le jour même d'abord
    $inventaire = Inventaire::where('vendeur_sortant_id', $vendeurId)
        ->where('valide_sortant', true)
        ->whereDate('date_inventaire', $date)
        ->with('details')
        ->first();

    if ($inventaire) {
        // Vérifier la cohérence: l'inventaire de fin sur J doit correspondre à l'inventaire de début
        $dateDebutInventaire = Carbon::parse($inventaireDebut->date_inventaire);
        $dateFinInventaire = Carbon::parse($inventaire->date_inventaire);
        
        // Ils doivent être sur la même date
        if ($dateDebutInventaire->isSameDay($dateFinInventaire)) {
            Log::info("Inventaire fin trouvé le jour même - Cohérent avec l'inventaire début", [
                'inventaire_id' => $inventaire->id,
                'date' => $inventaire->date_inventaire
            ]);
            return $inventaire;
        } else {
            Log::warning("Inventaire fin trouvé sur J mais incohérent avec l'inventaire début", [
                'date_debut' => $dateDebutInventaire->toDateString(),
                'date_fin' => $dateFinInventaire->toDateString()
            ]);
        }
    }

    // Sinon chercher le lendemain
    $lendemain = $date->copy()->addDay();
    $inventaireLendemain = Inventaire::where('vendeur_sortant_id', $vendeurId)
        ->where('valide_sortant', true)
        ->whereDate('date_inventaire', $lendemain)
        ->with('details')
        ->first();

    if (!$inventaireLendemain) {
        Log::info("Aucun inventaire fin trouvé (jour ou lendemain)");
        return null;
    }

    // Vérifier que cet inventaire n'a pas été ouvert le lendemain
    // (c'est-à-dire qu'il n'existe pas d'inventaire entrant pour ce vendeur sur J+1)
    $inventaireOuvertLendemain = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', $lendemain)
        ->exists();

    if ($inventaireOuvertLendemain) {
        Log::info("L'inventaire de J+1 a déjà été ouvert le lendemain - Non valide pour J", [
            'date_lendemain' => $lendemain->toDateString()
        ]);
        return null;
    }

    // Vérifier la cohérence avec l'inventaire de début
    $dateDebutInventaire = Carbon::parse($inventaireDebut->date_inventaire);
    $dateFinInventaire = Carbon::parse($inventaireLendemain->date_inventaire);
    
    // L'inventaire de début doit être J-1 ou J, et l'inventaire de fin J+1
    // La différence doit être <= 1 jour
    $diffJours = $dateDebutInventaire->diffInDays($dateFinInventaire);
    
    if ($diffJours > 1) {
        Log::warning("Inventaire fin sur J+1 mais trop éloigné de l'inventaire début", [
            'diff_jours' => $diffJours,
            'date_debut' => $dateDebutInventaire->toDateString(),
            'date_fin' => $dateFinInventaire->toDateString()
        ]);
        return null;
    }

    // Vérifier que l'inventaire de début est bien J-1 ou J si on utilise un inventaire de fin sur J+1
    if (!$dateDebutInventaire->isSameDay($date->copy()->subDay()) && !$dateDebutInventaire->isSameDay($date)) {
        Log::warning("Inventaire fin sur J+1 mais inventaire début non cohérent", [
            'date_debut' => $dateDebutInventaire->toDateString(),
            'date_attendue' => $date->toDateString() . ' ou ' . $date->copy()->subDay()->toDateString()
        ]);
        return null;
    }

    Log::info("Inventaire fin trouvé le lendemain et non ouvert - Valide pour J", [
        'inventaire_id' => $inventaireLendemain->id,
        'date' => $inventaireLendemain->date_inventaire
    ]);

    return $inventaireLendemain;
}

    /**
     * Récupérer le stock initial du dernier inventaire validé
     */
  public function getStockInitial($vendeurId, $produitId, $dateDebut, $dateFin)
{
    // Correction : Utiliser un tableau pour le contexte
    Log::info("DDDDDDDDDDDDDDDDD\ndate debut", ['dateDebut' => $dateDebut]);
    
    // Chercher le dernier inventaire où le vendeur est ENTRANT et validé
    // avant ou à la date d'ouverture de session
    $inventaire = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereBetween('date_inventaire', [$dateDebut, $dateFin])
        ->orderBy('date_inventaire', 'desc')
        ->first();
        
    if (!$inventaire) {
        Log::info("XXXXXXXXXXXXXXXXXXX\nAucun inventaire trouvé ou le vendeur est entrant");
        return 0;
    }
    
    $detail = DB::table('inventaire_details')
        ->where('inventaire_id', $inventaire->id)
        ->where('produit_id', $produitId)
        ->first();
        
    Log::info("XXXXXXXXXXXXXXXXXXX\nInventaire trouvé avec pour détail", (array)$detail);
    
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
            $stockInitial = $this->getStockInitial($vendeurId, $produit->id, $dateDebut, $dateFin);
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

    /**
 * Modifier une session de vente
 */
public function modifierSession($sessionId, array $data, $pdgId)
{
    DB::beginTransaction();
    try {
        $session = SessionVente::findOrFail($sessionId);
        
        // Si la session est fermée, recalculer le manquant
        if ($session->statut === 'fermee') {
            $ventesTotales = $this->calculerVentesTotales($session);
            
            $diffOM = $data['orange_money_final'] - $data['orange_money_initial'];
            $diffMTN = $data['mtn_money_final'] - $data['mtn_money_initial'];
            
            $manquant = ($ventesTotales + $data['fond_vente']) -
                ($data['montant_verse'] + $diffOM + $diffMTN);
            
            $data['manquant'] = $manquant;
        }
        
        $session->update($data);
        
        DB::commit();
        return $session->load(['vendeur', 'fermeePar']);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Erreur lors de la modification de session", [
            'session_id' => $sessionId,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}

/**
 * Obtenir une session par ID
 */
public function getSessionById($id)
{
    return SessionVente::with(['vendeur', 'fermeePar'])->findOrFail($id);
}

}