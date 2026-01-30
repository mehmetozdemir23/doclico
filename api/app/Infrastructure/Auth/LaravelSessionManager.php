<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use App\Application\Identity\SessionManagerInterface;
use App\Application\Identity\UserResult;
use App\Infrastructure\Persistence\Eloquent\UserModel;
use Illuminate\Support\Facades\Auth;

final class LaravelSessionManager implements SessionManagerInterface
{
    public function login(UserResult $user): void
    {
        $userModel = UserModel::find($user->id);

        if ($userModel) {
            Auth::login($userModel);
        }
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
    }

    public function regenerateSession(): void
    {
        request()->session()->regenerate();
    }

    public function invalidateSession(): void
    {
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
