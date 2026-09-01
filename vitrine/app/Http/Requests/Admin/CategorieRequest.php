<?php
// app/Http/Requests/Admin/CategorieRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategorieRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('categorie') ? $this->route('categorie')->id : null;

        return [
            'nom' => 'required|string|max:255|unique:categories,nom,' . $id,
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'ordre' => 'integer'
        ];
    }
}