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

            // ✅ Gérer le client_id (ADAPTÉ À LA STRUCTURE DE LA TABLE)
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

            // Créer l'utilisateur avec le client_id dans synced_clients
            $user = User::create([
                'name' => $data['name'],
                'numero_telephone' => $numeroTelephone,
                'role' => $data['role'],
                'code_pin' => Hash::make($data['code_pin']),
                'preferred_language' => $data['preferred_language'] ?? 'fr',
                'actif' => true,
                'synced_clients' => json_encode([$clientId]) // Le client est synchronisé avec cet utilisateur
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
     * Connexion d'un utilisateur - Gère les nouveaux appareils
     * ✅ ADAPTÉ À LA STRUCTURE DE LA TABLE (pas de user_id ni last_seen_at)
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

        // ✅ Récupérer le client_id envoyé dans le header
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
            
            // Ajouter le client_id à synced_clients de l'utilisateur si pas déjà présent
            $syncedClients = json_decode($user->synced_clients ?? '[]', true);
            if (!in_array($clientId, $syncedClients)) {
                $syncedClients[] = $clientId;
                $user->synced_clients = json_encode($syncedClients);
                $user->save();
                
                \Log::info('Client ajouté à synced_clients', [
                    'user_id' => $user->id,
                    'client_id' => $clientId,
                    'total_clients' => count($syncedClients)
                ]);
            }
        } else {
            // ⚠️ Pas de client_id fourni (ne devrait pas arriver avec la nouvelle logique React)
            // Récupérer le premier client de synced_clients ou en créer un nouveau
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
                
                // L'ajouter à synced_clients
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
            'client_id' => $clientId, // ✅ Renvoyer le client_id
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