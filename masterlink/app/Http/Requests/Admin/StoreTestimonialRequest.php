<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
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
                'required',
                'string',
                'max:150',
            ],

            'message' => [
                'required',
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

            'display_name.required' => 'Display name is required.',

            'message.required' => 'Message is required.',

            'media_id.exists' => 'Selected media does not exist.',

        ];
    }
}