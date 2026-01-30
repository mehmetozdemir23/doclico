<?php

declare(strict_types=1);

namespace App\Infrastructure\Authorization;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentAuthorizationServiceInterface;
use App\Domain\Identity\UserId;

final class DocumentAuthorizationService implements DocumentAuthorizationServiceInterface
{
    public function canView(UserId $userId, Document $document): bool
    {
        return $this->isOwner($userId, $document);
    }

    public function canUpdate(UserId $userId, Document $document): bool
    {
        return $this->isOwner($userId, $document);
    }

    public function canDelete(UserId $userId, Document $document): bool
    {
        return $this->isOwner($userId, $document);
    }

    private function isOwner(UserId $userId, Document $document): bool
    {
        return $document->userId->equals($userId);
    }
}
