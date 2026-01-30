<?php

declare(strict_types=1);

namespace App\Domain\Document;

use App\Domain\Identity\UserId;

interface DocumentAuthorizationServiceInterface
{
    public function canView(UserId $userId, Document $document): bool;

    public function canUpdate(UserId $userId, Document $document): bool;

    public function canDelete(UserId $userId, Document $document): bool;
}
