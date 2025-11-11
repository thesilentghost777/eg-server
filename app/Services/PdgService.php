<?php

namespace App\Services;

use App\Models\SessionVente;
use App\Models\Inventaire;
use App\Models\ReceptionPointeur;
use App\Models\RetourProduit;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PdgService
{
    /**
     * Obtenir les données du dashboard
     */
    public function getDashboardData($limit = 10)
    {
        return [
            'receptions' => $this->getRecentReceptions($limit),
            'inventaires' => $this->getRecentInventaires($limit),
            'sessions_vente' => $this->getRecentSessionsVente($limit),
            'statistiques' => $this->getStatistiquesGlobales(),
        ];
    }

    /**
     * Récupérer les réceptions récentes
     */
    private function getRecentReceptions($limit)
    {
        return ReceptionPointeur::with(['pointeur', 'producteur', 'produit', 'vendeurAssigne'])
            ->orderBy('date_reception', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($reception) {
                return [
                    'id' => $reception->id,
                    'pointeur' => $reception->pointeur->name,
                    'producteur' => $reception->producteur->name,
                    'produit' => $reception->produit->nom,
                    'categorie' => $reception->produit->categorie,
                    'quantite' => $reception->quantite,
                    'vendeur_assigne' => $reception->vendeurAssigne ? $reception->vendeurAssigne->name : 'Non assigné',
                    'verrou' => $reception->verrou,
                    'date_reception' => $reception->date_reception,
                    'notes' => $reception->notes,
                ];
            });
    }

    /**
     * Récupérer les inventaires récents
     */
    private function getRecentInventaires($limit)
    {
        return Inventaire::with(['vendeurSortant', 'vendeurEntrant', 'details.produit'])
            ->orderBy('date_inventaire', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($inventaire) {
                return [
                    'id' => $inventaire->id,
                    'vendeur_sortant' => $inventaire->vendeurSortant->name,
                    'vendeur_entrant' => $inventaire->vendeurEntrant->name,
                    'categorie' => $inventaire->categorie,
                    'valide_sortant' => $inventaire->valide_sortant,
                    'valide_entrant' => $inventaire->valide_entrant,
                    'statut' => $this->getStatutInventaire($inventaire),
                    'date_inventaire' => $inventaire->date_inventaire,
                    'nombre_produits' => $inventaire->details->count(),
                ];
            });
    }

    /**
     * Récupérer les sessions de vente récentes
     */
    private function getRecentSessionsVente($limit)
    {
        return SessionVente::with(['vendeur', 'fermeePar'])
            ->orderBy('date_ouverture', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($session) {
                return $this->formatSessionVente($session);
            });
    }

    /**
     * Obtenir les statistiques globales
     */
    private function getStatistiquesGlobales()
    {
        $today = Carbon::today();
        
        return [
            'sessions_ouvertes' => SessionVente::where('statut', 'ouverte')->count(),
            'receptions_aujourdhui' => ReceptionPointeur::whereDate('date_reception', $today)->count(),
            'inventaires_en_attente' => Inventaire::where(function ($query) {
                $query->where('valide_sortant', false)
                      ->orWhere('valide_entrant', false);
            })->count(),
            'total_ventes_aujourdhui' => $this->getTotalVentesAujourdhui(),
        ];
    }

    /**
     * Calculer le total des ventes du jour
     */
    private function getTotalVentesAujourdhui()
    {
        $sessionsFermees = SessionVente::where('statut', 'fermee')
            ->whereDate('date_fermeture', Carbon::today())
            ->get();

        $total = 0;
        foreach ($sessionsFermees as $session) {
            $ventes = $this->calculerVentesSession($session);
            $total += $ventes;
        }

        return round($total, 2);
    }

/**
 * Obtenir le flux opérationnel détaillé pour une date
 */
public function getFluxOperationnel($date, $vendeurId = null, $produitId = null)
{
    $dateCarbon = Carbon::parse($date);
    
    Log::info("=== FLUX OPERATIONNEL ===");
    Log::info("Date: {$date}, Vendeur ID: {$vendeurId}, Produit ID: {$produitId}");
    
    // Récupérer tous les vendeurs actifs ou un vendeur spécifique
    $vendeurs = $vendeurId 
        ? User::where('id', $vendeurId)->get()
        : User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])->where('actif', true)->get();

    Log::info("Nombre de vendeurs à traiter: " . $vendeurs->count());

    $flux = [];

    foreach ($vendeurs as $vendeur) {
        Log::info("Traitement vendeur: {$vendeur->name} (ID: {$vendeur->id})");
        
        $fluxVendeur = $this->getFluxParVendeur($vendeur->id, $date, $produitId);
        
        if (!$fluxVendeur) {
            Log::info("Vendeur {$vendeur->name} n'a aucune plage opérationnelle valide pour cette date");
            continue;
        }
        
        // Ne garder que les vendeurs qui ont une activité
        $hasActivite = collect($fluxVendeur['flux'])->sum(function($item) {
            return $item['quantite_trouvee'] + $item['quantite_recue'] + 
                   $item['quantite_retour'] + $item['quantite_restante'];
        }) > 0;
        
        if ($hasActivite) {
            Log::info("Vendeur {$vendeur->name} a une activité");
            $flux[] = [
                'vendeur' => [
                    'id' => $vendeur->id,
                    'nom' => $vendeur->name,
                    'role' => $vendeur->role,
                ],
                'periode' => $fluxVendeur['periode'],
                'produits' => $fluxVendeur['flux'],
                'total_ventes' => $fluxVendeur['total_ventes'],
            ];
        } else {
            Log::info("Vendeur {$vendeur->name} n'a aucune activité pour cette date");
        }
    }

    Log::info("Nombre de vendeurs avec activité: " . count($flux));

    return [
        'date' => $date,
        'flux' => $flux,
        'resume' => $this->getResumeFlux($flux),
    ];
}

