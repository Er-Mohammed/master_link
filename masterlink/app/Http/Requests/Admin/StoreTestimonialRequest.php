<?php

namespace App\Http\Requests\Admin;

use App\Models\Testimonial;
use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Testimonial::class) ?? false;
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
                'min:0',
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
            'display_name.required' => 'Display name is required.',
            'message.required' => 'Message is required.',
            'sort_order.integer' => 'Sort order must be an integer.',
            'sort_order.min' => 'Sort order cannot be negative.',
        ];
    }
}
