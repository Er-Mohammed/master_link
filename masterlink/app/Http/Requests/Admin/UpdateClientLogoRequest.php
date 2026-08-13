<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientLogoRequest extends FormRequest
{
    /**
     * Determine if the authenticated admin
     * is authorized to update this client logo.
     */
    public function authorize(): bool
    {
        $clientLogo = $this->route('client_logo');

        return $clientLogo
            && ($this->user()?->can('update', $clientLogo) ?? false);
    }

    /**
     * Get the validation rules that apply
     * to the request.
     */
    public function rules(): array
    {
        $clientLogo = $this->route('client_logo');

        return [
            'media_id' => [
                'sometimes',
                'nullable',
                'exists:media,id',
                Rule::unique('client_logos', 'media_id')
                    ->ignore($clientLogo?->id),
            ],

            'company_name' => [
                'sometimes',
                'nullable',
                'string',
                'max:150',
            ],

            'website_url' => [
                'sometimes',
                'nullable',
                'url',
            ],

            'sort_order' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
            ],

            'is_active' => [
                'sometimes',
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
            'media_id.exists' =>
                'Selected media does not exist.',

            'media_id.unique' =>
                'This media is already assigned to another client logo.',

            'website_url.url' =>
                'Website URL must be a valid URL.',

            'sort_order.integer' =>
                'Sort order must be an integer.',

            'sort_order.min' =>
                'Sort order cannot be negative.',
        ];
    }
}