<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Domain\Client\ClientId;
use App\Domain\Client\Client;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Document\Document;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Sharing\Exception\ShareNotFoundException;
use App\Domain\Sharing\Exception\ShareNotificationUnavailableException;
use App\Domain\Sharing\Share;
use App\Domain\Sharing\ShareAuthorizationServiceInterface;
use App\Domain\Sharing\ShareId;
use App\Domain\Sharing\ShareRepositoryInterface;

final readonly class SendShareNotification
{
    public function __construct(
        private ShareRepositoryInterface $shareRepository,
        private DocumentRepositoryInterface $documentRepository,
        private ClientRepositoryInterface $clientRepository,
        private ShareAuthorizationServiceInterface $authService,
        private ShareNotifierInterface $notifier,
        private string $baseUrl,
    ) {}

    public function execute(ShareId $shareId, UserId $currentUserId): void
    {
        $share = $this->shareRepository->findById($shareId);

        if (! $share instanceof Share) {
            throw new ShareNotFoundException($shareId);
        }

        if (! $this->authService->canDeleteShare($currentUserId, $share)) {
            throw new UnauthorizedException('notify recipient for this share');
        }

        $document = $this->documentRepository->findById($share->documentId);

        if (! $document instanceof Document || !$document->clientId instanceof ClientId) {
            throw new ShareNotificationUnavailableException;
        }

        $client = $this->clientRepository->findById($document->clientId);

        if (! $client instanceof Client || $client->email === null) {
            throw new ShareNotificationUnavailableException;
        }

        $this->notifier->notify(
            recipientEmail: $client->email,
            recipientName: $client->nom,
            documentName: $document->name,
            shareUrl: $share->shareUrl($this->baseUrl),
        );
    }
}
