<?php

declare(strict_types=1);

namespace App\Application\Identity;

interface SessionManagerInterface
{
    public function login(UserResult $user): void;

    public function logout(): void;

    public function regenerateSession(): void;

    public function invalidateSession(): void;
}
