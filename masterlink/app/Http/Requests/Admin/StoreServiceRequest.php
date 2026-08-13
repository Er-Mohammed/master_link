<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine whether the authenticated admin
     * is authorized to create a service.
     */
    public function authorize(): bool
    {
        return $this->user()?->can(
            'create',
            Service::class
        ) ?? false;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:services,slug',
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'full_description' => [
                'nullable',
                'string',
            ],

            'sort_order' => [
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