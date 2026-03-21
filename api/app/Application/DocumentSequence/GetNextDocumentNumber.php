<?php

declare(strict_types=1);

namespace App\Application\DocumentSequence;

use App\Domain\DocumentSequence\DocumentNumberFormatter;
use App\Domain\DocumentSequence\DocumentSequenceRepositoryInterface;
use App\Domain\Identity\UserId;

final readonly class GetNextDocumentNumber
{
    public function __construct(
        private DocumentSequenceRepositoryInterface $sequenceRepository,
    ) {}

    public function execute(UserId $userId, string $type): string
    {
        $year = (int) now()->format('Y');
        $number = $this->sequenceRepository->peek($userId, $type, $year);

        return DocumentNumberFormatter::format($type, $year, $number);
    }
}
