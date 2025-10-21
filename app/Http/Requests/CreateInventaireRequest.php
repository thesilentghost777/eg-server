<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInventaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendeur_entrant_id' => 'required|exists:users,id',
            'produits' => 'required|array|min:1',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite_restante' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'vendeur_entrant_id.required' => 'Le vendeur entrant est obligatoire',
            'produits.required' => 'Au moins un produit est requis',
            'produits.*.produit_id.required' => 'Le produit est obligatoire',
            'produits.*.quantite_restante.required' => 'La quantité est obligatoire',
        ];
    }
}
