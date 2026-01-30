<?php

declare(strict_types=1);

namespace App\Domain\Identity\Exception;

use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\EntityNotFoundException;

final class UserNotFoundException extends EntityNotFoundException
{
    public function __construct(UserId $id)
    {
        parent::__construct($id, 'User');
    }
}
