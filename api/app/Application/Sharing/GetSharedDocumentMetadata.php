<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\Identity\User;
use App\Domain\Identity\UserRepositoryInterface;
use App\Domain\Sharing\Exception\InvalidShareTokenException;
use App\Domain\Sharing\Exception\ShareExpiredException;
use App\Domain\Sharing\Exception\ShareNotFoundException;
use App\Domain\Sharing\Share;
use App\Domain\Sharing\ShareRepositoryInterface;
use App\Domain\Sharing\ShareToken;
use App\Domain\Template\Exception\TemplateNotFoundException;
use App\Domain\Template\Template;
use App\Domain\Template\TemplateRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

final readonly class GetSharedDocumentMetadata
{
    public function __construct(
        private ShareRepositoryInterface $shareRepository,
        private DocumentRepositoryInterface $documentRepository,
        private TemplateRepositoryInterface $templateRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(string $token): SharedDocumentMetadata
    {
        try {
            $shareToken = ShareToken::fromString($token);
        } catch (InvalidArgumentException) {
            throw new InvalidShareTokenException($token);
        }

        $share = $this->shareRepository->findByToken($shareToken);

        if (! $share instanceof Share) {
            throw new ShareNotFoundException($token);
        }

        if ($share->isExpired()) {
            throw new ShareExpiredException($shareToken);
        }

        $document = $this->documentRepository->findById($share->documentId);

        if (! $document instanceof Document) {
            throw new DocumentNotFoundException($share->documentId);
        }

        $template = $this->templateRepository->findById($document->templateId);

        if (! $template instanceof Template) {
            throw new TemplateNotFoundException($document->templateId);
        }

        $user = $this->userRepository->findById($document->userId);

        return new SharedDocumentMetadata(
            documentName: $document->name,
            templateName: $template->name(),
            templateType: $template->type,
            emitter: $user?->fullName(),
            emitterCompany: $user instanceof User ? $user->companyName : null,
            emitterLogo: $user instanceof User && $user->logo
                ? Storage::disk('public')->url($user->logo)
                : null,
            expiresAt: $share->expiresAt?->format('c'),
        );
    }
}
