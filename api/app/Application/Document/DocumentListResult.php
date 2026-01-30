<?php

declare(strict_types=1);

namespace App\Application\Document;

final readonly class DocumentListResult
{
    /** @param DocumentResult[] $documents */
    public function __construct(
        public array $documents,
    ) {}
}
