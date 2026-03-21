<?php

declare(strict_types=1);

namespace App\Domain\Client\Exception;

use App\Domain\Client\ClientId;
use App\Domain\Shared\Exception\EntityNotFoundException;

final class ClientNotFoundException extends EntityNotFoundException
{
    public function __construct(ClientId $id)
    {
        parent::__construct($id, 'Client');
    }
}
