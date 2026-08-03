<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'admin_id' => [
                'nullable',
                'exists:admins,id',
            ],

            'media_id' => [
                'nullable',
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