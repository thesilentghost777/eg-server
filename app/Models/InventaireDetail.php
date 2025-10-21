<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventaireDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventaire_id',
        'produit_id',
        'quantite_restante',
    ];

    public function inventaire()
    {
        return $this->belongsTo(Inventaire::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
