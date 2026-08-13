<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine whether the authenticated admin
     * is authorized to update the post.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $post instanceof Post
            && ($this->user()?->can(
                'update',
                $post
            ) ?? false);
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'media_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:media,id',
            ],

            'title' => [
                'sometimes',
                'required',
                'string',
                'max:200',
            ],

            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:220',
                Rule::unique(
                    'posts',
                    'slug'
                )->ignore($post),
            ],

            'short_description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'content' => [
                'sometimes',
                'required',
                'string',
            ],

            'published_at' => [
                'sometimes',
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