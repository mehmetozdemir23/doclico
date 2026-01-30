<?php

declare(strict_types=1);

namespace App\Domain\Sharing\Exception;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\Sharing\ShareId;

final class ShareNotFoundException extends DomainException
{
    public function __construct(ShareId|string $identifier)
    {
        $value = $identifier instanceof ShareId ? $identifier->value : $identifier;
        parent::__construct("Share not found: {$value}");
    }
}
