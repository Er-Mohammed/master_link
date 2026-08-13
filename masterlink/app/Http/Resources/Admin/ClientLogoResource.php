<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientLogoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'media_id' => $this->media_id,
            'company_name' => $this->company_name,
            'website_url' => $this->website_url,
            'sort_order' => $this->sort_order,
            'is_active' => (bool) $this->is_active,

            'media' => new MediaResource(
                $this->whenLoaded('media')
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}