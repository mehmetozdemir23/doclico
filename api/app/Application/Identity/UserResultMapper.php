<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Domain\Identity\User;
use Illuminate\Support\Facades\Storage;

final class UserResultMapper
{
    public static function toResult(User $user): UserResult
    {
        return new UserResult(
            id: $user->id->value,
            firstName: $user->firstName,
            lastName: $user->lastName,
            email: $user->email->value,
            companyName: $user->companyName,
            siret: $user->siret,
            address: $user->address,
            phone: $user->phone,
            mentionsLegales: $user->mentionsLegales,
            numeroTva: $user->numeroTva,
            logo: $user->logo ? Storage::disk('public')->url($user->logo) : null,
        );
    }
}
