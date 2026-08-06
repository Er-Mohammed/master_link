<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ServiceResource extends JsonResource
{

    public function toArray(Request $request): array
    {

        return [

            'id' => $this->id,


            'title' => $this->title,


            'slug' => $this->slug,


            'short_description' =>
                $this->short_description,


            'full_description' =>
                $this->full_description,


            'sort_order' =>
                $this->sort_order,


            'is_active' =>
                (bool) $this->is_active,


            'media' =>
                MediaResource::collection(
                    $this->whenLoaded('media')
                ),


            'created_at' =>
                $this->created_at,


            'updated_at' =>
                $this->updated_at,

        ];

    }

}