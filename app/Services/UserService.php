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

    if (array_key_exists('nom', $data)) {
        $updateData['name'] = $data['nom'];
    }

    if (array_key_exists('numero_telephone', $data)) {
        $updateData['numero_telephone'] = $data['numero_telephone'];
    }

    // ✅ AJOUTEZ CECI
    if (array_key_exists('role', $data)) {
        $updateData['role'] = $data['role'];
    }

    if (!empty($data['code_pin'])) {
        $updateData['code_pin'] = Hash::make($data['code_pin']);
    }

    if (array_key_exists('actif', $data)) {
        $updateData['actif'] = filter_var($data['actif'], FILTER_VALIDATE_BOOLEAN);
    }

    if (array_key_exists('preferred_language', $data)) {
        $updateData['preferred_language'] = $data['preferred_language'];
    }

    if (!empty($updateData)) {
        $user->update($updateData);
    }

    return $user->fresh();
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