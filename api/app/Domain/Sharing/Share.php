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
        public readonly DateTimeImmutable $sharedAt = new DateTimeImmutable,
        private int $downloadsCount = 0,
        private ?DateTimeImmutable $lastDownloadedAt = null,
        private ?DateTimeImmutable $remindedAt = null,
        private int $viewsCount = 0,
        private ?DateTimeImmutable $firstViewedAt = null,
    ) {}

    public function downloadsCount(): int
    {
        return $this->downloadsCount;
    }

    public function lastDownloadedAt(): ?DateTimeImmutable
    {
        return $this->lastDownloadedAt;
    }

    public function remindedAt(): ?DateTimeImmutable
    {
        return $this->remindedAt;
    }

    public function markReminded(): void
    {
        $this->remindedAt = new DateTimeImmutable;
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

    public function viewsCount(): int
    {
        return $this->viewsCount;
    }

    public function firstViewedAt(): ?DateTimeImmutable
    {
        return $this->firstViewedAt;
    }

    public function recordView(): void
    {
        $this->viewsCount++;
        $this->firstViewedAt ??= new DateTimeImmutable;
    }

    public function shareUrl(string $baseUrl): string
    {
        return "{$baseUrl}/share/{$this->token->value}";
    }
}
