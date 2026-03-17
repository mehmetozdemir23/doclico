<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Application\Identity\DeleteAccount;
use App\Application\Identity\Exception\InvalidCurrentPasswordException;
use App\Application\Identity\ExportUserData;
use App\Application\Identity\SessionManagerInterface;
use App\Application\Identity\UpdatePassword;
use App\Application\Identity\UpdatePasswordData;
use App\Application\Profile\GetProfile;
use App\Application\Profile\UpdateLogo;
use App\Application\Profile\UpdateProfile;
use App\Application\Profile\UpdateProfileData;
use App\Domain\Identity\UserId;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct(
        private readonly GetProfile $getProfile,
        private readonly UpdateProfile $updateProfile,
        private readonly UpdateLogo $updateLogo,
        private readonly UpdatePassword $updatePassword,
        private readonly ExportUserData $exportUserData,
        private readonly DeleteAccount $deleteAccount,
        private readonly SessionManagerInterface $sessionManager,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $result = $this->getProfile->execute(UserId::fromString($request->user()->id));

        return response()->json($result);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $data = new UpdateProfileData(
            userId: UserId::fromString($request->user()->id),
            firstName: $validated['first_name'],
            lastName: $validated['last_name'],
            email: $validated['email'],
            companyName: $validated['company_name'] ?? null,
            siret: $validated['siret'] ?? null,
            address: $validated['address'] ?? null,
            phone: $validated['phone'] ?? null,
            mentionsLegales: $validated['mentions_legales'] ?? null,
            numeroTva: $validated['numero_tva'] ?? null,
        );

        $result = $this->updateProfile->execute($data);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $result,
        ]);
    }

    public function storeLogo(Request $request): JsonResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:jpeg,png,gif,webp', 'max:1024'],
        ]);

        $path = $request->file('logo')->store('logos', 'public');
        $oldPath = $this->updateLogo->execute(UserId::fromString($request->user()->id), $path);

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return response()->json(['url' => Storage::disk('public')->url($path)]);
    }

    public function destroyLogo(Request $request): JsonResponse
    {
        $oldPath = $this->updateLogo->execute(UserId::fromString($request->user()->id), null);

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return response()->json(['message' => 'Logo supprimé']);
    }

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
                'errors' => ['current_password' => [$e->getMessage()]],
            ], 422);
        }

        return response()->json(['message' => 'Mot de passe modifié avec succès']);
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->deleteAccount->execute(UserId::fromString($request->user()->id));

        $this->sessionManager->logout();
        $this->sessionManager->invalidateSession();

        return response()->json(['message' => 'Compte supprimé avec succès']);
    }

    public function export(Request $request): Response
    {
        $data = $this->exportUserData->execute(
            UserId::fromString($request->user()->id)
        );

        $filename = 'doclico-export-'.now()->format('Y-m-d').'.json';

        return response(
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            200,
            [
                'Content-Type' => 'application/json',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]
        );
    }
}
