<?php

namespace App\Http\Controllers\Api;

use App\Application\Template\GetAllTemplates;
use App\Application\Template\GetTemplateByType;
use App\Application\Template\TemplateResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TemplateController extends Controller
{
    public function __construct(
        private readonly GetAllTemplates $getAllTemplates,
        private readonly GetTemplateByType $getTemplateByType,
    ) {}

    public function index(): JsonResponse
    {
        $result = $this->getAllTemplates->execute();

        return response()->json($result->templates);
    }

    public function show(string $type): JsonResponse
    {
        $result = $this->getTemplateByType->execute($type);

        if (! $result instanceof TemplateResult) {
            throw new NotFoundHttpException('Template not found');
        }

        return response()->json($result);
    }
}