/**
 * Obtenir le flux par vendeur
 */
public function getFluxParVendeur($vendeurId, $date = null, $produitId = null)
{
    $date = $date ?? now()->toDateString();
    $dateCarbon = Carbon::parse($date);
    
    Log::info("--- Flux pour vendeur {$vendeurId} ---");
    Log::info("Date demandée: {$date}");
    
    // Trouver l'inventaire de début (vendeur_entrant)
    $inventaireDebut = $this->trouverInventaireDebut($vendeurId, $dateCarbon);
    
    if (!$inventaireDebut) {
        Log::info("Aucun inventaire début trouvé pour le vendeur");
        return null;
    }
    
    Log::info("Inventaire début trouvé - ID: {$inventaireDebut->id}, Date: {$inventaireDebut->date_inventaire}");
    
    // Trouver l'inventaire de fin (vendeur_sortant) qui correspond
    $inventaireFin = $this->trouverInventaireFin($vendeurId, $dateCarbon, $inventaireDebut);
    
    if ($inventaireFin) {
        Log::info("Inventaire fin trouvé - ID: {$inventaireFin->id}, Date: {$inventaireFin->date_inventaire}");
    } else {
        Log::info("Aucun inventaire fin trouvé");
    }
    
    // Définir la plage opérationnelle
    $dateDebut = Carbon::parse($inventaireDebut->date_inventaire)->startOfDay();
    $dateFin = $inventaireFin 
        ? Carbon::parse($inventaireFin->date_inventaire)->endOfDay()
        : $dateCarbon->copy()->endOfDay();
    
    // Vérifier que la période ne dépasse pas 24h
    $dureeHeures = $dateDebut->diffInHours($dateFin);
    if ($dureeHeures > 24) {
        Log::warning("Période opérationnelle dépasse 24h ({$dureeHeures}h) - Inventaires incohérents");
        return null;
    }
    
    Log::info("Période opérationnelle valide: de {$dateDebut} à {$dateFin} ({$dureeHeures}h)");
    
    // Récupérer toutes les réceptions dans la plage opérationnelle
    $receptions = ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
        ->whereBetween('date_reception', [$dateDebut, $dateFin])
        ->with('produit')
        ->get();
    
    Log::info("Réceptions trouvées: " . $receptions->count());
    
    // Récupérer tous les retours dans la plage opérationnelle
    $retours = RetourProduit::where('vendeur_id', $vendeurId)
        ->whereBetween('date_retour', [$dateDebut, $dateFin])
        ->with('produit')
        ->get();
    
    Log::info("Retours trouvés: " . $retours->count());
    
    // Construire le flux pour chaque produit
    $flux = [];
    $produitsQuery = Produit::where('actif', true);
    
    if ($produitId) {
        $produitsQuery->where('id', $produitId);
    }
    
    $produits = $produitsQuery->get();
    $totalVentes = 0;
    $produitsAvecActivite = 0;
    
    Log::info("Produits à analyser: " . $produits->count());
    
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
        $quantiteVendue = max(0, $quantiteTrouvee + $quantiteRecue - $quantiteRetour - $quantiteRestante);
        $valeurVente = $quantiteVendue * $produit->prix;
        $totalVentes += $valeurVente;
        
        // Log seulement les produits avec activité
        if ($quantiteTrouvee > 0 || $quantiteRecue > 0 || $quantiteRetour > 0 || $quantiteRestante > 0) {
            $produitsAvecActivite++;
            Log::info("Produit [{$produit->id}] {$produit->nom}: T={$quantiteTrouvee}, R={$quantiteRecue}, Ret={$quantiteRetour}, Rest={$quantiteRestante}, V={$quantiteVendue}");
        }
        
        $flux[] = [
            'produit_id' => $produit->id,
            'produit_nom' => $produit->nom,
            'produit_categorie' => $produit->categorie,
            'prix_unitaire' => $produit->prix,
            'quantite_trouvee' => $quantiteTrouvee,
            'quantite_recue' => $quantiteRecue,
            'quantite_retour' => $quantiteRetour,
            'quantite_restante' => $quantiteRestante,
            'quantite_vendue' => $quantiteVendue,
            'valeur_vente' => round($valeurVente, 2),
        ];
    }
    
    Log::info("Produits avec activité: {$produitsAvecActivite}");
    Log::info("Total ventes: {$totalVentes}");
    
    return [
        'periode' => [
            'debut' => $dateDebut->toDateTimeString(),
            'fin' => $dateFin->toDateTimeString(),
            'date' => $dateCarbon->toDateString(),
            'duree_heures' => $dureeHeures,
        ],
        'flux' => $flux,
        'total_ventes' => round($totalVentes, 2),
        'stats' => [
            'has_inventaire_debut' => $inventaireDebut ? true : false,
            'has_inventaire_fin' => $inventaireFin ? true : false,
            'total_receptions' => $receptions->count(),
            'total_retours' => $retours->count(),
            'produits_avec_activite' => $produitsAvecActivite,
        ]
    ];
}

