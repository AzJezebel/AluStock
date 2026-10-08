<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'        => ['required', 'string', 'min:2', 'max:120'],
            'email'      => ['required', 'email:rfc', 'max:190'],
            'entreprise' => ['nullable', 'string', 'max:150'],
            'sujet'      => ['required', 'string', 'min:3', 'max:190'],
            'message'    => ['required', 'string', 'min:10', 'max:5000'],

            // Honeypot : champ invisible, doit rester vide. Un robot le remplit.
            'website'    => ['prohibited'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nom'        => 'nom',
            'email'      => 'courriel',
            'entreprise' => 'entreprise',
            'sujet'      => 'sujet',
            'message'    => 'message',
        ];
    }

    public function messages(): array
    {
        return [
            'required'   => 'Le champ :attribute est obligatoire.',
            'email'      => 'Entrez une adresse courriel valide.',
            'min'        => 'Le champ :attribute est trop court (minimum :min caractères).',
            'max'        => 'Le champ :attribute est trop long (maximum :max caractères).',
            'prohibited' => 'Envoi refusé.',
        ];
    }
}