<?php

declare(strict_types=1);

namespace App\Domain\Identity;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function update(User $user): void;

    public function findById(UserId $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function findByGoogleId(string $googleId): ?User;

    public function existsByEmail(Email $email): bool;

    public function delete(UserId $id): void;
}
