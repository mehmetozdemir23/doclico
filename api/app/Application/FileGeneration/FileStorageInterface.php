<?php

declare(strict_types=1);

namespace App\Application\FileGeneration;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileStorageInterface
{
    public function put(string $path, string $content): void;

    public function exists(string $path): bool;

    public function get(string $path): ?string;

    public function delete(string $path): void;

    public function download(string $path): StreamedResponse;
}
