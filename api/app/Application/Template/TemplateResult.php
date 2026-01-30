<?php

declare(strict_types=1);

namespace App\Application\Template;

final readonly class TemplateResult
{
    public function __construct(
        public int $id,
        public string $type,
        public string $name,
        public string $category,
        public ?string $icon,
        public array $fields,
        public bool $popular,
    ) {}
}
