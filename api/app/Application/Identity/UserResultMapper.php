<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Domain\Identity\User;

final class UserResultMapper
{
    public static function toResult(User $user): UserResult
    {
        return new UserResult(
            id: $user->id->value,
            firstName: $user->firstName,
            lastName: $user->lastName,
            email: $user->email->value,
        );
    }
}
