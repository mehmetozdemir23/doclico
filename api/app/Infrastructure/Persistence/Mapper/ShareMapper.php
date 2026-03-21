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
            sharedAt: $model->shared_at?->toDateTimeImmutable() ?? new DateTimeImmutable,
            downloadsCount: $model->downloads_count ?? 0,
            lastDownloadedAt: $model->last_downloaded_at?->toDateTimeImmutable(),
            remindedAt: $model->reminded_at?->toDateTimeImmutable(),
            viewsCount: $model->views_count ?? 0,
            firstViewedAt: $model->first_viewed_at?->toDateTimeImmutable(),
        );
    }

    public static function toModel(Share $entity): ShareModel
    {
        $model = new ShareModel;
        $model->id = $entity->id->value;
        $model->document_id = $entity->documentId->value;
        $model->token = $entity->token->value;
        $model->expires_at = $entity->expiresAt;
        $model->shared_at = $entity->sharedAt;
        $model->downloads_count = $entity->downloadsCount();
        $model->last_downloaded_at = $entity->lastDownloadedAt();
        $model->views_count = $entity->viewsCount();
        $model->first_viewed_at = $entity->firstViewedAt();

        return $model;
    }

    public static function updateModel(ShareModel $model, Share $entity): void
    {
        $model->downloads_count = $entity->downloadsCount();
        $model->last_downloaded_at = $entity->lastDownloadedAt();
        $model->reminded_at = $entity->remindedAt();
        $model->views_count = $entity->viewsCount();
        $model->first_viewed_at = $entity->firstViewedAt();
    }
}
