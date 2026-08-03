<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'category_id' => [
                'required',
                'exists:project_categories,id',
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
                'unique:projects,slug',
            ],

            'client_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'full_description' => [
                'nullable',
                'string',
            ],

            'project_url' => [
                'nullable',
                'url',
            ],

            'completion_date' => [
                'nullable',
                'date',
            ],

            'is_featured' => [
                'boolean',
            ],

            'sort_order' => [
                'integer',
            ],

            'is_active' => [
                'boolean',
            ],

        ];
    }
}