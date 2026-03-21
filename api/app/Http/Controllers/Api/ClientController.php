<?php

namespace App\Http\Controllers\Api;

use App\Application\Client\CreateClient;
use App\Application\Client\CreateClientData;
use App\Application\Client\DeleteClient;
use App\Application\Client\GetUserClients;
use App\Application\Client\UpdateClient;
use App\Application\Client\UpdateClientData;
use App\Domain\Client\ClientId;
use App\Domain\Identity\UserId;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        private readonly GetUserClients $getUserClients,
        private readonly CreateClient $createClient,
        private readonly UpdateClient $updateClient,
        private readonly DeleteClient $deleteClient,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $userId = UserId::fromString($request->user()->id);
        $clients = $this->getUserClients->execute($userId);

        return response()->json($clients);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $data = new CreateClientData(
            userId: UserId::fromString($request->user()->id),
            nom: $validated['nom'],
            adresse: $validated['adresse'] ?? null,
            email: $validated['email'] ?? null,
            telephone: $validated['telephone'] ?? null,
            siret: $validated['siret'] ?? null,
        );

        $result = $this->createClient->execute($data);

        return response()->json($result, 201);
    }

    public function update(UpdateClientRequest $request, string $clientId): JsonResponse
    {
        $validated = $request->validated();

        $data = new UpdateClientData(
            clientId: ClientId::fromString($clientId),
            currentUserId: UserId::fromString($request->user()->id),
            nom: $validated['nom'],
            adresse: $validated['adresse'] ?? null,
            email: $validated['email'] ?? null,
            telephone: $validated['telephone'] ?? null,
            siret: $validated['siret'] ?? null,
        );

        $result = $this->updateClient->execute($data);

        return response()->json($result);
    }

    public function destroy(Request $request, string $clientId): JsonResponse
    {
        $this->deleteClient->execute(
            ClientId::fromString($clientId),
            UserId::fromString($request->user()->id),
        );

        return response()->json(['message' => 'Client supprimé avec succès']);
    }
}
