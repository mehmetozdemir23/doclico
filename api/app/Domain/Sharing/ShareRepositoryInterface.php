<?php

declare(strict_types=1);

namespace App\Domain\Sharing;

use App\Domain\Document\DocumentId;
use DateTimeImmutable;

interface ShareRepositoryInterface
{
    /**
     * @param  DocumentId[]  $documentIds
     * @return array<string, Share> keyed by document ID string
     */
    public function findLatestByDocumentIds(array $documentIds): array;

    public function save(Share $share): Share;

    public function findById(ShareId $id): ?Share;

    public function findByToken(ShareToken $token): ?Share;

    /** @return Share[] */
    public function findByDocumentId(DocumentId $documentId): array;

    public function delete(ShareId $id): void;

    public function update(Share $share): void;

    /** @return Share[] Shares not yet reminded, with zero downloads, created before $threshold, not expired */
    public function findNotRemindedOlderThan(DateTimeImmutable $threshold): array;
}
