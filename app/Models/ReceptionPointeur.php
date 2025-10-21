<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceptionPointeur extends Model
{
    use HasFactory;

    protected $table = 'receptions_pointeur';

    protected $fillable = [
        'pointeur_id',
        'producteur_id',
        'produit_id',
        'quantite',
        'vendeur_assigne_id',
        'verrou',
        'date_reception',
        'notes',
    ];

    protected $casts = [
        'date_reception' => 'datetime',
        'verrou' => 'boolean',
    ];

    public function pointeur()
    {
        return $this->belongsTo(User::class, 'pointeur_id');
    }

    public function producteur()
    {
        return $this->belongsTo(User::class, 'producteur_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function vendeurAssigne()
    {
        return $this->belongsTo(User::class, 'vendeur_assigne_id');
    }

    public function scopeNonVerrouille($query)
    {
        return $query->where('verrou', false);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date_reception', $date);
    }
}
