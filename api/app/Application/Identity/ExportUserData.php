<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Domain\Client\Client;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Document\Document;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Identity\User;
use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;

final readonly class ExportUserData
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ClientRepositoryInterface $clientRepository,
        private DocumentRepositoryInterface $documentRepository,
    ) {}

    public function execute(UserId $userId): array
    {
        $user = $this->userRepository->findById($userId);
        $clients = $this->clientRepository->findByUserId($userId);
        $documents = $this->documentRepository->findByUserId($userId);

        return [
            'exported_at' => now()->toIso8601String(),
            'profile' => $user instanceof User ? [
                'first_name' => $user->firstName,
                'last_name' => $user->lastName,
                'email' => $user->email->value,
                'siret' => $user->siret,
                'address' => $user->address,
                'phone' => $user->phone,
                'numero_tva' => $user->numeroTva,
                'mentions_legales' => $user->mentionsLegales,
            ] : null,
            'clients' => array_map(fn (Client $c): array => [
                'nom' => $c->nom,
                'adresse' => $c->adresse,
                'email' => $c->email,
                'telephone' => $c->telephone,
            ], $clients),
            'documents' => array_map(fn (Document $d): array => [
                'name' => $d->name,
                'generated_at' => $d->generatedAt->format('c'),
                'data' => $d->data,
            ], $documents),
        ];
    }
}
