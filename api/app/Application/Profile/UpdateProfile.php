<?php

declare(strict_types=1);

namespace App\Application\Profile;

use App\Application\Identity\UserResult;
use App\Application\Identity\UserResultMapper;
use App\Domain\Identity\Email;
use App\Domain\Identity\Exception\EmailAlreadyExistsException;
use App\Domain\Identity\Exception\UserNotFoundException;
use App\Domain\Identity\User;
use App\Domain\Identity\UserRepositoryInterface;

final readonly class UpdateProfile
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(UpdateProfileData $data): UserResult
    {
        $user = $this->userRepository->findById($data->userId);

        if (! $user instanceof User) {
            throw new UserNotFoundException($data->userId);
        }

        $email = Email::fromString($data->email);

        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser instanceof User && ! $existingUser->id->equals($data->userId)) {
            throw new EmailAlreadyExistsException($email->value);
        }

        $updatedUser = new User(
            id: $user->id,
            firstName: $data->firstName,
            lastName: $data->lastName,
            email: $email,
            password: $user->password,
        );

        $this->userRepository->update($updatedUser);

        return UserResultMapper::toResult($updatedUser);
    }
}
