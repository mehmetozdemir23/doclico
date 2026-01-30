<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentAuthorizationServiceInterface;
use App\Domain\Document\DocumentId;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;

final readonly class DeleteDocument
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
        private DocumentAuthorizationServiceInterface $authService,
    ) {}

    public function execute(DocumentId $documentId, UserId $currentUserId): void
    {
        $document = $this->documentRepository->findById($documentId);

        if (! $document instanceof Document) {
            throw new DocumentNotFoundException($documentId);
        }

        if (! $this->authService->canDelete($currentUserId, $document)) {
            throw new UnauthorizedException('delete this document');
        }

        $this->documentRepository->delete($documentId);
    }
}
