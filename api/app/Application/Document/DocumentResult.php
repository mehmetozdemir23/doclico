<?php

declare(strict_types=1);

namespace App\Application\Document;

final readonly class DocumentResult
{
    public function __construct(
        public string $id,
        public string $name,
        public int $templateId,
        public array $data,
        public string $generatedAt,
        public ?DocumentTemplateResult $template = null,
        public ?DocumentClientResult $client = null,
        public ?DocumentShareResult $share = null,
    ) {}
}
