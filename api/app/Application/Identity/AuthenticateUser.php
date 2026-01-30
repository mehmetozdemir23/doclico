<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Domain\Identity\Email;
use App\Domain\Identity\Exception\InvalidEmailException;
use App\Domain\Identity\User;
use App\Domain\Identity\UserRepositoryInterface;

final readonly class AuthenticateUser
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {}

    public function execute(string $email, string $password): ?UserResult
    {
        try {
            $emailVO = Email::fromString($email);
        } catch (InvalidEmailException) {
            return null;
        }

        $user = $this->userRepository->findByEmail($emailVO);

        if (! $user instanceof User) {
            return null;
        }

        if (! $this->passwordHasher->verify($password, $user->password)) {
            return null;
        }

        return UserResultMapper::toResult($user);
    }
}
