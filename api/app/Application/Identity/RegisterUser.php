<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Domain\Identity\Email;
use App\Domain\Identity\Exception\EmailAlreadyExistsException;
use App\Domain\Identity\User;
use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;
use DateTimeImmutable;

final readonly class RegisterUser
{
    public const string POLICY_VERSION = '1.0';

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {}

    public function execute(RegisterUserData $data): UserResult
    {
        $email = Email::fromString($data->email);

        if ($this->userRepository->existsByEmail($email)) {
            throw new EmailAlreadyExistsException($email->value);
        }

        $user = new User(
            id: UserId::generate(),
            firstName: $data->firstName,
            lastName: $data->lastName,
            email: $email,
            password: $this->passwordHasher->hash($data->password),
            consentAcceptedAt: new DateTimeImmutable,
            consentPolicyVersion: self::POLICY_VERSION,
        );

        $user = $this->userRepository->save($user);

        return UserResultMapper::toResult($user);
    }
}
