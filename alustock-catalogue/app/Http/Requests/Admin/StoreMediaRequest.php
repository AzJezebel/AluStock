<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fichiers' => 'required|array',
            'fichiers.*' => 'file|mimes:png,jpg,jpeg,webp,svg|max:5120',
            'type_media' => 'required|in:image,rendu_3d,plan',
        ];
    }

    public function messages(): array
    {
        return [
            'fichiers.required' => 'Veuillez sélectionner au moins un fichier.',
            'fichiers.*.mimes' => 'Les fichiers doivent être des images (png, jpg, jpeg, webp, svg).',
            'fichiers.*.max' => 'Les fichiers ne doivent pas dépasser 5 Mo.',
        ];
    }
}