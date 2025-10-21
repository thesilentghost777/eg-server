<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendeurActif extends Model
{
    use HasFactory;

    protected $table = 'vendeurs_actifs';

    protected $fillable = [
        'categorie',
        'vendeur_id',
        'connecte_a',
    ];

    protected $casts = [
        'connecte_a' => 'datetime',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public static function getVendeurActif($categorie)
    {
        return self::where('categorie', $categorie)->first()?->vendeur_id;
    }

    public static function setVendeurActif($categorie, $vendeurId)
    {
        return self::updateOrCreate(
            ['categorie' => $categorie],
            [
                'vendeur_id' => $vendeurId,
                'connecte_a' => now()
            ]
        );
    }
}
