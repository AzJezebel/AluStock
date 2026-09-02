<?php
// app/Http/Requests/Admin/OuvrageRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OuvrageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Retourner true pour autoriser l'accès
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('ouvrage') ? $this->route('ouvrage')->id : null;

        return [
            'titre' => 'required|string|max:255',
            'reference' => 'required|string|max:255|unique:ouvrages,reference,' . $id,
            'description' => 'nullable|string',
            'categorie_id' => 'nullable|exists:categories,id',
            'gamme_id' => 'nullable|exists:gammes,id',
            'date_realisation' => 'nullable|date',
            'client' => 'nullable|string|max:255',
            'localisation' => 'nullable|string|max:255',
            'specifications' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'main_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'reference.required' => 'La référence est obligatoire.',
            'reference.unique' => 'Cette référence existe déjà.',
            'main_image.image' => 'Le fichier doit être une image.',
            'main_image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            'gallery_images.*.image' => 'Les fichiers doivent être des images.',
            'gallery_images.*.max' => 'Chaque image ne doit pas dépasser 2 Mo.',
        ];
    }
}