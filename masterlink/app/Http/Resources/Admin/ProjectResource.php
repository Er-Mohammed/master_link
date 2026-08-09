<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'category_id' => $this->category_id,

            'title' => $this->title,
            'slug' => $this->slug,
            'client_name' => $this->client_name,

            'short_description' => $this->short_description,
            'full_description' => $this->full_description,

            'project_url' => $this->project_url,
            'completion_date' => $this->completion_date?->format('Y-m-d'),

            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,

            'category' => $this->whenLoaded('category'),

            'media' => MediaResource::collection(
                $this->whenLoaded('media')
            ),

            'services' => ServiceResource::collection(
                $this->whenLoaded('services')
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}