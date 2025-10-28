<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers()
    {
        return User::orderBy('name')->get();
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function getUsersByRole($role)
    {
        return User::where('role', $role)
            ->where('actif', true)
            ->orderBy('name')
            ->get();
    }

    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['nom'],
            'numero_telephone' => $data['numero_telephone'],
            'role' => $data['role'],
            'code_pin' => Hash::make($data['code_pin']),
            'preferred_language' => $data['preferred_language'] ?? 'fr',
            'actif' => $data['actif'] ?? true,
        ]);
    }

    public function updateUser($id, array $data)
    {
        $user = User::findOrFail($id);
        $updateData = [];
        
        if (isset($data['nom'])) {
            $updateData['name'] = $data['nom'];
        }
        if (isset($data['numero_telephone'])) {
            $updateData['numero_telephone'] = $data['numero_telephone'];
        }
        if (isset($data['code_pin'])) {
            $updateData['code_pin'] = $data['code_pin'];
        }
        if (isset($data['preferred_language'])) {
            $updateData['preferred_language'] = $data['preferred_language'];
        }
        
        $user->update($updateData);
        return $user;
    }

    public function toggleActif($id)
    {
        $user = User::findOrFail($id);
        $user->actif = !$user->actif;
        $user->save();
        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return true;
    }

    public function getProducteurs()
    {
        return $this->getUsersByRole('producteur');
    }

    public function getPointeurs()
    {
        return $this->getUsersByRole('pointeur');
    }

    public function getVendeursBoulangerie()
    {
        return $this->getUsersByRole('vendeur_boulangerie');
    }

    public function getVendeursPatisserie()
    {
        return $this->getUsersByRole('vendeur_patisserie');
    }
}