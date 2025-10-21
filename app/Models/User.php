<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Les attributs assignables en masse.
     */
    protected $fillable = [
        'name',
        'role',
        'code_pin',
        'numero_telephone',
        'actif',
        'preferred_language',
    ];

    /**
     * Les attributs à cacher dans les tableaux ou JSON.
     */
    protected $hidden = [
        'code_pin',
        'remember_token',
    ];

    /**
     * Les attributs à caster automatiquement.
     */
    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Vérifie si l'utilisateur est actif.
     */
    public function isActif(): bool
    {
        return $this->actif;
    }

    /**
     * Vérifie le rôle de l'utilisateur.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Définir les rôles possibles pour référence.
     */
    public static function roles(): array
    {
        return [
            'pdg',
            'pointeur',
            'vendeur_boulangerie',
            'vendeur_patisserie',
            'producteur',
        ];
    }


}