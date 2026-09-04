<?php

namespace App\Http\Requests\Admin;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

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

                File::types([
                    'jpg',
                    'jpeg',
                    'png',
                    'webp',
                    'mp4',
                    'webm',
                    'mov',
                    'pdf',
                    'doc',
                    'docx',
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
     * Get the media type based on the validated file.
     */
    public function mediaType(): string
    {
        $file = $this->file('file');

        $extension = strtolower(
            $file->extension()
        );

        return match (true) {

            in_array($extension, [
                'jpg',
                'jpeg',
                'png',
                'webp',
            ], true) => 'image',

            in_array($extension, [
                'mp4',
                'webm',
                'mov',
            ], true) => 'video',

            in_array($extension, [
                'pdf',
                'doc',
                'docx',
            ], true) => 'document',

            default => throw new \RuntimeException(
                'Unsupported media type.'
            ),
        };
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'الملف مطلوب.',

            'file.file' => 'الملف المرفق غير صالح.',

            'file.max' => 'حجم الملف يجب ألا يتجاوز 10 ميجابايت.',

            'file.types' => 'نوع الملف غير مسموح به.',

            'alt_text.string' => 'النص البديل يجب أن يكون نصاً.',

            'alt_text.max' => 'النص البديل يجب ألا يتجاوز 255 حرفاً.',
        ];
    }
}
