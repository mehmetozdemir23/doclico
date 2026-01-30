<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Application\Document\DocumentResult;
use App\Application\Template\TemplateResult;

final readonly class SharedDocumentResult
{
    public function __construct(
        public ShareResult $share,
        public DocumentResult $document,
        public TemplateResult $template,
    ) {}
}
