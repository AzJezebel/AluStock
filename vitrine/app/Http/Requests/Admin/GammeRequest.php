<?php
// app/Http/Requests/Admin/GammeRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('gamme') ? $this->route('gamme')->id : null;

        return [
            'nom' => 'required|string|max:255|unique:gammes,nom,' . $id,
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
            'couleur' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'ordre' => 'integer',
        ];
    }
}