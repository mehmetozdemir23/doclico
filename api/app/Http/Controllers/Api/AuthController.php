<?php

namespace App\Http\Controllers\Api;

use App\Application\Identity\AuthenticateUser;
use App\Application\Identity\RegisterUser;
use App\Application\Identity\RegisterUserData;
use App\Application\Identity\SessionManagerInterface;
use App\Application\Identity\UserResult;
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
    ) {}

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
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

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        $result = new UserResult(
            id: $user->id,
            firstName: $user->first_name,
            lastName: $user->last_name,
            email: $user->email,
        );

        return response()->json($result);
    }
}
