<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:admins,email',
            ],

            'password' => [
                'required',
                'string',
                Password::defaults(),
            ],

            'role' => [
                'required',
                Rule::in([
                    Admin::ROLE_SUPER_ADMIN,
                    Admin::ROLE_ADMIN,
                    Admin::ROLE_CONTENT_MANAGER,
                    Admin::ROLE_MARKETING,
                ]),
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
