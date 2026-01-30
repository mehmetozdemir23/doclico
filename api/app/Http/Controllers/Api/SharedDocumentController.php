<?php

namespace App\Http\Controllers\Api;

use App\Application\Sharing\AccessSharedDocument;
use App\Domain\FileGeneration\FileFormat;
use App\Domain\Template\Template;
use App\Domain\Template\TemplateId;
use App\Domain\Template\TemplateRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Infrastructure\Rendering\RendererFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SharedDocumentController extends Controller
{
    public function __construct(
        private readonly AccessSharedDocument $accessSharedDocument,
        private readonly TemplateRepositoryInterface $templateRepository,
    ) {}

    public function __invoke(Request $request, string $token, RendererFactory $factory): Response
    {
        $result = $this->accessSharedDocument->execute($token);

        $template = $this->templateRepository->findById(
            TemplateId::fromInt($result->template->id)
        );

        if (! $template instanceof Template) {
            throw new NotFoundHttpException('Template not found');
        }

        $format = $request->query('format', 'pdf');
        $renderer = $factory->make(FileFormat::from($format));

        $content = $renderer->render($template, $result->document->data);
        $fileName = "{$result->document->name}.{$renderer->extension()}";

        return response(
            $content,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "inline; filename=\"{$fileName}\"",
            ]
        );
    }
}
