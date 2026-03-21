<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'current_password.required' => 'Le mot de passe actuel est requis',
            'new_password.required' => 'Le nouveau mot de passe est requis',
            'new_password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ];
    }
}
