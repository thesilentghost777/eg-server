<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour l'ouverture d'une session
 */
class OuvrirSessionRequest extends FormRequest
{
    public function authorize()
    {
        // Seuls les vendeurs peuvent ouvrir une session
        return $this->user() && $this->user()->role === 'vendeur';
    }

    public function rules()
    {
        return [
            'categorie' => 'required|in:boulangerie,patisserie',
            'fond_vente' => 'required|numeric|min:0',
            'orange_money_initial' => 'nullable|numeric|min:0',
            'mtn_money_initial' => 'nullable|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'categorie.required' => 'La catégorie est requise',
            'categorie.in' => 'La catégorie doit être boulangerie ou patisserie',
            'fond_vente.required' => 'Le fond de vente est requis',
            'fond_vente.numeric' => 'Le fond de vente doit être un nombre',
            'fond_vente.min' => 'Le fond de vente ne peut pas être négatif',
            'orange_money_initial.numeric' => 'Le montant Orange Money doit être un nombre',
            'orange_money_initial.min' => 'Le montant Orange Money ne peut pas être négatif',
            'mtn_money_initial.numeric' => 'Le montant MTN Money doit être un nombre',
            'mtn_money_initial.min' => 'Le montant MTN Money ne peut pas être négatif',
        ];
    }
}

/**
 * Validation pour la fermeture d'une session
 */
class FermerSessionRequest extends FormRequest
{
    public function authorize()
    {
        // Seul le PDG peut fermer une session
        return $this->user() && $this->user()->role === 'pdg';
    }

    public function rules()
    {
        return [
            'montant_verse' => 'required|numeric|min:0',
            'orange_money_final' => 'required|numeric|min:0',
            'mtn_money_final' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'montant_verse.required' => 'Le montant versé est requis',
            'montant_verse.numeric' => 'Le montant versé doit être un nombre',
            'montant_verse.min' => 'Le montant versé ne peut pas être négatif',
            'orange_money_final.required' => 'Le montant final Orange Money est requis',
            'orange_money_final.numeric' => 'Le montant final Orange Money doit être un nombre',
            'orange_money_final.min' => 'Le montant final Orange Money ne peut pas être négatif',
            'mtn_money_final.required' => 'Le montant final MTN Money est requis',
            'mtn_money_final.numeric' => 'Le montant final MTN Money doit être un nombre',
            'mtn_money_final.min' => 'Le montant final MTN Money ne peut pas être négatif',
        ];
    }
}