<?php

use App\Domain\Document\DocumentId;
use App\Domain\Sharing\Share;
use App\Domain\Sharing\ShareId;
use App\Domain\Sharing\ShareToken;

it('creates a share', function (): void {
    $shareId = ShareId::generate();
    $documentId = DocumentId::generate();
    $token = ShareToken::generate();
    $share = new Share(
        id: $shareId,
        documentId: $documentId,
        token: $token,
        expiresAt: null,
    );

    expect($share->id)->toBe($shareId)
        ->and($share->documentId)->toBe($documentId)
        ->and($share->downloadsCount())->toBe(0)
        ->and($share->lastDownloadedAt())->toBeNull();
});

it('is not expired when expiration is null', function (): void {
    $share = new Share(
        id: ShareId::generate(),
        documentId: DocumentId::generate(),
        token: ShareToken::generate(),
        expiresAt: null,
    );

    expect($share->isExpired())->toBeFalse();
});

it('is not expired when expiration is in the future', function (): void {
    $share = new Share(
        id: ShareId::generate(),
        documentId: DocumentId::generate(),
        token: ShareToken::generate(),
        expiresAt: new DateTimeImmutable('+1 day'),
    );

    expect($share->isExpired())->toBeFalse();
});

it('is expired when expiration is in the past', function (): void {
    $share = new Share(
        id: ShareId::generate(),
        documentId: DocumentId::generate(),
        token: ShareToken::generate(),
        expiresAt: new DateTimeImmutable('-1 day'),
    );

    expect($share->isExpired())->toBeTrue();
});

it('records download and increments count', function (): void {
    $share = new Share(
        id: ShareId::generate(),
        documentId: DocumentId::generate(),
        token: ShareToken::generate(),
        expiresAt: null,
    );

    $share->recordDownload();

    expect($share->downloadsCount())->toBe(1)
        ->and($share->lastDownloadedAt())->toBeInstanceOf(DateTimeImmutable::class);

    $share->recordDownload();

    expect($share->downloadsCount())->toBe(2);
});

it('generates share url', function (): void {
    $token = ShareToken::fromString('a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6');
    $share = new Share(
        id: ShareId::generate(),
        documentId: DocumentId::generate(),
        token: $token,
        expiresAt: null,
    );

    expect($share->shareUrl('https://example.com'))->toBe('https://example.com/api/share/a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6');
});
