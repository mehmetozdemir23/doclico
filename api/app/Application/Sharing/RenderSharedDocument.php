<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Application\Document\LogoResolver;
use App\Application\FileGeneration\RendererFactoryInterface;
use App\Domain\Document\Document;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\FileGeneration\FileFormat;
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
use InvalidArgumentException;

final readonly class RenderSharedDocument
{
    public function __construct(
        private ShareRepositoryInterface $shareRepository,
        private DocumentRepositoryInterface $documentRepository,
        private TemplateRepositoryInterface $templateRepository,
        private UserRepositoryInterface $userRepository,
        private RendererFactoryInterface $rendererFactory,
    ) {}

    public function execute(string $token, FileFormat $format): RenderSharedDocumentResult
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

        $share->recordDownload();
        $this->shareRepository->update($share);

        $document = $this->documentRepository->findById($share->documentId);

        if (! $document instanceof Document) {
            throw new DocumentNotFoundException($share->documentId);
        }

        $template = $this->templateRepository->findById($document->templateId);

        if (! $template instanceof Template) {
            throw new TemplateNotFoundException($document->templateId);
        }

        $owner = $this->userRepository->findById($document->userId);
        $logoSrc = LogoResolver::fileUrl($owner instanceof User ? $owner : null);
        $data = $logoSrc !== null
            ? array_merge($document->data, ['logo' => $logoSrc])
            : $document->data;

        $renderer = $this->rendererFactory->make($format);
        $content = $renderer->render($template, $data);

        return new RenderSharedDocumentResult(
            content: $content,
            mimeType: $format->mimeType(),
            filename: "{$document->name}.{$renderer->extension()}",
        );
    }
}
