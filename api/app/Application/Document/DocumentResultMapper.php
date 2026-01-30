<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Document\Document;
use App\Domain\Template\Template;

final class DocumentResultMapper
{
    public static function toResult(Document $document, ?Template $template = null): DocumentResult
    {
        $templateResult = null;
        if ($template !== null) {
            $templateResult = new DocumentTemplateResult(
                id: $template->id->value,
                name: $template->name,
                type: $template->type,
            );
        }

        return new DocumentResult(
            id: $document->id->value,
            name: $document->name,
            templateId: $document->templateId->value,
            data: $document->data,
            template: $templateResult,
        );
    }

    /**
     * @param Document[] $documents
     * @param Template[] $templates
     */
    public static function toListResult(array $documents, array $templates): DocumentListResult
    {
        $templatesById = [];
        foreach ($templates as $template) {
            $templatesById[$template->id->value] = $template;
        }

        return new DocumentListResult(
            documents: array_map(
                fn (Document $doc) => self::toResult(
                    $doc,
                    $templatesById[$doc->templateId->value] ?? null
                ),
                $documents
            )
        );
    }
}
