<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentId;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Sharing\ShareAuthorizationServiceInterface;
use App\Domain\Sharing\ShareRepositoryInterface;

final readonly class GetDocumentShares
{
    public function __construct(
        private ShareRepositoryInterface $shareRepository,
        private DocumentRepositoryInterface $documentRepository,
        private ShareAuthorizationServiceInterface $authService,
        private string $baseUrl,
    ) {}

    public function execute(DocumentId $documentId, UserId $currentUserId): ShareListResult
    {
        $document = $this->documentRepository->findById($documentId);

        if (! $document instanceof Document) {
            throw new DocumentNotFoundException($documentId);
        }

        if (! $this->authService->canViewShares($currentUserId, $document)) {
            throw new UnauthorizedException('view shares for this document');
        }

        $shares = $this->shareRepository->findByDocumentId($documentId);

        return ShareResultMapper::toListResult($shares, $this->baseUrl);
    }
}
