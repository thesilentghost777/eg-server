<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\VendeurActif;

class AuthService
{
    public function inscription(array $data)
    {
        DB::beginTransaction();
        
        try {
            // Vérifier le code PDG si nécessaire
            if ($data['role'] === 'pdg') {
                $configPdg = DB::table('config_pdg')->first();
                
                if (!$configPdg || $data['code_pdg'] !== $configPdg->code_inscription_pdg) {
                    throw new \Exception("Code PDG incorrect");
                }
            }
            
            $user = User::create([
                'name' => $data['nom'],
                'numero_telephone' => $data['numero_telephone'],
                'role' => $data['role'],
                'code_pin' => $data['code_pin'],
                'preferred_language' => $data['preferred_language'] ?? 'fr',
                'actif' => true,
            ]);
            
            DB::commit();
            return $user;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function connexion($numeroTelephone, $codePin)
    {
        $user = User::where('numero_telephone', $numeroTelephone)
            ->where('actif', true)
            ->first();
        
        if (!$user || $user->code_pin !== $codePin) {
            throw new \Exception("Identifiants incorrects");
        }
        
        if($user->role === 'vendeur_boulangerie' || $user->role === 'vendeur_patisserie') {
             // Gérer les vendeurs actifs
            $categorie = $user->role === 'vendeur_boulangerie' ? 'boulangerie' :
                     ($user->role === 'vendeur_patisserie' ? 'patisserie' : null);
            VendeurActif::setVendeurActif(
                    $categorie,
                    $user->id
                );

        }
       
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function deconnexion($user)
    {
        $user->tokens()->delete();
        return true;
    }
}
