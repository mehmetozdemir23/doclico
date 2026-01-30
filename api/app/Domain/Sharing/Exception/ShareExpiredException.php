<?php

declare(strict_types=1);

namespace App\Domain\Sharing\Exception;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Sharing\ShareToken;

final class ShareExpiredException extends DomainException
{
    public function __construct(ShareToken $token)
    {
        parent::__construct("Share link has expired: {$token->value}");
    }
}
