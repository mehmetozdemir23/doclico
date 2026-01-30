<?php

declare(strict_types=1);

namespace App\Application\Sharing;

final readonly class ShareListResult
{
    /** @param ShareResult[] $shares */
    public function __construct(
        public array $shares,
    ) {}
}
