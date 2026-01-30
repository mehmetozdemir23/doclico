<?php

namespace App\Http\Resources;

use App\Domain\Document\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Document) {
            return [
                'id' => $this->resource->id->value,
                'name' => $this->resource->name,
                'data' => $this->resource->data,
                'template_id' => $this->resource->templateId->value,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'data' => $this->data,
            'template' => new TemplateResource($this->whenLoaded('template')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
