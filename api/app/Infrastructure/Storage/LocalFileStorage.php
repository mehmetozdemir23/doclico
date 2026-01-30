<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage;

use App\Application\FileGeneration\FileStorageInterface;
use Illuminate\Support\Facades\Storage;

final class LocalFileStorage implements FileStorageInterface
{
    public function put(string $path, string $content): void
    {
        Storage::put($path, $content);
    }

    public function exists(string $path): bool
    {
        return Storage::exists($path);
    }

    public function get(string $path): ?string
    {
        return Storage::get($path);
    }

    public function delete(string $path): void
    {
        Storage::delete($path);
    }
}
