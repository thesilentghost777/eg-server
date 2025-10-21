<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionVente extends Model
{
    use HasFactory;

    protected $table = 'sessions_vente';

    protected $fillable = [
        'vendeur_id',
        'categorie',
        'fond_vente',
        'orange_money_initial',
        'mtn_money_initial',
        'montant_verse',
        'orange_money_final',
        'mtn_money_final',
        'manquant',
        'statut',
        'fermee_par',
        'date_ouverture',
        'date_fermeture',
    ];

    protected $casts = [
        'fond_vente' => 'decimal:2',
        'orange_money_initial' => 'decimal:2',
        'mtn_money_initial' => 'decimal:2',
        'montant_verse' => 'decimal:2',
        'orange_money_final' => 'decimal:2',
        'mtn_money_final' => 'decimal:2',
        'manquant' => 'decimal:2',
        'date_ouverture' => 'datetime',
        'date_fermeture' => 'datetime',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function fermeePar()
    {
        return $this->belongsTo(User::class, 'fermee_par');
    }

    public function scopeOuverte($query)
    {
        return $query->where('statut', 'ouverte');
    }

    public function scopeFermee($query)
    {
        return $query->where('statut', 'fermee');
    }
}
