<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Client\Client;
use App\Domain\Client\ClientId;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Document\Document;
use App\Domain\Document\DocumentId;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\DocumentSequence\DocumentNumberFormatter;
use App\Domain\DocumentSequence\DocumentSequenceRepositoryInterface;
use App\Domain\Template\Exception\TemplateNotFoundException;
use App\Domain\Template\Exception\TemplateValidationException;
use App\Domain\Template\Template;
use App\Domain\Template\TemplateRepositoryInterface;

final readonly class CreateDocument
{
    public function __construct(
        private DocumentRepositoryInterface $documentRepository,
        private TemplateRepositoryInterface $templateRepository,
        private DocumentSequenceRepositoryInterface $sequenceRepository,
        private ClientRepositoryInterface $clientRepository,
    ) {}

    public function execute(CreateDocumentData $data): DocumentResult
    {
        $template = $this->templateRepository->findById($data->templateId);

        if (! $template instanceof Template) {
            throw new TemplateNotFoundException($data->templateId);
        }

        $documentData = $data->data;

        $clientId = null;
        if ($data->clientId !== null) {
            $clientId = ClientId::fromString($data->clientId);
            $client = $this->clientRepository->findById($clientId);
            if ($client instanceof Client) {
                $documentData['client_nom'] = $client->nom;
                $documentData['client_adresse'] = $client->adresse ?? '';
                $documentData['destinataire_societe'] = $client->nom;
                $documentData['destinataire_adresse'] = $client->adresse ?? '';
            }
        }

        if (DocumentNumberFormatter::isSequential($template->type)) {
            $year = (int) now()->format('Y');
            $number = $this->sequenceRepository->increment($data->userId, $template->type, $year);
            $numberField = DocumentNumberFormatter::NUMBER_FIELDS[$template->type];
            $documentData[$numberField] = DocumentNumberFormatter::format($template->type, $year, $number);
        }

        $errors = $template->validateData($documentData);
        if ($errors !== []) {
            throw new TemplateValidationException($errors);
        }

        $document = Document::createFromTemplate(
            id: DocumentId::generate(),
            userId: $data->userId,
            template: $template,
            name: $data->name,
            data: $documentData,
            clientId: $clientId,
        );

        $this->documentRepository->save($document);

        return DocumentResultMapper::toResult($document);
    }
}
