<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Identity\UserId;

final readonly class GetUserDocuments
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
    ) {}

    public function execute(UserId $userId): DocumentListResult
    {
        $documents = $this->documentRepository->findByUserId($userId);

        return DocumentResultMapper::toListResult($documents);
    }
}
