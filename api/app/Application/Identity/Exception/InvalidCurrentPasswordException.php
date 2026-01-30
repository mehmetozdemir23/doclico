<?php

declare(strict_types=1);

namespace App\Application\Identity\Exception;

use DomainException;

final class InvalidCurrentPasswordException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Le mot de passe actuel est incorrect');
    }
}
