<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreComposantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => 'required|string|max:50|unique:composants,reference',
            'designation' => 'required|string|max:200',
            'type_composant_id' => 'nullable|exists:types_composant,id',
            'gamme_id' => 'nullable|exists:gammes,id',
            'matiere' => 'nullable|string|max:100',
            
            // Champs techniques (uniquement pertinents pour les profilés)
            'longueur_barre_mm' => 'nullable|integer|min:0',
            'poids_lineaire_kg_m' => 'nullable|numeric|min:0',
            'poids_lineaire_lbs_ft' => 'nullable|numeric|min:0',
            'section_largeur_mm' => 'nullable|numeric|min:0',
            'section_hauteur_mm' => 'nullable|numeric|min:0',
            'epaisseur_paroi_mm' => 'nullable|numeric|min:0',
            'moment_inertie_x_cm4' => 'nullable|numeric|min:0',
            'moment_inertie_y_cm4' => 'nullable|numeric|min:0',
            'moment_inertie_in4' => 'nullable|numeric|min:0',
            'module_elasticite_x_cm3' => 'nullable|numeric|min:0',
            'module_elasticite_y_cm3' => 'nullable|numeric|min:0',
            'perimetre_mm' => 'nullable|numeric|min:0',
            'perimetre_in' => 'nullable|numeric|min:0',
            
            'est_disponible' => 'boolean',
        ];
    }
}