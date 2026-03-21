<?php

declare(strict_types=1);

namespace App\Application\Document;

final readonly class DocumentClientResult
{
    public function __construct(
        public string $id,
        public string $nom,
        public ?string $email = null,
    ) {}
}
