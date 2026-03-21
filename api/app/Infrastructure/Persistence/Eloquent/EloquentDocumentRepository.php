<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentId;
use App\Domain\Document\DocumentQuery;
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

    public function findByUserId(UserId $userId, DocumentQuery $query = new DocumentQuery): array
    {
        $column = match ($query->sortBy) {
            'name' => 'name',
            default => 'created_at',
        };

        return DocumentModel::where('user_id', $userId->value)
            ->when($query->templateTypes, fn ($q) => $q->whereHas(
                'template', fn ($t) => $t->whereIn('type', $query->templateTypes)
            ))
            ->when($query->clientId, fn ($q) => $q->where('client_id', $query->clientId))
            ->orderBy($column, $query->sortDir)
            ->offset(($query->page - 1) * $query->perPage)
            ->limit($query->perPage)
            ->get()
            ->map(fn (DocumentModel $model): Document => DocumentMapper::toDomain($model))
            ->all();
    }

    public function countByUserId(UserId $userId, DocumentQuery $query = new DocumentQuery): int
    {
        return DocumentModel::where('user_id', $userId->value)
            ->when($query->templateTypes, fn ($q) => $q->whereHas(
                'template', fn ($t) => $t->whereIn('type', $query->templateTypes)
            ))
            ->when($query->clientId, fn ($q) => $q->where('client_id', $query->clientId))
            ->count();
    }

    public function delete(DocumentId $id): void
    {
        DocumentModel::destroy($id->value);
    }
}
