<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Application\FileGeneration\RendererFactoryInterface;
use App\Domain\Document\Document;
use App\Domain\Document\DocumentAuthorizationServiceInterface;
use App\Domain\Document\DocumentId;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\FileGeneration\FileFormat;
use App\Domain\Identity\User;
use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Template\Exception\TemplateNotFoundException;
use App\Domain\Template\Template;
use App\Domain\Template\TemplateRepositoryInterface;

final readonly class DownloadDocument
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
        private DocumentAuthorizationServiceInterface $authService,
        private TemplateRepositoryInterface $templateRepository,
        private RendererFactoryInterface $rendererFactory,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(DocumentId $documentId, UserId $currentUserId, FileFormat $format): DownloadDocumentResult
    {
        $document = $this->documentRepository->findById($documentId);

        if (! $document instanceof Document) {
            throw new DocumentNotFoundException($documentId);
        }

        if (! $this->authService->canView($currentUserId, $document)) {
            throw new UnauthorizedException('download this document');
        }

        $template = $this->templateRepository->findById($document->templateId);

        if (! $template instanceof Template) {
            throw new TemplateNotFoundException($document->templateId);
        }

        $owner = $this->userRepository->findById($currentUserId);
        $logoSrc = LogoResolver::fileUrl($owner instanceof User ? $owner : null);
        $data = $logoSrc !== null
            ? array_merge($document->data, ['logo' => $logoSrc])
            : $document->data;

        $renderer = $this->rendererFactory->make($format);
        $content = $renderer->render($template, $data);

        return new DownloadDocumentResult(
            content: $content,
            format: $format,
            filename: $document->name.'.'.$format->extension(),
        );
    }
}
