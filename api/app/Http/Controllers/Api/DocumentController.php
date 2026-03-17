<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Application\Document\CreateDocument;
use App\Application\Document\CreateDocumentData;
use App\Application\Document\DeleteDocument;
use App\Application\Document\DownloadDocument;
use App\Application\Document\GetUserDocuments;
use App\Application\Document\PreviewDocument;
use App\Domain\Document\DocumentId;
use App\Domain\Document\DocumentQuery;
use App\Domain\FileGeneration\FileFormat;
use App\Domain\Identity\UserId;
use App\Domain\Template\Exception\TemplateValidationException;
use App\Domain\Template\TemplateId;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DocumentController extends Controller
{
    public function __construct(
        private readonly GetUserDocuments $getUserDocuments,
        private readonly CreateDocument $createDocument,
        private readonly DeleteDocument $deleteDocument,
        private readonly PreviewDocument $previewDocument,
        private readonly DownloadDocument $downloadDocument,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query('page', 1));
        $sortBy = in_array($request->query('sort_by'), ['name', 'createdAt'], true)
            ? $request->query('sort_by')
            : 'createdAt';
        $sortDir = $request->query('sort_dir') === 'asc' ? 'asc' : 'desc';
        $templateTypes = array_values(array_filter((array) $request->query('type', []), is_string(...)));
        $clientId = is_string($request->query('client_id')) ? $request->query('client_id') : null;

        $query = new DocumentQuery(page: $page, sortBy: $sortBy, sortDir: $sortDir, templateTypes: $templateTypes, clientId: $clientId);

        $result = $this->getUserDocuments->execute(
            UserId::fromString($request->user()->id),
            $query,
        );

        return response()->json([
            'data' => $result->documents,
            'meta' => [
                'total' => $result->total,
                'page' => $result->page,
                'per_page' => $result->perPage,
                'last_page' => (int) ceil($result->total / $result->perPage),
            ],
        ]);
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $data = new CreateDocumentData(
            templateId: TemplateId::fromInt($validated['template_id']),
            userId: UserId::fromString($request->user()->id),
            name: $validated['name'] ?? null,
            data: $validated['data'],
            clientId: $validated['client_id'] ?? null,
        );

        try {
            $result = $this->createDocument->execute($data);
        } catch (TemplateValidationException $e) {
            return response()->json(['errors' => $e->errors], 422);
        }

        return response()->json([
            'message' => 'Document créé avec succès',
            'document' => $result,
        ], 201);
    }

    public function preview(Request $request, string $documentId): Response
    {
        $currentUserId = UserId::fromString($request->user()->id);

        $pdfContent = $this->previewDocument->execute(
            DocumentId::fromString($documentId),
            $currentUserId
        );

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
        ]);
    }

    public function download(Request $request, string $documentId): Response
    {
        $format = FileFormat::tryFrom($request->query('format', 'pdf')) ?? FileFormat::Pdf;
        $currentUserId = UserId::fromString($request->user()->id);

        $result = $this->downloadDocument->execute(
            DocumentId::fromString($documentId),
            $currentUserId,
            $format,
        );

        return response($result->content, 200, [
            'Content-Type' => $result->format->mimeType(),
            'Content-Disposition' => 'attachment; filename="'.$result->filename.'"',
        ]);
    }

    public function destroy(Request $request, string $documentId): JsonResponse
    {
        $currentUserId = UserId::fromString($request->user()->id);

        $this->deleteDocument->execute(
            DocumentId::fromString($documentId),
            $currentUserId
        );

        return response()->json(['message' => 'Document supprimé avec succès']);
    }
}
