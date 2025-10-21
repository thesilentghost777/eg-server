<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'producteur_id' => 'required|exists:users,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'producteur_id.required' => 'Le producteur est obligatoire',
            'produit_id.required' => 'Le produit est obligatoire',
            'quantite.required' => 'La quantité est obligatoire',
            'quantite.min' => 'La quantité doit être au moins 1',
        ];
    }
}
