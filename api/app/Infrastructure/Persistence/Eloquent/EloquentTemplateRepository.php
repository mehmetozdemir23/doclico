<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Template\Template;
use App\Domain\Template\TemplateId;
use App\Domain\Template\TemplateRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\TemplateMapper;

final class EloquentTemplateRepository implements TemplateRepositoryInterface
{
    public function findById(TemplateId $id): ?Template
    {
        $model = TemplateModel::find($id->value);

        return $model ? TemplateMapper::toDomain($model) : null;
    }

    public function findByType(string $type): ?Template
    {
        $model = TemplateModel::where('type', $type)->first();

        return $model ? TemplateMapper::toDomain($model) : null;
    }

    public function findAll(): array
    {
        return TemplateModel::all()
            ->map(fn (TemplateModel $model): Template => TemplateMapper::toDomain($model))
            ->all();
    }

    public function findByIds(array $ids): array
    {
        $intIds = array_map(fn (TemplateId $id): int => $id->value, $ids);

        return TemplateModel::whereIn('id', $intIds)
            ->get()
            ->map(fn (TemplateModel $model): Template => TemplateMapper::toDomain($model))
            ->all();
    }
}
