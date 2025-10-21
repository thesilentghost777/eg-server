<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProduitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'categorie' => 'required|in:boulangerie,patisserie',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du produit est obligatoire',
            'prix.required' => 'Le prix est obligatoire',
            'prix.numeric' => 'Le prix doit être un nombre',
            'categorie.required' => 'La catégorie est obligatoire',
            'categorie.in' => 'La catégorie doit être boulangerie ou patisserie',
        ];
    }
}
