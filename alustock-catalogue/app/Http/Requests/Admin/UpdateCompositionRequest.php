<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Le composant est déjà dans l'URL : seules les données de la ligne se modifient.
    public function rules(): array
    {
        return [
            'quantite' => 'required|numeric|min:0.01',
            'unite' => 'required|string|max:20',
            'ordre' => 'nullable|integer|min:0',
            'commentaire' => 'nullable|string',
        ];
    }
}
