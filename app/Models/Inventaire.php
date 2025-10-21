<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendeur_sortant_id',
        'vendeur_entrant_id',
        'categorie',
        'valide_sortant',
        'valide_entrant',
        'date_inventaire',
    ];

    protected $casts = [
        'valide_sortant' => 'boolean',
        'valide_entrant' => 'boolean',
        'date_inventaire' => 'datetime',
    ];

    public function vendeurSortant()
    {
        return $this->belongsTo(User::class, 'vendeur_sortant_id');
    }

    public function vendeurEntrant()
    {
        return $this->belongsTo(User::class, 'vendeur_entrant_id');
    }

    public function details()
    {
        return $this->hasMany(InventaireDetail::class);
    }

    public function isComplete()
    {
        return $this->valide_sortant && $this->valide_entrant;
    }
}
