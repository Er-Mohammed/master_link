<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConsultationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules.
     */
    public function rules(): array
    {
        return [

            'status' => [
                'required',
                'string',
                Rule::in([
                    'new',
                    'contacted',
                    'in_progress',
                    'completed',
                    'cancelled',
                ]),
            ],

        ];
    }

    /**
     * Custom Validation Messages.
     */
    public function messages(): array
    {
        return [

            'status.required' => 'Status is required.',

            'status.in' => 'Invalid consultation status.',

        ];
    }
}