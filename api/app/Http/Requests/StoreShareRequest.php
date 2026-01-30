<?php

namespace App\Http\Requests;

use App\Models\DocumentShare;
use Gate;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreShareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', [DocumentShare::class, $this->route('document')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'expires_in' => 'required|string|in:24h,7d,30d,never',
        ];
    }

    public function messages(): array
    {
        return [
            'expires_in.required' => 'La durée d\'expiration est requise',
            'expires_in.string' => 'La durée d\'expiration doit être une chaîne de caractères',
            'expires_in.in' => 'La durée d\'expiration doit être : 24h, 7d, 30d ou never',
        ];
    }
}
