<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\VendeurActif;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Inscription d'un nouvel utilisateur
     */
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

            // Nettoyer le numéro de téléphone
            $numeroTelephone = $data['numero_telephone'];

            if (str_starts_with($numeroTelephone, '237')) {
                $numeroTelephone = substr($numeroTelephone, 3);
            } elseif (str_starts_with($numeroTelephone, '6')) {
                $numeroTelephone = $numeroTelephone;
            } else {
                throw new \Exception("Format de numéro de téléphone invalide");
            }

            if (strlen($numeroTelephone) !== 9 || !ctype_digit($numeroTelephone)) {
                throw new \Exception("Le numéro doit contenir 9 chiffres après le préfixe");
            }

            // Gérer le client_id
            $clientId = null;
            $hasClientId = $data['has_client_id'] ?? false;

            if ($hasClientId && !empty($data['client_id'])) {
                // Le client a déjà un ID, vérifier s'il existe
                $existingClient = DB::table('clients')->where('client_id', $data['client_id'])->first();
                if ($existingClient) {
                    $clientId = $data['client_id'];
                } else {
                    // L'ID fourni n'existe pas, en créer un nouveau
                    $clientId = $this->createNewClient($data['device_info'] ?? 'Unknown device');
                }
            } else {
                // Pas de client_id existant, en créer un nouveau
                $clientId = $this->createNewClient($data['device_info'] ?? 'Unknown device');
            }

            // Créer l'utilisateur
            $user = User::create([
                'name' => $data['name'],
                'numero_telephone' => $numeroTelephone,
                'role' => $data['role'],
                'code_pin' => Hash::make($data['code_pin']),
                'preferred_language' => $data['preferred_language'] ?? 'fr',
                'actif' => true,
                'synced_clients' => json_encode([$clientId])
            ]);

            DB::commit();
            
            return [
                'user' => $user,
                'client_id' => $clientId
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Créer un nouveau client
     */
    private function createNewClient(string $deviceInfo): string
    {
        $clientId = (string) Str::uuid();
        
        DB::table('clients')->insert([
            'client_id' => $clientId,
            'device_info' => $deviceInfo,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $clientId;
    }

     public function inscription2(array $data)
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

        // Nettoyer le numéro de téléphone (garder uniquement 6xxxxxxxx)
        $numeroTelephone = $data['numero_telephone'];

        // Si le numéro commence par 237, on enlève les 3 premiers chiffres (237)
        if (str_starts_with($numeroTelephone, '237')) {
            $numeroTelephone = substr($numeroTelephone, 3);
        }
        // Si le numéro commence par 6, on le garde tel quel
        elseif (str_starts_with($numeroTelephone, '6')) {
            $numeroTelephone = $numeroTelephone;
        }
        // Sinon, erreur de format
        else {
            throw new \Exception("Format de numéro de téléphone invalide");
        }

        // Vérifier que le numéro restant a 9 chiffres
        if (strlen($numeroTelephone) !== 9 || !ctype_digit($numeroTelephone)) {
            throw new \Exception("Le numéro doit contenir 9 chiffres après le préfixe");
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $data['name'],
            'numero_telephone' => $numeroTelephone, // Stocke uniquement 6xxxxxxxx
            'role' => $data['role'],
            'code_pin' => Hash::make($data['code_pin']), // ✅ Hashé
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


    /**
     * Connexion d'un utilisateur
     */
   public function connexion($numeroTelephone, $codePin)
{
    $user = User::where('numero_telephone', $numeroTelephone)
        ->where('actif', true)
        ->first();

    if (!$user || !Hash::check($codePin, $user->code_pin)) {
        throw new \Exception("Identifiants incorrects");
    }

    // Gérer les vendeurs actifs
    if (in_array($user->role, ['vendeur_boulangerie', 'vendeur_patisserie'])) {
        $categorie = $user->role === 'vendeur_boulangerie' ? 'boulangerie' : 'patisserie';
        VendeurActif::setVendeurActif($categorie, $user->id);
    }

    // Générer le token
    $token = $user->createToken('auth_token')->plainTextToken;

    return [
        'user' => $user,
        'token' => $token,
    ];
}

    /**
     * Déconnexion d'un utilisateur
     */
    public function deconnexion($user)
    {
        // Supprimer tous les tokens de l'utilisateur
        $user->tokens()->delete();
        
        return true;
    }
}