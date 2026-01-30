<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Identity\UserId;
use App\Domain\Template\TemplateRepositoryInterface;

final readonly class GetUserDocuments
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
        private TemplateRepositoryInterface $templateRepository,
    ) {}

    public function execute(UserId $userId): DocumentListResult
    {
        $documents = $this->documentRepository->findByUserId($userId);

        if (empty($documents)) {
            return new DocumentListResult([]);
        }

        $templateIds = array_unique(
            array_map(fn ($doc) => $doc->templateId, $documents)
        );
        $templates = $this->templateRepository->findByIds($templateIds);

        return DocumentResultMapper::toListResult($documents, $templates);
    }
}
