<?php

namespace App\Http\Resources;

use App\Domain\Sharing\Share;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentShareResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Share) {
            $baseUrl = config('app.url');

            return [
                'id' => $this->resource->id->value,
                'document_id' => $this->resource->documentId->value,
                'token' => $this->resource->token,
                'share_url' => $this->resource->shareUrl($baseUrl),
                'is_expired' => $this->resource->isExpired(),
                'expires_at' => $this->resource->expiresAt?->format('Y-m-d H:i:s'),
                'downloads_count' => $this->resource->downloadsCount(),
                'last_downloaded_at' => $this->resource->lastDownloadedAt()?->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'id' => $this->id,
            'document_id' => $this->document_id,
            'token' => $this->token,
            'share_url' => $this->share_url,
            'is_expired' => $this->is_expired,
            'expires_at' => $this->expires_at,
            'downloads_count' => $this->downloads_count,
            'last_downloaded_at' => $this->last_downloaded_at,
            'created_at' => $this->created_at,
        ];
    }
}
