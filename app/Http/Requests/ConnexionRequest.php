<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConnexionRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la connexion.
     */
    public function rules(): array
    {
        return [
            'numero_telephone' => 'required|string|max:20|exists:users,numero_telephone',
            'code_pin' => 'required|string|size:6',
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     */
    public function messages(): array
    {
        return [
            'numero_telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'numero_telephone.exists' => 'Aucun compte ne correspond à ce numéro de téléphone.',
            'code_pin.required' => 'Le code PIN est obligatoire.',
            'code_pin.size' => 'Le code PIN doit contenir exactement 6 caractères.',
        ];
    }
}
