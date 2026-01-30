<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Document\DocumentId;
use App\Domain\Sharing\Share;
use App\Domain\Sharing\ShareId;
use App\Domain\Sharing\ShareToken;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use DateTimeImmutable;

final class ShareMapper
{
    public static function toDomain(ShareModel $model): Share
    {
        return new Share(
            id: ShareId::fromString($model->id),
            documentId: DocumentId::fromString($model->document_id),
            token: ShareToken::fromString($model->token),
            expiresAt: $model->expires_at?->toDateTimeImmutable(),
            downloadsCount: $model->downloads_count ?? 0,
            lastDownloadedAt: $model->last_downloaded_at
            ? new DateTimeImmutable($model->last_downloaded_at)
            : null,
        );
    }

    public static function toModel(Share $entity): ShareModel
    {
        $model = new ShareModel;
        $model->id = $entity->id->value;
        $model->document_id = $entity->documentId->value;
        $model->token = $entity->token->value;
        $model->expires_at = $entity->expiresAt;
        $model->downloads_count = $entity->downloadsCount();
        $model->last_downloaded_at = $entity->lastDownloadedAt();

        return $model;
    }

    public static function updateModel(ShareModel $model, Share $entity): void
    {
        $model->downloads_count = $entity->downloadsCount();
        $model->last_downloaded_at = $entity->lastDownloadedAt();
    }
}
