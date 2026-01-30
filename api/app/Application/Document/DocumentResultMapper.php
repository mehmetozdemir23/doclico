<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Document\Document;

final class DocumentResultMapper
{
    public static function toResult(Document $document): DocumentResult
    {
        $template = null;
        if ($document->templateName !== null) {
            $template = new DocumentTemplateResult(
                id: $document->templateId->value,
                name: $document->templateName,
                type: $document->templateType ?? '',
            );
        }

        return new DocumentResult(
            id: $document->id->value,
            name: $document->name,
            templateId: $document->templateId->value,
            data: $document->data,
            createdAt: $document->createdAt?->format('c'),
            template: $template,
        );
    }

    /** @param Document[] $documents */
    public static function toListResult(array $documents): DocumentListResult
    {
        return new DocumentListResult(
            documents: array_map(
                self::toResult(...),
                $documents
            )
        );
    }
}
