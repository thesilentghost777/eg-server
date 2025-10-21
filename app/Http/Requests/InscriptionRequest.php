<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'numero_telephone' => 'required|string|unique:users,numero_telephone|max:20',
            'role' => 'required|in:pdg,pointeur,vendeur_boulangerie,vendeur_patisserie,producteur',
            'code_pin' => 'required|string|size:6',
            'preferred_language' => 'nullable|in:fr,en',
        ];

        // Si le rôle est PDG, exiger le code PDG
        if ($this->input('role') === 'pdg') {
            $rules['code_pdg'] = 'required|string';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire',
            'numero_telephone.required' => 'Le numéro de téléphone est obligatoire',
            'numero_telephone.unique' => 'Ce numéro de téléphone est déjà utilisé',
            'code_pin.required' => 'Le code PIN est obligatoire',
            'code_pin.size' => 'Le code PIN doit contenir exactement 6 caractères',
            'role.required' => 'Le rôle est obligatoire',
            'code_pdg.required' => 'Le code PDG est obligatoire pour ce rôle',
        ];
    }
}
