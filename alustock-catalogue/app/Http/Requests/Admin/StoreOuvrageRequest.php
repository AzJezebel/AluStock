<?php

// app/Http/Requests/Admin/StoreOuvrageRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOuvrageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Informations de l'ouvrage
            'reference' => 'required|string|max:50|unique:ouvrages,reference',
            'nom' => 'required|string|max:200',
            'gamme_id' => 'nullable|exists:gammes,id',
            'categorie_id' => 'nullable|exists:categories,id',
            'description_courte' => 'nullable|string',
            'description_technique' => 'nullable|string',
            'largeur_min_mm' => 'nullable|integer|min:0',
            'largeur_max_mm' => 'nullable|integer|min:0|gte:largeur_min_mm',
            'hauteur_min_mm' => 'nullable|integer|min:0',
            'hauteur_max_mm' => 'nullable|integer|min:0|gte:hauteur_min_mm',
            'performance_thermique' => 'nullable|string|max:50',
            'performance_acoustique' => 'nullable|string|max:50',
            'est_actif' => 'boolean',

            'temp_composition' => 'nullable|string',
            'temp_caracteristiques' => 'nullable|string',

            // Médias (optionnel)
            'medias' => 'nullable|array',
            'medias.fichiers' => 'nullable|array|max:10',
            'medias.fichiers.*' => 'file|mimes:png,jpg,jpeg|max:5120',
            'medias.type_media' => 'nullable|in:schema,photo,rendu_3d',
        ];
    }

    public function messages(): array
    {
        return [
            'reference.required' => 'La référence est obligatoire.',
            'reference.unique' => 'Cette référence existe déjà.',
            'nom.required' => 'Le nom est obligatoire.',
            'largeur_max_mm.gte' => 'La largeur max doit être supérieure ou égale à la largeur min.',
            'hauteur_max_mm.gte' => 'La hauteur max doit être supérieure ou égale à la hauteur min.',
            'medias.fichiers.*.mimes' => 'Formats acceptés : PNG, JPG, JPEG.',
            'medias.fichiers.*.max' => 'Chaque fichier ne doit pas dépasser 5 Mo.',
        ];
    }
}