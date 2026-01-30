<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use Stringable;

interface IdInterface extends Stringable
{
    public function value(): string|int;

    public function equals(self $other): bool;
}
