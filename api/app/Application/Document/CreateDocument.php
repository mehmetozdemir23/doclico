<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentId;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Template\Exception\TemplateNotFoundException;
use App\Domain\Template\Template;
use App\Domain\Template\TemplateRepositoryInterface;

final readonly class CreateDocument
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
        private TemplateRepositoryInterface $templateRepository,
    ) {}

    public function execute(CreateDocumentData $data): DocumentResult
    {
        $template = $this->templateRepository->findById($data->templateId);

        if (! $template instanceof Template) {
            throw new TemplateNotFoundException($data->templateId);
        }

        $document = Document::createFromTemplate(
            id: DocumentId::generate(),
            userId: $data->userId,
            template: $template,
            name: $data->name,
            data: $data->data,
        );

        $this->documentRepository->save($document);

        return DocumentResultMapper::toResult($document);
    }
}
