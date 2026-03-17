<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Domain\Sharing\Share;

final class ShareResultMapper
{
    public static function toResult(Share $share, string $baseUrl): ShareResult
    {
        return new ShareResult(
            id: $share->id->value,
            documentId: $share->documentId->value,
            token: $share->token->value,
            expiresAt: $share->expiresAt?->format('c'),
            downloadsCount: $share->downloadsCount(),
            lastDownloadedAt: $share->lastDownloadedAt()?->format('c'),
            shareUrl: $share->shareUrl($baseUrl),
            viewsCount: $share->viewsCount(),
            firstViewedAt: $share->firstViewedAt()?->format('c'),
        );
    }

    /** @param Share[] $shares */
    public static function toListResult(array $shares, string $baseUrl): ShareListResult
    {
        return new ShareListResult(
            shares: array_map(
                fn (Share $share): ShareResult => self::toResult($share, $baseUrl),
                $shares
            )
        );
    }
}
