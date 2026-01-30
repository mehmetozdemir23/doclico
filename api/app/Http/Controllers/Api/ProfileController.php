<?php

namespace App\Http\Controllers\Api;

use App\Application\Profile\GetProfile;
use App\Application\Profile\UpdateProfile;
use App\Application\Profile\UpdateProfileData;
use App\Domain\Identity\UserId;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly GetProfile $getProfile,
        private readonly UpdateProfile $updateProfile,
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
        );

        $result = $this->updateProfile->execute($data);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $result,
        ]);
    }
}
