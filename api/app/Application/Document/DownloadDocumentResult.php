<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\FileGeneration\FileFormat;

final readonly class DownloadDocumentResult
{
    public function __construct(
        public string $content,
        public FileFormat $format,
        public string $filename,
    ) {}
}
