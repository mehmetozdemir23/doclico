<?php

declare(strict_types=1);

namespace App\Application\Identity;

interface PasswordHasherInterface
{
    public function hash(string $password): string;

    public function verify(string $password, string $hash): bool;
}
