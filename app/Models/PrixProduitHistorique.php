<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrixProduitHistorique extends Model
{
    protected $table = 'prix_produits_historique';

    protected $fillable = ['produit_id', 'prix', 'date_debut', 'date_fin'];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
        'prix'       => 'decimal:2',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Récupère le prix d'un produit à une date donnée
     */
    public static function getPrixAt(int $produitId, $date): ?float
    {
        $row = static::where('produit_id', $produitId)
            ->where('date_debut', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('date_fin')
                  ->orWhere('date_fin', '>=', $date);
            })
            ->orderByDesc('date_debut')
            ->first();

        return $row ? (float) $row->prix : null;
    }
}