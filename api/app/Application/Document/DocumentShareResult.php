<?php

declare(strict_types=1);

namespace App\Application\Document;

final readonly class DocumentShareResult
{
    public function __construct(
        public int $viewsCount,
        public int $downloadsCount,
        public bool $isExpired,
        public ?string $expiresAt,
    ) {}
}
