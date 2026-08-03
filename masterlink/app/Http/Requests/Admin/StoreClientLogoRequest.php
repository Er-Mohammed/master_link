<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientLogoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules.
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
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ];
    }

    /**
     * Custom Validation Messages.
     */
    public function messages(): array
    {
        return [

            'media_id.required' => 'Media is required.',

            'media_id.exists' => 'Selected media does not exist.',

            'media_id.unique' => 'This media is already assigned to another client logo.',

            'company_name.required' => 'Company name is required.',

            'website_url.url' => 'Website URL must be a valid URL.',

        ];
    }
}