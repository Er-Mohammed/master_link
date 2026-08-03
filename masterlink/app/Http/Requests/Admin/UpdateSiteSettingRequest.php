<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $siteSetting = $this->route('siteSetting');

        return [

            'key' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('site_settings', 'key')
                    ->ignore($siteSetting?->id),
            ],

            'value' => [
                'nullable',
                'string',
            ],

            'type' => [
                'sometimes',
                Rule::in([
                    'text',
                    'textarea',
                    'email',
                    'phone',
                    'url',
                    'image',
                ]),
            ],

            'group_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'is_active' => [
                'boolean',
            ],

        ];
    }
}