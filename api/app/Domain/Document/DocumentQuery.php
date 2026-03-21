<?php

declare(strict_types=1);

namespace App\Domain\Document;

final readonly class DocumentQuery
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 20,
        public string $sortBy = 'createdAt',
        public string $sortDir = 'desc',
        public array $templateTypes = [],
        public ?string $clientId = null,
    ) {}
}
