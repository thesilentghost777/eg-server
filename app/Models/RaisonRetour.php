<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaisonRetour extends Model
{
    protected $table = 'raisons_retour';

    protected $fillable = [
        'code',
        'libelle',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}