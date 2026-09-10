<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'composant_id' => 'required|exists:composants,id',
            'quantite' => 'required|numeric|min:0.01',
            'unite' => 'required|string|max:20',
            'ordre' => 'nullable|integer|min:0',
            'longueur_coupe_mm' => 'nullable|integer|min:0',
            'commentaire' => 'nullable|string',
        ];
    }
}