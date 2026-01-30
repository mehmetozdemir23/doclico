<?php

namespace App\Http\Controllers\Api;

use App\Application\FileGeneration\FileStorageInterface;
use App\Application\FileGeneration\GetFileGenerationForDownload;
use App\Domain\FileGeneration\FileGenerationId;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileGenerationDownloadController extends Controller
{
    public function __construct(
        private readonly GetFileGenerationForDownload $getFileGeneration,
        private readonly FileStorageInterface $fileStorage,
    ) {}

    public function __invoke(string $fileGenerationId): StreamedResponse
    {
        $result = $this->getFileGeneration->execute(
            FileGenerationId::fromString($fileGenerationId)
        );

        if ($result->filePath === null || ! $this->fileStorage->exists($result->filePath)) {
            abort(404, 'File not found.');
        }

        return $this->fileStorage->download($result->filePath);
    }
}
