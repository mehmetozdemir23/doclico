<?php

namespace App\Http\Requests;

use App\Models\Template;
use Illuminate\Foundation\Http\FormRequest;

class GenerateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'template_id' => 'required|integer|exists:templates,id',
            'name' => 'nullable|string|max:255',
            'data' => 'present|array',
        ];

        if ($this->template_id) {
            $template = Template::find($this->template_id);
            if ($template) {
                foreach ($template->fields as $field) {
                    $fieldRules = $field['required'] ?? false ? ['required'] : ['nullable'];
                    $rules["data.{$field['name']}"] = $fieldRules;
                }
            }
        }

        return $rules;
    }

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
