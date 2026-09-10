<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCaracteristiqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cle' => 'required|string|max:100',
            'valeur' => 'required|string',
            'unite' => 'nullable|string|max:20',
            'ordre_affichage' => 'nullable|integer|min:0',
        ];
    }
}