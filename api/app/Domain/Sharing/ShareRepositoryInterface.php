<?php

declare(strict_types=1);

namespace App\Domain\Sharing;

use App\Domain\Document\DocumentId;

interface ShareRepositoryInterface
{
    public function save(Share $share): Share;

    public function findById(ShareId $id): ?Share;

    public function findByToken(ShareToken $token): ?Share;

    /** @return Share[] */
    public function findByDocumentId(DocumentId $documentId): array;

    public function delete(ShareId $id): void;

    public function update(Share $share): void;
}
