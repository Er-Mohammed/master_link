<?php

namespace App\Http\Requests\Admin;

use App\Models\ClientLogo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientLogoRequest extends FormRequest
{
    /**
     * Determine if the authenticated admin
     * is authorized to create a client logo.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', ClientLogo::class) ?? false;
    }

    /**
     * Get the validation rules that apply
     * to the request.
     */
    public function rules(): array
    {
        return [
            'media_id' => [
                'required',
                'exists:media,id',
                Rule::unique('client_logos', 'media_id'),
            ],

            'company_name' => [
                'required',
                'string',
                'max:150',
            ],

            'website_url' => [
                'nullable',
                'url',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'media_id.required' =>
                'Media is required.',

            'media_id.exists' =>
                'Selected media does not exist.',

            'media_id.unique' =>
                'This media is already assigned to another client logo.',

            'company_name.required' =>
                'Company name is required.',

            'website_url.url' =>
                'Website URL must be a valid URL.',

            'sort_order.integer' =>
                'Sort order must be an integer.',

            'sort_order.min' =>
                'Sort order cannot be negative.',
        ];
    }
}