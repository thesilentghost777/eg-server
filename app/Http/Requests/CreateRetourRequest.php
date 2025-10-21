<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRetourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'raison' => 'required|in:gate,perime,defectueux,autre',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'produit_id.required' => 'Le produit est obligatoire',
            'quantite.required' => 'La quantité est obligatoire',
            'raison.required' => 'La raison du retour est obligatoire',
        ];
    }
}
