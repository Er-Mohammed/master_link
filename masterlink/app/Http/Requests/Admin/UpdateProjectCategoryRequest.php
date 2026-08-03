<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $projectCategory = $this->route('project_category');

        return [

            'name' => [
                'sometimes',
                'string',
                'max:150',
            ],

            'slug' => [
                'sometimes',
                'string',
                'max:180',
                Rule::unique('project_categories', 'slug')
                    ->ignore($projectCategory),
            ],

            'description' => [
                'nullable',
                'string',
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