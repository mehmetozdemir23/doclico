<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Application\Identity\AuthenticateWithGoogle;
use App\Application\Identity\SessionManagerInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function __construct(
        private readonly AuthenticateWithGoogle $authenticateWithGoogle,
        private readonly SessionManagerInterface $sessionManager,
    ) {}

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $nameParts = explode(' ', (string) $googleUser->getName(), 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? $nameParts[0];

        $result = $this->authenticateWithGoogle->execute(
            googleId: $googleUser->getId(),
            email: $googleUser->getEmail(),
            firstName: $firstName,
            lastName: $lastName,
        );

        $this->sessionManager->login($result);
        $this->sessionManager->regenerateSession();

        return redirect(config('app.frontend_url').'/dashboard');
    }
}
