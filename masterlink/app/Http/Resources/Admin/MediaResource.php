<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'file_name' => $this->file_name,

            'url' => $this->url(),

            'extension' => $this->extension,

            'media_type' => $this->media_type,

            'mime_type' => $this->mime_type,

            'file_size' => $this->file_size,

            'width' => $this->width,

            'height' => $this->height,

            'alt_text' => $this->alt_text,

            'sort_order' => $this->pivot?->sort_order,

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}