<?php

declare(strict_types=1);

namespace App\Application\Sharing;

final readonly class RenderSharedDocumentResult
{
    public function __construct(
        public string $content,
        public string $mimeType,
        public string $filename,
    ) {}
}
