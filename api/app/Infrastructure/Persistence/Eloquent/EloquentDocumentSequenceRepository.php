<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\DocumentSequence\DocumentSequenceRepositoryInterface;
use App\Domain\Identity\UserId;
use Illuminate\Support\Facades\DB;

final class EloquentDocumentSequenceRepository implements DocumentSequenceRepositoryInterface
{
    public function peek(UserId $userId, string $type, int $year): int
    {
        $seq = DocumentSequenceModel::where([
            'user_id' => $userId->value,
            'type' => $type,
            'year' => $year,
        ])->first();

        return ($seq?->last_number ?? 0) + 1;
    }

    public function increment(UserId $userId, string $type, int $year): int
    {
        return DB::transaction(function () use ($userId, $type, $year): int {
            $seq = DocumentSequenceModel::lockForUpdate()->firstOrCreate(
                ['user_id' => $userId->value, 'type' => $type, 'year' => $year],
                ['last_number' => 0],
            );

            $seq->increment('last_number');

            return $seq->last_number;
        });
    }
}
