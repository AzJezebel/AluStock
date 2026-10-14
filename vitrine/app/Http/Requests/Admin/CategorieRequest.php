<?php
// app/Http/Requests/Admin/CategorieRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategorieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // l'accès est déjà protégé par les middleware auth + admin
    }

    public function rules(): array
    {
        return [
            'nom' => [
                'required', 'string', 'max:255',
                Rule::unique('categories', 'nom')->ignore($this->route('categorie')),
            ],
            'description' => ['nullable', 'string'],
            'icone'       => ['nullable', 'string', 'max:100'],
            'ordre'       => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.unique'   => 'Cette catégorie existe déjà.',
        ];
    }
}