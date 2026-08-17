<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    /**
     * Determine whether the authenticated admin
     * is authorized to update media.
     */
    public function authorize(): bool
    {
        $media = $this->route('medium');

        return $media
            && ($this->user()?->can(
                'update',
                $media
            ) ?? false);
    }

    /**
     * Get the validation rules that apply
     * to the request.
     */
    public function rules(): array
    {
        return [
            'alt_text' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'alt_text.string' =>
                'النص البديل يجب أن يكون نصاً.',

            'alt_text.max' =>
                'النص البديل يجب ألا يتجاوز 255 حرفاً.',
        ];
    }
}