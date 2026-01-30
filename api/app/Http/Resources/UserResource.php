<?php

namespace App\Http\Resources;

use App\Domain\Identity\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof User) {
            return [
                'id' => $this->resource->id->value,
                'first_name' => $this->resource->firstName,
                'last_name' => $this->resource->lastName,
                'email' => $this->resource->email->value,
            ];
        }

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
