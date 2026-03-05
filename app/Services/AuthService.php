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
                    // Le client existe déjà, on le réutilise
                    $clientId = $data['client_id'];
                    
                    // Mettre à jour les informations du client
                    DB::table('clients')
                        ->where('client_id', $clientId)
                        ->update([
                            'device_info' => $data['device_info'] ?? 'Unknown device',
                            'updated_at' => now()
                        ]);
                } else {
                    // L'ID fourni n'existe pas, l'enregistrer
                    $clientId = $data['client_id'];
                    DB::table('clients')->insert([
                        'client_id' => $clientId,
                        'device_info' => $data['device_info'] ?? 'Unknown device',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } else {
                // Pas de client_id existant, en créer un nouveau
                $clientId = $this->createNewClient($data['device_info'] ?? 'Unknown device');
            }

            // 🔥 CORRECTION: Créer l'utilisateur avec le client_id dans synced_clients
            // (Lors de l'inscription, on synchronise immédiatement car c'est une création)
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
            
            \Log::info('Inscription réussie', [
                'user_id' => $user->id,
                'client_id' => $clientId
            ]);
            
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
 * Inscription depuis la vue Blade (sans client_id)
 */
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

        // Nettoyer le numéro de téléphone
        $numeroTelephone = $data['numero_telephone'];
        if (str_starts_with($numeroTelephone, '237')) {
            $numeroTelephone = substr($numeroTelephone, 3);
        }

        if (strlen($numeroTelephone) !== 9 || !ctype_digit($numeroTelephone)) {
            throw new \Exception("Le numéro doit contenir 9 chiffres");
        }

        // Créer l'utilisateur sans client_id (inscription web)
        $user = User::create([
            'name'               => $data['name'],
            'numero_telephone'   => $numeroTelephone,
            'role'               => $data['role'],
            'code_pin'           => Hash::make($data['code_pin']),
            'preferred_language' => $data['preferred_language'] ?? 'fr',
            'actif'              => true,
            'synced_clients'     => json_encode([]) // vide, pas d'app mobile
        ]);

        DB::commit();

        return $user;

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

    /**
     * 🔥 CORRECTION MAJEURE: Connexion d'un utilisateur
     * NE PAS ajouter le client_id à synced_clients lors de la connexion
     * Laisser la synchronisation normale s'en charger
     */
    public function connexion($numeroTelephone, $codePin)
    {
        // Récupérer l'utilisateur
        $user = User::where('numero_telephone', $numeroTelephone)
            ->where('actif', true)
            ->first();

        if (!$user || !Hash::check($codePin, $user->code_pin)) {
            throw new \Exception("Identifiants incorrects");
        }

        // Récupérer le client_id envoyé dans le header
        $clientIdFromHeader = request()->header('X-Client-ID');
        
        \Log::info('Connexion - Client ID reçu', [
            'user_id' => $user->id,
            'client_id_header' => $clientIdFromHeader
        ]);

        $clientId = null;

        if ($clientIdFromHeader) {
            // Vérifier si ce client existe
            $client = DB::table('clients')
                ->where('client_id', $clientIdFromHeader)
                ->first();
            
            if ($client) {
                // Client existant - mettre à jour device_info si nécessaire
                $deviceInfo = request()->userAgent() ?? 'Unknown device';
                if ($client->device_info !== $deviceInfo) {
                    DB::table('clients')
                        ->where('client_id', $clientIdFromHeader)
                        ->update([
                            'device_info' => $deviceInfo,
                            'updated_at' => now()
                        ]);
                }
                
                \Log::info('Client existant trouvé', [
                    'client_id' => $clientIdFromHeader,
                    'user_id' => $user->id
                ]);
                
                $clientId = $clientIdFromHeader;
            } else {
                // Nouveau client, l'enregistrer
                DB::table('clients')->insert([
                    'client_id' => $clientIdFromHeader,
                    'device_info' => request()->userAgent() ?? 'Unknown device',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                \Log::info('Nouveau client enregistré lors de la connexion', [
                    'user_id' => $user->id,
                    'client_id' => $clientIdFromHeader
                ]);
                
                $clientId = $clientIdFromHeader;
            }
            
            // 🔥 CORRECTION: NE PAS ajouter le client_id à synced_clients ici
            // La synchronisation normale (pull) s'en chargera automatiquement
            // Cela évite le bug où l'utilisateur n'est jamais synchronisé sur un nouvel appareil
            
            \Log::info('Client enregistré, synchronisation différée au pull', [
                'user_id' => $user->id,
                'client_id' => $clientId
            ]);
            
        } else {
            // Pas de client_id fourni
            $syncedClients = json_decode($user->synced_clients ?? '[]', true);
            
            if (!empty($syncedClients)) {
                // Utiliser le premier client synchronisé
                $clientId = $syncedClients[0];
                
                \Log::info('Aucun client_id fourni - utilisation du premier client synchronisé', [
                    'user_id' => $user->id,
                    'client_id' => $clientId
                ]);
            } else {
                // Aucun client trouvé, en créer un nouveau
                $clientId = $this->createNewClient(
                    request()->userAgent() ?? 'Unknown device'
                );
                
                // Pour un nouveau client sans historique, on peut l'ajouter à synced_clients
                // car c'est une première connexion absolue
                $user->synced_clients = json_encode([$clientId]);
                $user->save();
                
                \Log::info('Aucun client trouvé - création nouveau client', [
                    'user_id' => $user->id,
                    'client_id' => $clientId
                ]);
            }
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
            'client_id' => $clientId,
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