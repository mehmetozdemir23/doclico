<?php

namespace App\Queue\Jobs;

use App\Application\FileGeneration\ProcessFileGeneration;
use App\Domain\FileGeneration\FileGenerationId;
use App\Infrastructure\Broadcasting\FileGenerationCompletedBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessFileGenerationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $fileGenerationId
    ) {}

    public function handle(ProcessFileGeneration $processFileGeneration): void
    {
        $fileGeneration = $processFileGeneration->execute(
            FileGenerationId::fromString($this->fileGenerationId)
        );

        broadcast(new FileGenerationCompletedBroadcast($fileGeneration));
    }
}
