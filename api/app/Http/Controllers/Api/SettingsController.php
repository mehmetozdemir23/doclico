<?php

namespace App\Http\Controllers\Api;

use App\Application\Identity\Exception\InvalidCurrentPasswordException;
use App\Application\Identity\UpdatePassword;
use App\Application\Identity\UpdatePasswordData;
use App\Domain\Identity\UserId;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function __construct(
        private readonly UpdatePassword $updatePassword,
    ) {}

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $this->updatePassword->execute(new UpdatePasswordData(
                userId: UserId::fromString($request->user()->id),
                currentPassword: $validated['current_password'],
                newPassword: $validated['new_password'],
            ));
        } catch (InvalidCurrentPasswordException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => [
                    'current_password' => [$e->getMessage()],
                ],
            ], 422);
        }

        return response()->json([
            'message' => 'Mot de passe modifié avec succès',
        ]);
    }
}
