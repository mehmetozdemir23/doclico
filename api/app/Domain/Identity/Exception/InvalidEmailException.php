<?php

declare(strict_types=1);

namespace App\Domain\Identity\Exception;

use App\Domain\Shared\Exception\DomainException;

final class InvalidEmailException extends DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("Invalid email format: {$email}");
    }
}
