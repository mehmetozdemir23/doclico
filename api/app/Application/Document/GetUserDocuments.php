<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Document\DocumentId;
use App\Domain\Client\ClientId;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Document\DocumentQuery;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Identity\UserId;
use App\Domain\Sharing\ShareRepositoryInterface;
use App\Domain\Template\TemplateId;
use App\Domain\Template\TemplateRepositoryInterface;

final readonly class GetUserDocuments
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
        private TemplateRepositoryInterface $templateRepository,
        private ClientRepositoryInterface $clientRepository,
        private ShareRepositoryInterface $shareRepository,
    ) {}

    public function execute(UserId $userId, DocumentQuery $query = new DocumentQuery): DocumentListResult
    {
        $total = $this->documentRepository->countByUserId($userId, $query);

        if ($total === 0) {
            return new DocumentListResult([], 0, $query->page, $query->perPage);
        }

        $documents = $this->documentRepository->findByUserId($userId, $query);

        if ($documents === []) {
            return new DocumentListResult([], $total, $query->page, $query->perPage);
        }

        $templateIds = array_unique(
            array_map(fn ($doc): TemplateId => $doc->templateId, $documents)
        );
        $templates = $this->templateRepository->findByIds($templateIds);

        $clientIds = array_values(array_unique(array_filter(
            array_map(fn ($doc): ?ClientId => $doc->clientId, $documents)
        )));
        $clients = $clientIds !== [] ? $this->clientRepository->findByIds($clientIds) : [];

        $documentIds = array_map(fn ($doc): DocumentId => $doc->id, $documents);
        $shares = $this->shareRepository->findLatestByDocumentIds($documentIds);

        return DocumentResultMapper::toListResult($documents, $templates, $total, $query->page, $query->perPage, $clients, $shares);
    }
}
