<?php

declare(strict_types=1);

namespace App\Domain\Document;

use App\Domain\Identity\UserId;
use App\Domain\Template\Template;
use App\Domain\Template\TemplateId;
use DateTimeImmutable;

final readonly class Document
{
    public function __construct(
        public DocumentId $id,
        public UserId $userId,
        public TemplateId $templateId,
        public string $name,
        public array $data,
        public ?DateTimeImmutable $createdAt = null,
        public ?string $templateName = null,
        public ?string $templateType = null,
    ) {}

    public static function createFromTemplate(
        DocumentId $id,
        UserId $userId,
        Template $template,
        ?string $name,
        array $data,
    ): self {
        if ($name === null || $name === '') {
            $name = sprintf(
                '%s - %s',
                $template->name(),
                (new DateTimeImmutable)->format('Y-m-d H:i')
            );
        }

        return new self(
            id: $id,
            userId: $userId,
            templateId: $template->id,
            name: $name,
            data: $data,
        );
    }
}
