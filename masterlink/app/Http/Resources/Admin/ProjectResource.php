<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
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

            'completion_date' => $this->completion_date?->toDateString(),

            'is_featured' => (bool) $this->is_featured,
            'sort_order' => $this->sort_order,
            'is_active' => (bool) $this->is_active,

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            'category' => new ProjectCategoryResource(
                $this->whenLoaded('category')
            ),

            'media' => MediaResource::collection(
                $this->whenLoaded('media')
            ),

            'services' => ServiceResource::collection(
                $this->whenLoaded('services')
            ),

            /*
            |--------------------------------------------------------------------------
            | Counts
            |--------------------------------------------------------------------------
            */

            'media_count' => $this->when(
                isset($this->media_count),
                $this->media_count
            ),

            'services_count' => $this->when(
                isset($this->services_count),
                $this->services_count
            ),

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
