<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Domain\Identity\Email;
use App\Domain\Identity\Exception\InvalidEmailException;
use App\Domain\Identity\User;
use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;
use DateTimeImmutable;
use InvalidArgumentException;

final readonly class AuthenticateWithGoogle
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(string $googleId, string $email, string $firstName, string $lastName): UserResult
    {
        $user = $this->userRepository->findByGoogleId($googleId);
        if ($user instanceof User) {
            return UserResultMapper::toResult($user);
        }

        try {
            $emailVO = Email::fromString($email);
        } catch (InvalidEmailException) {
            throw new InvalidArgumentException("Email invalide reçu de Google : {$email}");
        }

        $existing = $this->userRepository->findByEmail($emailVO);
        if ($existing instanceof User) {
            $linked = new User(
                id: $existing->id,
                firstName: $existing->firstName,
                lastName: $existing->lastName,
                email: $existing->email,
                password: $existing->password,
                googleId: $googleId,
                companyName: $existing->companyName,
                siret: $existing->siret,
                address: $existing->address,
                phone: $existing->phone,
                mentionsLegales: $existing->mentionsLegales,
                numeroTva: $existing->numeroTva,
                logo: $existing->logo,
                consentAcceptedAt: $existing->consentAcceptedAt,
                consentPolicyVersion: $existing->consentPolicyVersion,
            );
            $this->userRepository->update($linked);

            return UserResultMapper::toResult($linked);
        }

        $newUser = new User(
            id: UserId::generate(),
            firstName: $firstName,
            lastName: $lastName,
            email: $emailVO,
            password: null,
            googleId: $googleId,
            consentAcceptedAt: new DateTimeImmutable,
            consentPolicyVersion: RegisterUser::POLICY_VERSION,
        );
        $saved = $this->userRepository->save($newUser);

        return UserResultMapper::toResult($saved);
    }
}
