<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $post = $this->route('post');

        return [

            'admin_id' => [
                'sometimes',
                'nullable',
                'exists:admins,id',
            ],

            'media_id' => [
                'sometimes',
                'nullable',
                'exists:media,id',
            ],

            'title' => [
                'sometimes',
                'string',
                'max:200',
            ],

            'slug' => [
                'sometimes',
                'string',
                'max:220',
                Rule::unique('posts', 'slug')
                    ->ignore($post),
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'content' => [
                'nullable',
                'string',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

            'is_featured' => [
                'boolean',
            ],

            'is_active' => [
                'boolean',
            ],

        ];
    }
}