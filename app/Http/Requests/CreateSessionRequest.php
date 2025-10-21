<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fond_vente' => 'nullable|numeric|min:0',
            'orange_money_initial' => 'nullable|numeric|min:0',
            'mtn_money_initial' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'fond_vente.numeric' => 'Le fond de vente doit être un nombre',
            'orange_money_initial.numeric' => 'Le montant Orange Money doit être un nombre',
            'mtn_money_initial.numeric' => 'Le montant MTN Money doit être un nombre',
        ];
    }
}
