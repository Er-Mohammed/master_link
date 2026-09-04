<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine whether the authenticated admin
     * is authorized to create a post.
     */
    public function authorize(): bool
    {
        return $this->user()?->can(
            'create',
            Post::class
        ) ?? false;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            'media_id' => [
                'nullable',
                'integer',
                'exists:media,id',
            ],

            'title' => [
                'required',
                'string',
                'max:200',
            ],

            'slug' => [
                'required',
                'string',
                'max:220',
                'unique:posts,slug',
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'content' => [
                'required',
                'string',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

            'is_featured' => [
                'sometimes',
                'boolean',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
