<?php

namespace App\Http\Resources;

use App\Domain\Template\Template;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Template) {
            return [
                'id' => $this->resource->id->value,
                'type' => $this->resource->type,
                'name' => $this->resource->name,
                'category' => $this->resource->category,
                'icon' => $this->resource->icon,
                'fields' => $this->resource->fields,
                'popular' => $this->resource->popular,
            ];
        }

        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'category' => $this->category,
            'icon' => $this->icon,
            'fields' => $this->fields,
            'popular' => $this->popular,
        ];
    }
}
