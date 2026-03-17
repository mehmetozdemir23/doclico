<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Domain\Sharing\Share;
use App\Domain\Sharing\ShareRepositoryInterface;
use App\Domain\Sharing\ShareToken;
use InvalidArgumentException;

final readonly class TrackShareView
{
    public function __construct(
        private ShareRepositoryInterface $shareRepository,
    ) {}

    public function execute(string $token): void
    {
        try {
            $shareToken = ShareToken::fromString($token);
        } catch (InvalidArgumentException) {
            return;
        }

        $share = $this->shareRepository->findByToken($shareToken);

        if (! $share instanceof Share || $share->isExpired()) {
            return;
        }

        $share->recordView();
        $this->shareRepository->update($share);
    }
}
