<?php

declare(strict_types=1);

namespace App\Domain\Sharing\Exception;

use App\Domain\Shared\Exception\DomainException;

final class InvalidExpirationPeriodException extends DomainException
{
    public function __construct(string $period)
    {
        parent::__construct("Invalid expiration period: {$period}");
    }
}
