<?php

declare(strict_types=1);

namespace App\Application\Template;

use App\Domain\Template\Template;

final class TemplateResultMapper
{
    public static function toResult(Template $template): TemplateResult
    {
        return new TemplateResult(
            id: $template->id->value,
            type: $template->type,
            name: $template->name,
            category: $template->category,
            icon: $template->icon,
            fields: $template->fields,
            popular: $template->popular,
        );
    }

    /** @param Template[] $templates */
    public static function toListResult(array $templates): TemplateListResult
    {
        return new TemplateListResult(
            templates: array_map(
                self::toResult(...),
                $templates
            )
        );
    }
}
