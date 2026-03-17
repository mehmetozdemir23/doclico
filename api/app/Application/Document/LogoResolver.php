<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Identity\User;

final class LogoResolver
{
    public static function fileUrl(?User $owner): ?string
    {
        if (! $owner instanceof User || $owner->logo === null) {
            return null;
        }

        return 'file:///'.str_replace('\\', '/', storage_path('app/public/'.$owner->logo));
    }
}
