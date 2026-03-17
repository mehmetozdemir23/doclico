<?php

declare(strict_types=1);

namespace App\Domain\DocumentSequence;

use App\Domain\Identity\UserId;

interface DocumentSequenceRepositoryInterface
{
    public function peek(UserId $userId, string $type, int $year): int;

    public function increment(UserId $userId, string $type, int $year): int;
}
