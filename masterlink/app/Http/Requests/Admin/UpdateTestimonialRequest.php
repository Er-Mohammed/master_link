<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'media_id' => [
                'nullable',
                'exists:media,id',
            ],

            'display_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'message' => [
                'nullable',
                'string',
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

    public function messages(): array
    {
        return [

            'media_id.exists' => 'Selected media does not exist.',

        ];
    }
}