<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Application\Identity\Exception\InvalidCurrentPasswordException;
use App\Domain\Identity\Exception\UserNotFoundException;
use App\Domain\Identity\User;
use App\Domain\Identity\UserRepositoryInterface;

final readonly class UpdatePassword
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {}

    public function execute(UpdatePasswordData $data): void
    {
        $user = $this->userRepository->findById($data->userId);

        if (! $user instanceof User) {
            throw new UserNotFoundException($data->userId);
        }

        if (! $this->passwordHasher->verify($data->currentPassword, $user->password)) {
            throw new InvalidCurrentPasswordException;
        }

        $hashedPassword = $this->passwordHasher->hash($data->newPassword);

        $updatedUser = new User(
            id: $user->id,
            firstName: $user->firstName,
            lastName: $user->lastName,
            email: $user->email,
            password: $hashedPassword,
        );

        $this->userRepository->update($updatedUser);
    }
}
