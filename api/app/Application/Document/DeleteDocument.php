<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentAuthorizationServiceInterface;
use App\Domain\Document\DocumentId;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Document\Exception\DocumentDeletionForbiddenException;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Template\Template;
use App\Domain\Template\TemplateRepositoryInterface;

final readonly class DeleteDocument
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
        private TemplateRepositoryInterface $templateRepository,
        private DocumentAuthorizationServiceInterface $authService,
    ) {}

    public function execute(DocumentId $documentId, UserId $currentUserId): void
    {
        $document = $this->documentRepository->findById($documentId);

        if (! $document instanceof Document) {
            throw new DocumentNotFoundException($documentId);
        }

        if (! $this->authService->canDelete($currentUserId, $document)) {
            throw new UnauthorizedException('delete this document');
        }

        $template = $this->templateRepository->findById($document->templateId);

        if ($template instanceof Template && ! $template->isDeletable()) {
            throw new DocumentDeletionForbiddenException;
        }

        $this->documentRepository->delete($documentId);
    }
}
