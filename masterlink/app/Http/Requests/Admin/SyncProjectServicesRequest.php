<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SyncProjectServicesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'services' => [
                'present',
                'array',
            ],
            'services.*' => [
                'required',
                'integer',
                'exists:services,id',
            ],
        ];
    }
}
