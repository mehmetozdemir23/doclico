<?php

declare(strict_types=1);

namespace App\Domain\Template;

interface TemplateRepositoryInterface
{
    public function findById(TemplateId $id): ?Template;

    public function findByType(string $type): ?Template;

    /** @return Template[] */
    public function findAll(): array;
}
