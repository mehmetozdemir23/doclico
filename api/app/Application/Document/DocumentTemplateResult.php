<?php

declare(strict_types=1);

namespace App\Application\Document;

final readonly class DocumentTemplateResult
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
    ) {}
}
