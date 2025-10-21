<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetourProduit extends Model
{
    use HasFactory;

    //specifier le nom de la table si différent de la convention
    protected $table = 'retours_produits';
    protected $fillable = [
        'pointeur_id',
        'vendeur_id',
        'produit_id',
        'quantite',
        'raison',
        'description',
        'verrou',
        'date_retour',
    ];

    protected $casts = [
        'date_retour' => 'datetime',
        'verrou' => 'boolean',
    ];

    public function pointeur()
    {
        return $this->belongsTo(User::class, 'pointeur_id');
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function scopeNonVerrouille($query)
    {
        return $query->where('verrou', false);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date_retour', $date);
    }
}
