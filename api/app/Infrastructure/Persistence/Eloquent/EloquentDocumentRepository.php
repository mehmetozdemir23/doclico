<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentId;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Mapper\DocumentMapper;

final class EloquentDocumentRepository implements DocumentRepositoryInterface
{
    public function save(Document $document): Document
    {
        $model = DocumentMapper::toModel($document);
        $model->save();

        return DocumentMapper::toDomain($model);
    }

    public function findById(DocumentId $id): ?Document
    {
        $model = DocumentModel::find($id->value);

        return $model ? DocumentMapper::toDomain($model) : null;
    }

    public function findByUserId(UserId $userId): array
    {
        return DocumentModel::where('user_id', $userId->value)
            ->latest()
            ->get()
            ->map(fn (DocumentModel $model): Document => DocumentMapper::toDomain($model))
            ->all();
    }

    public function delete(DocumentId $id): void
    {
        DocumentModel::destroy($id->value);
    }
}
