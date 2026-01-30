<?php

declare(strict_types=1);

namespace App\Domain\Sharing\Exception;

use App\Domain\Shared\Exception\DomainException;

final class InvalidShareTokenException extends DomainException
{
    public function __construct(string $token)
    {
        parent::__construct("Invalid share token: {$token}");
    }
}
