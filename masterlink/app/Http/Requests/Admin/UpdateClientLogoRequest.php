<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientLogoRequest extends FormRequest
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
        $clientLogo = $this->route('client_logo');

        return [

            'media_id' => [
                'nullable',
                'exists:media,id',
                Rule::unique('client_logos', 'media_id')
                    ->ignore($clientLogo),
            ],

            'company_name' => [
                'nullable',
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

            'media_id.exists' => 'Selected media does not exist.',

            'media_id.unique' => 'This media is already assigned to another client logo.',

            'website_url.url' => 'Website URL must be a valid URL.',

        ];
    }
}