<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'company_name' => ['nullable', 'string', 'max:255'],
            'siret' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mentions_legales' => ['nullable', 'string', 'max:2000'],
            'numero_tva' => ['nullable', 'string', 'max:50'],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'first_name.required' => 'Le prénom est requis',
            'first_name.string' => 'Le prénom doit être une chaîne de caractères',
            'first_name.max' => 'Le prénom ne peut pas dépasser 255 caractères',
            'last_name.required' => 'Le nom est requis',
            'last_name.string' => 'Le nom doit être une chaîne de caractères',
            'last_name.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'email.required' => 'L\'email est requis',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères',
        ];
    }
}
