<?php

namespace App\Http\Controllers\Api;

use App\Application\Sharing\CreateShare;
use App\Application\Sharing\CreateShareData;
use App\Application\Sharing\GetDocumentShares;
use App\Application\Sharing\RevokeShare;
use App\Domain\Document\DocumentId;
use App\Domain\Identity\UserId;
use App\Domain\Sharing\ShareId;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShareRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function __construct(
        private readonly GetDocumentShares $getDocumentShares,
        private readonly CreateShare $createShare,
        private readonly RevokeShare $revokeShare,
    ) {}

    public function index(Request $request, string $documentId): JsonResponse
    {
        $currentUserId = UserId::fromString($request->user()->id);

        $result = $this->getDocumentShares->execute(
            DocumentId::fromString($documentId),
            $currentUserId
        );

        return response()->json($result->shares);
    }

    public function store(StoreShareRequest $request, string $documentId): JsonResponse
    {
        $validated = $request->validated();

        $data = new CreateShareData(
            documentId: DocumentId::fromString($documentId),
            expiresIn: $validated['expires_in'],
            currentUserId: UserId::fromString($request->user()->id),
        );

        $result = $this->createShare->execute($data);

        return response()->json([
            'message' => 'Lien de partage créé avec succès',
            'share' => $result,
        ], 201);
    }

    public function destroy(Request $request, string $documentId, string $shareId): JsonResponse
    {
        $currentUserId = UserId::fromString($request->user()->id);

        $this->revokeShare->execute(
            ShareId::fromString($shareId),
            $currentUserId
        );

        return response()->json(['message' => 'Lien de partage révoqué avec succès']);
    }
}
