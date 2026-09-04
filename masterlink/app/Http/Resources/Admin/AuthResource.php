<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,
            'email' => $this->email,

            'role' => $this->role,
            'is_active' => $this->is_active,

            'profile_media_id' => $this->profile_media_id,
            'profile_media' => new MediaResource($this->whenLoaded('profileMedia')),
            'avatar_url' => $this->profileMedia?->url() ?? null,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
