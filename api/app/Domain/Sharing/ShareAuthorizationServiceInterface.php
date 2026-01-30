<?php

declare(strict_types=1);

namespace App\Domain\Sharing;

use App\Domain\Document\Document;
use App\Domain\Identity\UserId;

interface ShareAuthorizationServiceInterface
{
    public function canViewShares(UserId $userId, Document $document): bool;

    public function canCreateShare(UserId $userId, Document $document): bool;

    public function canDeleteShare(UserId $userId, Share $share): bool;
}
