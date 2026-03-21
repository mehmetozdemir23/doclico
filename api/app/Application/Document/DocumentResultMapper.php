<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Client\Client;
use App\Domain\Client\ClientId;
use App\Domain\Document\Document;
use App\Domain\Sharing\Share;
use App\Domain\Template\Template;

final class DocumentResultMapper
{
    public static function toResult(Document $document, ?Template $template = null, ?Client $client = null, ?Share $share = null): DocumentResult
    {
        $templateResult = null;
        if ($template instanceof Template) {
            $templateResult = new DocumentTemplateResult(
                id: $template->id->value,
                name: $template->name,
                type: $template->type,
            );
        }

        $clientResult = null;
        if ($client instanceof Client) {
            $clientResult = new DocumentClientResult(
                id: $client->id->value,
                nom: $client->nom,
                email: $client->email,
            );
        }

        $shareResult = null;
        if ($share instanceof Share) {
            $shareResult = new DocumentShareResult(
                viewsCount: $share->viewsCount(),
                downloadsCount: $share->downloadsCount(),
                isExpired: $share->isExpired(),
                expiresAt: $share->expiresAt?->format('c'),
            );
        }

        return new DocumentResult(
            id: $document->id->value,
            name: $document->name,
            templateId: $document->templateId->value,
            data: $document->data,
            generatedAt: $document->generatedAt->format('c'),
            template: $templateResult,
            client: $clientResult,
            share: $shareResult,
        );
    }

    /**
     * @param  Document[]  $documents
     * @param  Template[]  $templates
     * @param  Client[]  $clients
     * @param  array<string, Share>  $shares  keyed by document ID
     */
    public static function toListResult(array $documents, array $templates, int $total = 0, int $page = 1, int $perPage = 20, array $clients = [], array $shares = []): DocumentListResult
    {
        $templatesById = [];
        foreach ($templates as $template) {
            $templatesById[$template->id->value] = $template;
        }

        $clientsById = [];
        foreach ($clients as $client) {
            $clientsById[$client->id->value] = $client;
        }

        return new DocumentListResult(
            documents: array_map(
                fn (Document $doc): DocumentResult => self::toResult(
                    $doc,
                    $templatesById[$doc->templateId->value] ?? null,
                    $doc->clientId instanceof ClientId ? ($clientsById[$doc->clientId->value] ?? null) : null,
                    $shares[$doc->id->value] ?? null,
                ),
                $documents
            ),
            total: $total,
            page: $page,
            perPage: $perPage,
        );
    }
}