/**
 * Trouver l'inventaire de début (vendeur entrant)
 * Cherche sur la date demandée (J) ou la veille (J-1)
 * S'assure que l'inventaire de la veille n'a pas été fermé la veille
 */
private function trouverInventaireDebut($vendeurId, Carbon $date)
{
    // Chercher sur J
    $inventaire = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', $date)
        ->with('details')
        ->first();
    
    if ($inventaire) {
        Log::info("Inventaire entrant trouvé sur J ({$date->toDateString()})");
        return $inventaire;
    }
    
    // Chercher sur J-1
    $dateVeille = $date->copy()->subDay();
    $inventaireVeille = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', $dateVeille)
        ->with('details')
        ->first();
    
    if (!$inventaireVeille) {
        Log::info("Aucun inventaire entrant trouvé sur J-1 ({$dateVeille->toDateString()})");
        return null;
    }
    
    // Vérifier que cet inventaire n'a pas été fermé la veille
    // (c'est-à-dire qu'il n'existe pas d'inventaire sortant pour ce vendeur sur J-1)
    $inventaireFermeVeille = Inventaire::where('vendeur_sortant_id', $vendeurId)
        ->where('valide_sortant', true)
        ->whereDate('date_inventaire', $dateVeille)
        ->exists();
    
    if ($inventaireFermeVeille) {
        Log::info("L'inventaire de J-1 a déjà été fermé la veille - Non valide pour J");
        return null;
    }
    
    Log::info("Inventaire entrant trouvé sur J-1 ({$dateVeille->toDateString()}) et non fermé - Valide pour J");
    return $inventaireVeille;
}

/**
 * Trouver l'inventaire de fin (vendeur sortant)
 * Cherche sur la date demandée (J) ou le lendemain (J+1)
 * S'assure que l'inventaire du lendemain n'a pas été ouvert le lendemain
 * Vérifie aussi la cohérence avec l'inventaire de début
 */
