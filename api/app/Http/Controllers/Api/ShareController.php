<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Application\Sharing\CreateShare;
use App\Application\Sharing\CreateShareData;
use App\Application\Sharing\Exception\ShareNotificationFailedException;
use App\Application\Sharing\GetDocumentShares;
use App\Application\Sharing\GetSharedDocumentMetadata;
use App\Application\Sharing\RenderSharedDocument;
use App\Application\Sharing\RevokeShare;
use App\Application\Sharing\SendShareNotification;
use App\Application\Sharing\TrackShareView;
use App\Domain\Document\DocumentId;
use App\Domain\FileGeneration\FileFormat;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Sharing\Exception\InvalidShareTokenException;
use App\Domain\Sharing\Exception\ShareExpiredException;
use App\Domain\Sharing\Exception\ShareNotFoundException;
use App\Domain\Sharing\Exception\ShareNotificationUnavailableException;
use App\Domain\Sharing\ShareId;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShareRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShareController extends Controller
{
    public function __construct(
        private readonly GetDocumentShares $getDocumentShares,
        private readonly CreateShare $createShare,
        private readonly RevokeShare $revokeShare,
        private readonly SendShareNotification $sendShareNotification,
        private readonly GetSharedDocumentMetadata $getSharedDocumentMetadata,
        private readonly TrackShareView $trackShareView,
        private readonly RenderSharedDocument $renderSharedDocument,
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

    public function notify(Request $request, string $shareId): JsonResponse
    {
        try {
            $this->sendShareNotification->execute(
                ShareId::fromString($shareId),
                UserId::fromString($request->user()->id),
            );
        } catch (ShareNotFoundException) {
            throw new NotFoundHttpException('Lien de partage introuvable.');
        } catch (UnauthorizedException) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        } catch (ShareNotificationUnavailableException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ShareNotificationFailedException $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }

        return response()->json(['message' => 'Email envoyé au client.']);
    }

    public function destroy(Request $request, string $shareId): JsonResponse
    {
        $currentUserId = UserId::fromString($request->user()->id);

        $this->revokeShare->execute(
            ShareId::fromString($shareId),
            $currentUserId
        );

        return response()->json(['message' => 'Lien de partage révoqué avec succès']);
    }

    public function info(string $token): JsonResponse
    {
        try {
            $metadata = $this->getSharedDocumentMetadata->execute($token);
        } catch (InvalidShareTokenException|ShareNotFoundException) {
            throw new NotFoundHttpException('Lien de partage introuvable.');
        } catch (ShareExpiredException) {
            return response()->json(['error' => 'expired', 'message' => 'Ce lien de partage a expiré.'], 410);
        }

        $this->trackShareView->execute($token);

        return response()->json([
            'document_name' => $metadata->documentName,
            'template_name' => $metadata->templateName,
            'template_type' => $metadata->templateType,
            'emitter' => $metadata->emitter,
            'emitter_company' => $metadata->emitterCompany,
            'emitter_logo' => $metadata->emitterLogo,
            'expires_at' => $metadata->expiresAt,
        ]);
    }

    public function download(Request $request, string $token): Response
    {
        $format = FileFormat::tryFrom($request->query('format', 'pdf')) ?? FileFormat::Pdf;
        $result = $this->renderSharedDocument->execute($token, $format);

        return response(
            $result->content,
            200,
            [
                'Content-Type' => $result->mimeType,
                'Content-Disposition' => "inline; filename=\"{$result->filename}\"",
            ]
        );
    }
}
