<?php

declare(strict_types=1);

namespace App\Application\FileGeneration;

final readonly class FileGenerationResult
{
    public function __construct(
        public string $id,
        public int $templateId,
        public ?string $userId,
        public string $format,
        public string $status,
        public ?string $filePath,
        public ?string $error,
    ) {}
}
