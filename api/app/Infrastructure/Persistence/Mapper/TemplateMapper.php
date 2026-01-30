<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Template\Template;
use App\Domain\Template\TemplateId;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;

final class TemplateMapper
{
    public static function toDomain(TemplateModel $model): Template
    {
        return new Template(
            id: TemplateId::fromInt($model->id),
            type: $model->type,
            name: $model->name,
            category: $model->category,
            icon: $model->icon,
            fields: $model->fields ?? [],
            popular: $model->popular,
        );
    }

    public static function toModel(Template $entity): TemplateModel
    {
        $model = new TemplateModel;
        $model->id = $entity->id->value;
        $model->type = $entity->type;
        $model->name = $entity->name;
        $model->category = $entity->category;
        $model->icon = $entity->icon;
        $model->fields = $entity->fields;
        $model->popular = $entity->popular;

        return $model;
    }
}
