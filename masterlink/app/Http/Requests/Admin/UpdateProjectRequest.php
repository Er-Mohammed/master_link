<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'category_id' => [
                'sometimes',
                'nullable',
                'exists:project_categories,id',
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
                Rule::unique('projects', 'slug')
                    ->ignore($project?->id),
            ],

            'client_name' => [
                'sometimes',
                'nullable',
                'string',
                'max:150',
            ],

            'short_description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'full_description' => [
                'sometimes',
                'nullable',
                'string',
            ],

            'project_url' => [
                'sometimes',
                'nullable',
                'url',
                'max:2048',
            ],

            'completion_date' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'is_featured' => [
                'sometimes',
                'boolean',
            ],

            'sort_order' => [
                'sometimes',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}