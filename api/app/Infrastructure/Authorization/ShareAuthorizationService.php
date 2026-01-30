<?php

declare(strict_types=1);

namespace App\Infrastructure\Authorization;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Identity\UserId;
use App\Domain\Sharing\Share;
use App\Domain\Sharing\ShareAuthorizationServiceInterface;

final readonly class ShareAuthorizationService implements ShareAuthorizationServiceInterface
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
    ) {}

    public function canViewShares(UserId $userId, Document $document): bool
    {
        return $this->isDocumentOwner($userId, $document);
    }

    public function canCreateShare(UserId $userId, Document $document): bool
    {
        return $this->isDocumentOwner($userId, $document);
    }

    public function canDeleteShare(UserId $userId, Share $share): bool
    {
        $document = $this->documentRepository->findById($share->documentId);

        return $document instanceof Document && $this->isDocumentOwner($userId, $document);
    }

    private function isDocumentOwner(UserId $userId, Document $document): bool
    {
        return $document->userId->equals($userId);
    }
}
