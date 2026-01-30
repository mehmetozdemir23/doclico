<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Sharing\Exception\ShareNotFoundException;
use App\Domain\Sharing\Share;
use App\Domain\Sharing\ShareAuthorizationServiceInterface;
use App\Domain\Sharing\ShareId;
use App\Domain\Sharing\ShareRepositoryInterface;

final readonly class RevokeShare
{
    public function __construct(
        private ShareRepositoryInterface $shareRepository,
        private ShareAuthorizationServiceInterface $authService,
    ) {}

    public function execute(ShareId $shareId, UserId $currentUserId): void
    {
        $share = $this->shareRepository->findById($shareId);

        if (! $share instanceof Share) {
            throw new ShareNotFoundException($shareId);
        }

        if (! $this->authService->canDeleteShare($currentUserId, $share)) {
            throw new UnauthorizedException('revoke this share');
        }

        $this->shareRepository->delete($shareId);
    }
}
