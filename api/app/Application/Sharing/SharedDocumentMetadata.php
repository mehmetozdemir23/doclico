<?php

declare(strict_types=1);

namespace App\Application\Sharing;

final readonly class SharedDocumentMetadata
{
    public function __construct(
        public string $documentName,
        public string $templateName,
        public string $templateType,
        public ?string $emitter,
        public ?string $emitterCompany,
        public ?string $emitterLogo,
        public ?string $expiresAt,
    ) {}
}
