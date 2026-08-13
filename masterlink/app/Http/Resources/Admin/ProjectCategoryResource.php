<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,

            'sort_order' => $this->sort_order,
            'is_active' => (bool) $this->is_active,

            'projects_count' => $this->when(
                isset($this->projects_count),
                $this->projects_count
            ),

            'projects' => ProjectResource::collection(
                $this->whenLoaded('projects')
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
