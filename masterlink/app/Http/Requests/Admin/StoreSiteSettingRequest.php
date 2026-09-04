<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'key' => [
                'required',
                'string',
                'max:100',
                'unique:site_settings,key',
            ],

            'value' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
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

        ];
    }
}
