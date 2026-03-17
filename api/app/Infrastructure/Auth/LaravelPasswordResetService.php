<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use App\Application\Identity\Exception\InvalidPasswordResetTokenException;
use App\Application\Identity\PasswordResetServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

final class LaravelPasswordResetService implements PasswordResetServiceInterface
{
    public function sendResetLink(string $email): void
    {
        Password::sendResetLink(['email' => $email]);
    }

    public function reset(string $email, string $token, string $newPassword): void
    {
        $status = Password::reset(
            ['email' => $email, 'token' => $token, 'password' => $newPassword],
            function ($user, string $password): void {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new InvalidPasswordResetTokenException;
        }
    }
}
