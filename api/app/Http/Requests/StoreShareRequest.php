<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreShareRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'expires_in' => 'required|string|in:24h,7d,30d,never',
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'expires_in.required' => 'La durée d\'expiration est requise',
            'expires_in.string' => 'La durée d\'expiration doit être une chaîne de caractères',
            'expires_in.in' => 'La durée d\'expiration doit être : 24h, 7d, 30d ou never',
        ];
    }
}
