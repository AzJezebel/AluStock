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
            'reference' => 'required|string|max:50',
            'designation' => 'required|string|max:200',
            
            // ============================================================
            // CLASSIFICATION
            // ============================================================
            'type_composant_id' => 'nullable|exists:types_composant,id',
            'gamme_id' => 'nullable|exists:gammes,id',
            'matiere' => 'nullable|string|max:100',

            // ============================================================
            // DIMENSIONS (profilés)
            // ============================================================
            'longueur_barre_mm' => 'nullable|integer|min:0',
            'section_largeur_mm' => 'nullable|numeric|min:0',
            'section_hauteur_mm' => 'nullable|numeric|min:0',
            'epaisseur_paroi_mm' => 'nullable|numeric|min:0',

            // ============================================================
            // POIDS LINÉAIRE
            // ============================================================
            'poids_lineaire_kg_m' => 'nullable|numeric|min:0',
            'poids_lineaire_lbs_ft' => 'nullable|numeric|min:0',

            // ============================================================
            // INERTIE & PÉRIMÈTRE
            // ============================================================
            'moment_inertie_cm4' => 'nullable|numeric|min:0',
            'perimetre_mm' => 'nullable|numeric|min:0',

            // ============================================================
            // STATUT
            // ============================================================
            'est_disponible' => 'boolean',

            // ============================================================
            // MÉDIAS (optionnel — peut être géré séparément)
            // ============================================================
            'medias_fichiers' => 'nullable|array|max:10',
            'medias_fichiers.*' => 'file|mimes:png,jpg,jpeg|max:5120',
            'medias_type_media' => 'nullable|in:schema,photo,rendu_3d',
        ];
    }
}