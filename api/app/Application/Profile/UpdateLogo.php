<?php

declare(strict_types=1);

namespace App\Application\Profile;

use App\Domain\Identity\Exception\UserNotFoundException;
use App\Domain\Identity\User;
use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;

final readonly class UpdateLogo
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(UserId $userId, ?string $logoPath): ?string
    {
        $user = $this->userRepository->findById($userId);

        if (! $user instanceof User) {
            throw new UserNotFoundException($userId);
        }

        $oldLogoPath = $user->logo;

        $this->userRepository->update(new User(
            id: $user->id,
            firstName: $user->firstName,
            lastName: $user->lastName,
            email: $user->email,
            password: $user->password,
            googleId: $user->googleId,
            companyName: $user->companyName,
            siret: $user->siret,
            address: $user->address,
            phone: $user->phone,
            mentionsLegales: $user->mentionsLegales,
            numeroTva: $user->numeroTva,
            logo: $logoPath,
            consentAcceptedAt: $user->consentAcceptedAt,
            consentPolicyVersion: $user->consentPolicyVersion,
        ));

        return $oldLogoPath;
    }
}
