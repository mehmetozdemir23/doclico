<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Domain\Document\DocumentId;
use App\Domain\Identity\UserId;

final readonly class CreateShareData
{
    public function __construct(
        public DocumentId $documentId,
        public string $expiresIn,
        public UserId $currentUserId,
    ) {}
}
