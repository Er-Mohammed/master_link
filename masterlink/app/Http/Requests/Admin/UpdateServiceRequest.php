<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Determine whether the authenticated admin
     * is authorized to update the service.
     */
    public function authorize(): bool
    {
        $service = $this->route('service');

        return $service instanceof Service
            && ($this->user()?->can(
                'update',
                $service
            ) ?? false);
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        $service = $this->route('service');

        return [
            'title' => [
                'sometimes',
                'string',
                'max:150',
            ],

            'slug' => [
                'sometimes',
                'string',
                'max:180',
                Rule::unique(
                    'services',
                    'slug'
                )->ignore($service),
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

            'sort_order' => [
                'sometimes',
                'nullable',
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