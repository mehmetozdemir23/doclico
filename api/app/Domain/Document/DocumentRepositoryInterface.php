<?php

declare(strict_types=1);

namespace App\Domain\Document;

use App\Domain\Identity\UserId;

interface DocumentRepositoryInterface
{
    public function save(Document $document): Document;

    public function findById(DocumentId $id): ?Document;

    /** @return Document[] */
    public function findByUserId(UserId $userId): array;

    public function delete(DocumentId $id): void;
}
