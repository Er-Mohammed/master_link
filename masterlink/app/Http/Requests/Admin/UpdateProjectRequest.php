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
                    ->ignore($project->id),
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