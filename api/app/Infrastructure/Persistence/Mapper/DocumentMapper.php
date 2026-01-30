<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentId;
use App\Domain\Identity\UserId;
use App\Domain\Template\TemplateId;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use DateTimeImmutable;

final class DocumentMapper
{
    public static function toDomain(DocumentModel $model): Document
    {
        return new Document(
            id: DocumentId::fromString($model->id),
            userId: UserId::fromString($model->user_id),
            templateId: TemplateId::fromInt($model->template_id),
            name: $model->name,
            data: $model->data ?? [],
            createdAt: $model->created_at ? new DateTimeImmutable($model->created_at->toDateTimeString()) : null,
            templateName: $model->relationLoaded('template') ? $model->template?->name : null,
            templateType: $model->relationLoaded('template') ? $model->template?->type : null,
        );
    }

    public static function toModel(Document $entity): DocumentModel
    {
        $model = new DocumentModel;
        $model->id = $entity->id->value;
        $model->user_id = $entity->userId->value;
        $model->template_id = $entity->templateId->value;
        $model->name = $entity->name;
        $model->data = $entity->data;

        return $model;
    }
}
