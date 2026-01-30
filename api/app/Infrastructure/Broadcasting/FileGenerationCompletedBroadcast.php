<?php

namespace App\Infrastructure\Broadcasting;

use App\Domain\FileGeneration\FileGeneration;
use App\Domain\Identity\UserId;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileGenerationCompletedBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private FileGeneration $fileGeneration
    ) {}

    /** @return array<int, Channel> */
    public function broadcastOn(): array
    {
        if ($this->fileGeneration->userId instanceof UserId) {
            return [
                new PrivateChannel("user.{$this->fileGeneration->userId}"),
            ];
        }

        return [
            new Channel("file-generation.{$this->fileGeneration->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'file-generation.completed';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->fileGeneration->id->value,
            'status' => $this->fileGeneration->status()->value,
            'file_path' => $this->fileGeneration->filePath(),
            'download_url' => $this->fileGeneration->isCompleted()
                ? route('file-generations.download', $this->fileGeneration->id->value)
                : null,
            'error' => $this->fileGeneration->error(),
        ];
    }
}
