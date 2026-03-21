<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Application\Identity\AuthenticateUser;
use App\Application\Identity\Exception\InvalidPasswordResetTokenException;
use App\Application\Identity\RegisterUser;
use App\Application\Identity\RegisterUserData;
use App\Application\Identity\ResetPassword;
use App\Application\Identity\SendPasswordResetLink;
use App\Application\Identity\SessionManagerInterface;
use App\Application\Identity\UserResult;
use App\Application\Identity\UserResultMapper;
use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUser $registerUser,
        private readonly AuthenticateUser $authenticateUser,
        private readonly SessionManagerInterface $sessionManager,
        private readonly UserRepositoryInterface $userRepository,
        private readonly SendPasswordResetLink $sendPasswordResetLink,
        private readonly ResetPassword $resetPassword,
    ) {}

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'consent' => 'accepted',
        ]);

        $data = new RegisterUserData(
            firstName: $validated['first_name'],
            lastName: $validated['last_name'],
            email: $validated['email'],
            password: $validated['password'],
        );

        $result = $this->registerUser->execute($data);

        $this->sessionManager->login($result);

        return response()->json([
            'message' => 'Inscription réussie',
            'user' => $result,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authenticateUser->execute(
            $validated['email'],
            $validated['password']
        );

        if (! $result instanceof UserResult) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        $this->sessionManager->login($result);
        $this->sessionManager->regenerateSession();

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $result,
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->sessionManager->logout();
        $this->sessionManager->invalidateSession();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function user(Request $request): JsonResponse
    {
        $user = $this->userRepository->findById(
            UserId::fromString($request->user()->id)
        );

        return response()->json(UserResultMapper::toResult($user));
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $this->sendPasswordResetLink->execute($request->string('email')->toString());

        return response()->json([
            'message' => 'Si un compte est associé à cet email, un lien de réinitialisation vous a été envoyé.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        try {
            $this->resetPassword->execute(
                email: $request->string('email')->toString(),
                token: $request->string('token')->toString(),
                newPassword: $request->string('password')->toString(),
            );
        } catch (InvalidPasswordResetTokenException) {
            return response()->json(['message' => 'Ce lien de réinitialisation est invalide ou a expiré.'], 422);
        }

        return response()->json(['message' => 'Mot de passe réinitialisé avec succès.']);
    }
}
