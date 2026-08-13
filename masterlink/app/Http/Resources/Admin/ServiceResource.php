<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'slug' => $this->slug,

            'short_description' => $this->short_description,

            'full_description' => $this->full_description,

            'sort_order' => $this->sort_order,

            'is_active' => (bool) $this->is_active,

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            'media' => MediaResource::collection(
                $this->whenLoaded('media')
            ),

            'media_count' => $this->when(
                isset($this->media_count),
                $this->media_count
            ),

            'projects_count' => $this->when(
                isset($this->projects_count),
                $this->projects_count
            ),

            'consultations_count' => $this->when(
                isset($this->consultations_count),
                $this->consultations_count
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
