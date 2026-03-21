<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_id' => 'required|integer|exists:templates,id',
            'name' => 'nullable|string|max:255',
            'data' => 'present|array',
            'client_id' => 'nullable|uuid|exists:clients,id',
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'template_id.required' => 'Le modèle est requis',
            'template_id.integer' => 'Le modèle doit être un identifiant valide',
            'template_id.exists' => 'Le modèle sélectionné n\'existe pas',
            'name.string' => 'Le nom doit être une chaîne de caractères',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères',
            'data.present' => 'Les données sont requises',
            'data.array' => 'Les données doivent être un tableau',
        ];
    }
}
