<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Document\DocumentId;
use App\Domain\Sharing\Share;
use App\Domain\Sharing\ShareId;
use App\Domain\Sharing\ShareRepositoryInterface;
use App\Domain\Sharing\ShareToken;
use App\Infrastructure\Persistence\Mapper\ShareMapper;

final class EloquentShareRepository implements ShareRepositoryInterface
{
    public function save(Share $share): Share
    {
        $model = ShareMapper::toModel($share);
        $model->save();

        return ShareMapper::toDomain($model);
    }

    public function findById(ShareId $id): ?Share
    {
        $model = ShareModel::find($id->value);

        return $model ? ShareMapper::toDomain($model) : null;
    }

    public function findByToken(ShareToken $token): ?Share
    {
        $model = ShareModel::where('token', $token->value)->first();

        return $model ? ShareMapper::toDomain($model) : null;
    }

    public function findByDocumentId(DocumentId $documentId): array
    {
        return ShareModel::where('document_id', $documentId->value)
            ->latest()
            ->get()
            ->map(fn (ShareModel $model): Share => ShareMapper::toDomain($model))
            ->all();
    }

    public function delete(ShareId $id): void
    {
        ShareModel::destroy($id->value);
    }

    public function update(Share $share): void
    {
        $model = ShareModel::find($share->id->value);

        if ($model) {
            ShareMapper::updateModel($model, $share);
            $model->save();
        }
    }
}
