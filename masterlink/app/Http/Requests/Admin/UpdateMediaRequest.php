<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'alt_text' => [
                'nullable',
                'string',
                'max:255',
            ],

            'media_type' => [
                'nullable',
                'in:image,video,document',
            ],

        ];
    }
}