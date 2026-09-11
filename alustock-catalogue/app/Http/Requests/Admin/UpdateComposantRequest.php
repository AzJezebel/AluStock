<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComposantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Récupérer l'ID du composant en cours d'édition
        // Cela permet d'ignorer cet ID dans la règle "unique"
        $composantId = $this->route('composant')->id;

        return [
            // ============================================================
            // IDENTIFICATION
            // ============================================================
            'reference' => 'required|string|max:50|unique:composants,reference,' . $composantId,
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

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            // Identification
            'reference.required' => 'La référence est obligatoire.',
            'reference.unique' => 'Cette référence existe déjà.',
            'reference.max' => 'La référence ne doit pas dépasser 50 caractères.',
            'designation.required' => 'La désignation est obligatoire.',
            'designation.max' => 'La désignation ne doit pas dépasser 200 caractères.',

            // Classification
            'type_composant_id.exists' => 'Le type de composant sélectionné n\'existe pas.',
            'gamme_id.exists' => 'La gamme sélectionnée n\'existe pas.',

            // Dimensions
            'longueur_barre_mm.integer' => 'La longueur de barre doit être un nombre entier.',
            'longueur_barre_mm.min' => 'La longueur de barre ne peut pas être négative.',
            'section_largeur_mm.numeric' => 'La largeur de section doit être un nombre.',
            'section_hauteur_mm.numeric' => 'La hauteur de section doit être un nombre.',
            'epaisseur_paroi_mm.numeric' => 'L\'épaisseur de paroi doit être un nombre.',

            // Poids
            'poids_lineaire_kg_m.numeric' => 'Le poids en KG/M doit être un nombre.',
            'poids_lineaire_lbs_ft.numeric' => 'Le poids en WT/FT doit être un nombre.',

            // Inertie & Périmètre
            'moment_inertie_cm4.numeric' => 'Le moment d\'inertie doit être un nombre.',
            'perimetre_mm.numeric' => 'Le périmètre doit être un nombre.',

            // Médias
            'medias_fichiers.array' => 'Les fichiers doivent être un tableau.',
            'medias_fichiers.max' => 'Maximum 10 fichiers à la fois.',
            'medias_fichiers.*.file' => 'Chaque élément doit être un fichier.',
            'medias_fichiers.*.mimes' => 'Formats acceptés : PNG, JPG, JPEG.',
            'medias_fichiers.*.max' => 'Chaque fichier ne doit pas dépasser 5 Mo.',
            'medias_type_media.in' => 'Le type de média est invalide.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convertir les champs vides en null (utile pour les champs numériques)
        $fields = [
            'longueur_barre_mm',
            'section_largeur_mm',
            'section_hauteur_mm',
            'epaisseur_paroi_mm',
            'poids_lineaire_kg_m',
            'poids_lineaire_lbs_ft',
            'moment_inertie_cm4',
            'perimetre_mm',
            'type_composant_id',
            'gamme_id',
        ];

        $data = [];
        foreach ($fields as $field) {
            if ($this->has($field) && $this->input($field) === '') {
                $data[$field] = null;
            }
        }

        // S'assurer que est_disponible est un booléen
        $data['est_disponible'] = $this->has('est_disponible');

        $this->merge($data);
    }
}