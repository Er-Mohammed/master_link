<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
                'in:image,video,document',
            ],

            'admin_id' => [
                'nullable',
                'exists:admins,id',
            ],

            'alt_text' => [
                'nullable',
                'string',
                'max:255',
            ],

        ];
    }
}