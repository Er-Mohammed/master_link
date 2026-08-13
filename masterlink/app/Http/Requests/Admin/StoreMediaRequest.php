<?php

namespace App\Http\Requests\Admin;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    /**
     * Determine whether the authenticated admin
     * is authorized to create media.
     */
    public function authorize(): bool
    {
        return $this->user()?->can(
            'create',
            Media::class
        ) ?? false;
    }

    /**
     * Get the validation rules that apply
     * to the request.
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:10240',
            ],

            'media_type' => [
                'required',
                Rule::in([
                    'image',
                    'video',
                    'document',
                ]),
            ],

            'alt_text' => [
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
            'file.required' =>
                'الملف مطلوب.',

            'file.file' =>
                'الملف المرفق غير صالح.',

            'file.max' =>
                'حجم الملف يجب ألا يتجاوز 10 ميجابايت.',

            'media_type.required' =>
                'نوع الوسائط مطلوب.',

            'media_type.in' =>
                'نوع الوسائط يجب أن يكون image أو video أو document.',

            'alt_text.string' =>
                'النص البديل يجب أن يكون نصاً.',

            'alt_text.max' =>
                'النص البديل يجب ألا يتجاوز 255 حرفاً.',
        ];
    }
}