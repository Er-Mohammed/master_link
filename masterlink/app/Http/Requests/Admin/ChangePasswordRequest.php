<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
            ],

            'new_password' => [
                'required',
                'string',
                'confirmed',
                'different:current_password',
                Password::defaults(),
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'current_password.required' =>
                'كلمة المرور الحالية مطلوبة.',

            'new_password.required' =>
                'كلمة المرور الجديدة مطلوبة.',

            'new_password.confirmed' =>
                'تأكيد كلمة المرور الجديدة غير متطابق.',

            'new_password.different' =>
                'يجب أن تختلف كلمة المرور الجديدة عن الحالية.',
        ];
    }
}