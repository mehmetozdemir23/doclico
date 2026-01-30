<?php

namespace App\Http\Controllers\Api;

use App\Application\FileGeneration\CreateFileGeneration;
use App\Application\FileGeneration\CreateFileGenerationData;
use App\Domain\FileGeneration\FileFormat;
use App\Domain\Identity\UserId;
use App\Domain\Template\TemplateId;
use App\Http\Controllers\Controller;
use App\Queue\Jobs\ProcessFileGenerationJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class CreateFileGenerationController extends Controller
{
    public function __construct(
        private readonly CreateFileGeneration $createFileGeneration
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'template_id' => 'required|integer|exists:templates,id',
            'data' => 'present|array',
            'format' => ['nullable', 'string', Rule::enum(FileFormat::class)],
        ]);

        $format = FileFormat::tryFrom($validated['format'] ?? 'pdf') ?? FileFormat::Pdf;

        $data = new CreateFileGenerationData(
            templateId: TemplateId::fromInt($validated['template_id']),
            userId: $request->user() ? UserId::fromString($request->user()->id) : null,
            data: $validated['data'],
            format: $format,
        );

        try {
            $result = $this->createFileGeneration->execute($data);

            ProcessFileGenerationJob::dispatch($result->id);

            return response()->json([
                'id' => $result->id,
                'status' => $result->status,
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
