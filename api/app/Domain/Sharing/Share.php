<?php

declare(strict_types=1);

namespace App\Domain\Sharing;

use App\Domain\Document\DocumentId;
use DateTimeImmutable;

final class Share
{
    public function __construct(
        public readonly ShareId $id,
        public readonly DocumentId $documentId,
        public readonly ShareToken $token,
        public readonly ?DateTimeImmutable $expiresAt,
        private int $downloadsCount = 0,
        private ?DateTimeImmutable $lastDownloadedAt = null,
    ) {}

    public function downloadsCount(): int
    {
        return $this->downloadsCount;
    }

    public function lastDownloadedAt(): ?DateTimeImmutable
    {
        return $this->lastDownloadedAt;
    }

    public function isExpired(): bool
    {
        if (! $this->expiresAt instanceof DateTimeImmutable) {
            return false;
        }

        return $this->expiresAt < new DateTimeImmutable;
    }

    public function recordDownload(): void
    {
        $this->downloadsCount++;
        $this->lastDownloadedAt = new DateTimeImmutable;
    }

    public function shareUrl(string $baseUrl): string
    {
        return "{$baseUrl}/api/share/{$this->token->value}";
    }
}
