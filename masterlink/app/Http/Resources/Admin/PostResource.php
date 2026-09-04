<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'admin_id' => $this->admin_id,
            'media_id' => $this->media_id,

            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'content' => $this->content,

            'published_at' => $this->published_at?->toISOString(),

            'is_featured' => (bool) $this->is_featured,
            'is_active' => (bool) $this->is_active,

            'admin' => AdminResource::make(
                $this->whenLoaded('admin')
            ),

            'media' => MediaResource::make(
                $this->whenLoaded('media')
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
