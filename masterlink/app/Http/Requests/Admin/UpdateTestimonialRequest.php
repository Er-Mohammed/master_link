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
                'sometimes',
                'nullable',
                'exists:media,id',
            ],

            'display_name' => [
                'sometimes',
                'string',
                'max:150',
            ],

            'message' => [
                'sometimes',
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

    public function messages(): array
    {
        return [
            'media_id.exists' =>
                'Selected media does not exist.',

            'display_name.string' =>
                'Display name must be a valid string.',

            'message.string' =>
                'Message must be a valid string.',

            'sort_order.integer' =>
                'Sort order must be an integer.',

            'sort_order.min' =>
                'Sort order cannot be negative.',
        ];
    }
}