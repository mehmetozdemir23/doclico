<?php

namespace App\Http\Controllers\Api;

use App\Application\Document\CreateDocument;
use App\Application\Document\CreateDocumentData;
use App\Application\Document\DeleteDocument;
use App\Application\Document\GetDocument;
use App\Application\Document\GetUserDocuments;
use App\Domain\Document\DocumentId;
use App\Domain\Identity\UserId;
use App\Domain\Template\TemplateId;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(
        private readonly GetUserDocuments $getUserDocuments,
        private readonly GetDocument $getDocument,
        private readonly CreateDocument $createDocument,
        private readonly DeleteDocument $deleteDocument,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->getUserDocuments->execute(
            UserId::fromString($request->user()->id)
        );

        return response()->json($result->documents);
    }

    public function show(Request $request, string $documentId): JsonResponse
    {
        $currentUserId = UserId::fromString($request->user()->id);

        $result = $this->getDocument->execute(
            DocumentId::fromString($documentId),
            $currentUserId
        );

        return response()->json($result);
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $data = new CreateDocumentData(
            templateId: TemplateId::fromInt($validated['template_id']),
            userId: UserId::fromString($request->user()->id),
            name: $validated['name'] ?? null,
            data: $validated['data'],
        );

        $result = $this->createDocument->execute($data);

        return response()->json([
            'message' => 'Document créé avec succès',
            'document' => $result,
        ], 201);
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
