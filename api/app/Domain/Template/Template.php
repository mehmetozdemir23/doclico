<?php

declare(strict_types=1);

namespace App\Domain\Template;

final readonly class Template
{
    public function __construct(
        public TemplateId $id,
        public string $type,
        public string $name,
        public string $category,
        public ?string $icon,
        public array $fields,
        public bool $popular = false,
    ) {}

    public function id(): TemplateId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function hasRequiredFields(): bool
    {
        foreach ($this->fields as $field) {
            if ($field['required'] ?? false) {
                return true;
            }
        }

        return false;
    }

    public function validateData(array $data): array
    {
        $errors = [];

        foreach ($this->fields as $field) {
            $name = $field['name'];
            $isRequired = $field['required'] ?? false;
            $value = $data[$name] ?? null;

            if ($isRequired && empty($value)) {
                $errors[$name] = "Le champ {$name} est requis";
            }
        }

        return $errors;
    }
}
