<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Sharing\Exception\InvalidExpirationPeriodException;
use App\Domain\Sharing\Share;
use App\Domain\Sharing\ShareAuthorizationServiceInterface;
use App\Domain\Sharing\ShareId;
use App\Domain\Sharing\ShareRepositoryInterface;
use App\Domain\Sharing\ShareToken;
use DateTimeImmutable;

final readonly class CreateShare
{
    public function __construct(
        private ShareRepositoryInterface $shareRepository,
        private DocumentRepositoryInterface $documentRepository,
        private ShareAuthorizationServiceInterface $authService,
        private string $baseUrl,
    ) {}

    public function execute(CreateShareData $data): ShareResult
    {
        $document = $this->documentRepository->findById($data->documentId);

        if (! $document instanceof Document) {
            throw new DocumentNotFoundException($data->documentId);
        }

        if (! $this->authService->canCreateShare($data->currentUserId, $document)) {
            throw new UnauthorizedException('create share for this document');
        }

        $expiresAt = $this->calculateExpiresAt($data->expiresIn);

        $share = new Share(
            id: ShareId::generate(),
            documentId: $document->id,
            token: ShareToken::generate(),
            expiresAt: $expiresAt,
        );

        $share = $this->shareRepository->save($share);

        return ShareResultMapper::toResult($share, $this->baseUrl);
    }

    private function calculateExpiresAt(string $expiresIn): ?DateTimeImmutable
    {
        $now = new DateTimeImmutable;

        return match ($expiresIn) {
            '24h' => $now->modify('+24 hours'),
            '7d' => $now->modify('+7 days'),
            '30d' => $now->modify('+30 days'),
            'never' => null,
            default => throw new InvalidExpirationPeriodException($expiresIn),
        };
    }
}
