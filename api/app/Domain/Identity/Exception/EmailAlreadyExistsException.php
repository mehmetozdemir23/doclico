<?php

declare(strict_types=1);

namespace App\Domain\Identity\Exception;

use App\Domain\Shared\Exception\DomainException;

final class EmailAlreadyExistsException extends DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("Email {$email} is already in use");
    }
}
