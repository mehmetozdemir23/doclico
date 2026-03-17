<?php

declare(strict_types=1);

namespace App\Application\Profile;

use App\Application\Identity\UserResult;
use App\Application\Identity\UserResultMapper;
use App\Domain\Identity\Exception\UserNotFoundException;
use App\Domain\Identity\User;
use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;

final readonly class GetProfile
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(UserId $userId): UserResult
    {
        $user = $this->userRepository->findById($userId);

        if (! $user instanceof User) {
            throw new UserNotFoundException($userId);
        }

        return UserResultMapper::toResult($user);
    }
}
