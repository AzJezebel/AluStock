<?php

// app/Http/Requests/Admin/StoreMediaRequest.php

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
            'fichiers' => 'required|array|max:10', // Max 10 fichiers à la fois
            'fichiers.*' => 'file|mimes:png,jpg,jpeg|max:5120', // 5 Mo max
            'type_media' => 'nullable|in:schema,photo,rendu_3d',
            'titre' => 'nullable|string|max:200',
            'alt_text' => 'nullable|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'fichiers.required' => 'Veuillez sélectionner au moins un fichier.',
            'fichiers.max' => 'Maximum 10 fichiers à la fois.',
            'fichiers.*.mimes' => 'Formats acceptés : PNG, JPG, JPEG.',
            'fichiers.*.max' => 'Chaque fichier ne doit pas dépasser 5 Mo.',
        ];
    }
}