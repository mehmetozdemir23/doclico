<?php

declare(strict_types=1);

namespace App\Application\Sharing;

final readonly class ShareResult
{
    public function __construct(
        public string $id,
        public string $documentId,
        public string $token,
        public ?string $expiresAt,
        public int $downloadsCount,
        public ?string $lastDownloadedAt,
        public string $shareUrl,
        public int $viewsCount = 0,
        public ?string $firstViewedAt = null,
    ) {}
}
