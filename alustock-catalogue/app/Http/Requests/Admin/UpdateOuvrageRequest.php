<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOuvrageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ouvrageId = $this->route('ouvrage')->id;

        return [
            'reference' => 'required|string|max:50|unique:ouvrages,reference,' . $ouvrageId,
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
        ];
    }
}