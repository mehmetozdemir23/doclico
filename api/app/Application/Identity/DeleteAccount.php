<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;

final readonly class DeleteAccount
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(UserId $userId): void
    {
        $this->userRepository->delete($userId);
    }
}
