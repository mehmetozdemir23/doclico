<?php

declare(strict_types=1);

namespace App\Application\Document;

use App\Domain\Identity\UserId;
use App\Domain\Template\TemplateId;

final readonly class CreateDocumentData
{
    public function __construct(
        public TemplateId $templateId,
        public UserId $userId,
        public ?string $name,
        public array $data,
        public ?string $clientId = null,
    ) {}
}
