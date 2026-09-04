<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectCategoryRequest extends FormRequest
{
    /**
     * Determine whether the admin can update this category.
     */
    public function authorize(): bool
    {
        $category = $this->route('project_category');

        return $category
            && ($this->user()?->can(
                'update',
                $category
            ) ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $category = $this->route('project_category');

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
                Rule::unique(
                    'project_categories',
                    'slug'
                )->ignore($category?->id),
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
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
