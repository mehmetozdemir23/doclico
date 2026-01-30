<?php

declare(strict_types=1);

namespace App\Domain\FileGeneration\Event;

use App\Domain\FileGeneration\FileGeneration;

final readonly class FileGenerationCompleted
{
    public function __construct(
        public FileGeneration $fileGeneration
    ) {}
}
