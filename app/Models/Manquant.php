<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manquant extends Model
{
    protected $table = 'manquants';

    protected $fillable = [
        'vendeur_id',
        'date_manquant',
        'total_ventes',
        'fond_caisse',
        'versement_1',
        'versement_2',
        'versement_3',
        'versement_extra',
        'om_final',
        'momo_final',
        'total_verse',
        'montant_manquant',
        'notes',
        'valide_par',
    ];

    protected $casts = [
        'date_manquant'   => 'date',
        'total_ventes'    => 'decimal:2',
        'fond_caisse'     => 'decimal:2',
        'versement_1'     => 'decimal:2',
        'versement_2'     => 'decimal:2',
        'versement_3'     => 'decimal:2',
        'versement_extra' => 'decimal:2',
        'om_final'        => 'decimal:2',
        'momo_final'      => 'decimal:2',
        'total_verse'     => 'decimal:2',
        'montant_manquant'=> 'decimal:2',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    /**
     * Est-ce un vrai manquant (positif) ou un excédent (négatif) ?
     */
    public function getTypeAttribute(): string
    {
        if ($this->montant_manquant > 0.5)  return 'manquant';
        if ($this->montant_manquant < -0.5) return 'excedent';
        return 'exact';
    }
}