private function trouverInventaireFin($vendeurId, Carbon $date, $inventaireDebut)
{
    // Chercher sur J
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
            Log::info("Inventaire sortant trouvé sur J ({$date->toDateString()}) - Cohérent avec l'inventaire entrant");
            return $inventaire;
        } else {
            Log::warning("Inventaire sortant sur J mais incohérent avec l'inventaire entrant (dates différentes)");
        }
    }
    
    // Chercher sur J+1
    $dateLendemain = $date->copy()->addDay();
    $inventaireLendemain = Inventaire::where('vendeur_sortant_id', $vendeurId)
        ->where('valide_sortant', true)
        ->whereDate('date_inventaire', $dateLendemain)
        ->with('details')
        ->first();
    
    if (!$inventaireLendemain) {
        Log::info("Aucun inventaire sortant trouvé sur J+1 ({$dateLendemain->toDateString()})");
        return null;
    }
    
    // Vérifier que cet inventaire n'a pas été ouvert le lendemain
    // (c'est-à-dire qu'il n'existe pas d'inventaire entrant pour ce vendeur sur J+1)
    $inventaireOuvertLendemain = Inventaire::where('vendeur_entrant_id', $vendeurId)
        ->where('valide_entrant', true)
        ->whereDate('date_inventaire', $dateLendemain)
        ->exists();
    
    if ($inventaireOuvertLendemain) {
        Log::info("L'inventaire de J+1 a déjà été ouvert le lendemain - Non valide pour J");
        return null;
    }
    
    // Vérifier la cohérence avec l'inventaire de début
    $dateDebutInventaire = Carbon::parse($inventaireDebut->date_inventaire);
    $dateFinInventaire = Carbon::parse($inventaireLendemain->date_inventaire);
    
    // L'inventaire de début doit être J-1 ou J, et l'inventaire de fin J+1
    // La différence doit être <= 1 jour
    $diffJours = $dateDebutInventaire->diffInDays($dateFinInventaire);
    
    if ($diffJours > 1) {
        Log::warning("Inventaire sortant sur J+1 mais trop éloigné de l'inventaire entrant ({$diffJours} jours)");
        return null;
    }
    
    // Vérifier que l'inventaire de début est bien J-1 si on utilise un inventaire de fin sur J+1
    if (!$dateDebutInventaire->isSameDay($date->copy()->subDay()) && !$dateDebutInventaire->isSameDay($date)) {
        Log::warning("Inventaire sortant sur J+1 mais inventaire entrant non cohérent");
        return null;
    }
    
    Log::info("Inventaire sortant trouvé sur J+1 ({$dateLendemain->toDateString()}) et non ouvert - Valide pour J");
    return $inventaireLendemain;
}
    /**
     * Obtenir la liste détaillée des sessions de vente avec filtres
     */
    public function getSessionsVenteDetaillees($filters = [])
    {
        $query = SessionVente::with(['vendeur', 'fermeePar']);

        // Filtres
        if (isset($filters['vendeur_id'])) {
            $query->where('vendeur_id', $filters['vendeur_id']);
        }

        if (isset($filters['categorie'])) {
            $query->where('categorie', $filters['categorie']);
        }

        if (isset($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (isset($filters['date_debut'])) {
            $query->whereDate('date_ouverture', '>=', $filters['date_debut']);
        }

        if (isset($filters['date_fin'])) {
            $query->whereDate('date_ouverture', '<=', $filters['date_fin']);
        }

        $sessions = $query->orderBy('date_ouverture', 'desc')->get();

        return $sessions->map(function ($session) {
            return $this->getSessionAvecVentes($session);
        });
    }

    /**
     * Obtenir une session avec le détail des ventes
     */
    public function getSessionAvecVentes(SessionVente $session)
    {
        $sessionData = $this->formatSessionVente($session);
        
        if ($session->statut === 'fermee') {
            $sessionData['details_ventes'] = $this->getDetailsVentesSession($session);
            $sessionData['ventes_totales'] = $this->calculerVentesSession($session);
        } else {
            $sessionData['details_ventes'] = [];
            $sessionData['ventes_totales'] = 0;
        }

        return $sessionData;
    }

    /**
     * Obtenir les détails des ventes d'une session
     */
    private function getDetailsVentesSession(SessionVente $session)
    {
        $vendeurId = $session->vendeur_id;
        $categorie = $session->categorie;
        $dateDebut = Carbon::parse($session->date_ouverture);
        $dateFin = $session->date_fermeture ? Carbon::parse($session->date_fermeture) : now();

        $produits = Produit::where('categorie', $categorie)->where('actif', true)->get();
        $details = [];

        foreach ($produits as $produit) {
            $stockInitial = $this->getStockInitial($vendeurId, $produit->id, $dateDebut);
            $entrees = $this->getEntrees($vendeurId, $produit->id, $dateDebut, $dateFin);
            $retours = $this->getRetours($vendeurId, $produit->id, $dateDebut, $dateFin);
            $stockFinal = $this->getStockFinal($vendeurId, $produit->id, $dateDebut, $dateFin);
            $quantiteVendue = max(0, $stockInitial + $entrees - $retours - $stockFinal);
            $montantVendu = $quantiteVendue * $produit->prix;

            if ($quantiteVendue > 0 || $stockInitial > 0 || $entrees > 0) {
                $details[] = [
                    'produit_id' => $produit->id,
                    'produit_nom' => $produit->nom,
                    'prix_unitaire' => $produit->prix,
                    'stock_initial' => $stockInitial,
                    'entrees' => $entrees,
                    'retours' => $retours,
                    'stock_final' => $stockFinal,
                    'quantite_vendue' => $quantiteVendue,
                    'montant_vendu' => round($montantVendu, 2),
                ];
            }
        }

        return $details;
    }

    /**
     * Calculer les ventes totales d'une session
     */
    private function calculerVentesSession(SessionVente $session)
    {
        $details = $this->getDetailsVentesSession($session);
        return array_sum(array_column($details, 'montant_vendu'));
    }

    /**
     * Formater une session de vente
     */
    private function formatSessionVente(SessionVente $session)
    {
        return [
            'id' => $session->id,
            'vendeur' => [
                'id' => $session->vendeur->id,
                'nom' => $session->vendeur->name,
            ],
            'categorie' => $session->categorie,
            'fond_vente' => $session->fond_vente,
            'orange_money_initial' => $session->orange_money_initial,
            'mtn_money_initial' => $session->mtn_money_initial,
            'orange_money_final' => $session->orange_money_final,
            'mtn_money_final' => $session->mtn_money_final,
            'montant_verse' => $session->montant_verse,
            'manquant' => $session->manquant,
            'statut' => $session->statut,
            'date_ouverture' => $session->date_ouverture,
            'date_fermeture' => $session->date_fermeture,
            'fermee_par' => $session->fermeePar ? $session->fermeePar->name : null,
        ];
    }

    /**
     * Méthodes helpers pour les calculs de stock
     */
    private function getStockInitial($vendeurId, $produitId, $dateDebut)
    {
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

    private function getEntrees($vendeurId, $produitId, $dateDebut, $dateFin)
    {
        return ReceptionPointeur::where('vendeur_assigne_id', $vendeurId)
            ->where('produit_id', $produitId)
            ->whereBetween('date_reception', [$dateDebut, $dateFin])
            ->sum('quantite');
    }

    private function getRetours($vendeurId, $produitId, $dateDebut, $dateFin)
    {
        return RetourProduit::where('vendeur_id', $vendeurId)
            ->where('produit_id', $produitId)
            ->whereBetween('date_retour', [$dateDebut, $dateFin])
            ->sum('quantite');
    }

    private function getStockFinal($vendeurId, $produitId, $dateDebut, $dateFin)
    {
        $inventaire = Inventaire::where('vendeur_sortant_id', $vendeurId)
            ->where('valide_sortant', true)
            ->whereBetween('date_inventaire', [$dateDebut, $dateFin])
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
     * Obtenir le statut d'un inventaire
     */
    private function getStatutInventaire(Inventaire $inventaire)
    {
        if ($inventaire->valide_sortant && $inventaire->valide_entrant) {
            return 'Validé';
        } elseif ($inventaire->valide_sortant) {
            return 'Validé par sortant';
        } elseif ($inventaire->valide_entrant) {
            return 'Validé par entrant';
        }
        return 'En attente';
    }

    /**
     * Obtenir le résumé du flux
     */
    private function getResumeFlux($flux)
    {
        $totalVentes = 0;
        $totalProduits = 0;
        
        foreach ($flux as $vendeurFlux) {
            $totalVentes += $vendeurFlux['total_ventes'];
            $totalProduits += count($vendeurFlux['produits']);
        }

        return [
            'nombre_vendeurs' => count($flux),
            'total_ventes' => round($totalVentes, 2),
            'total_produits_traites' => $totalProduits,
        ];
    }
}