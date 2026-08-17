<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $targetAdmin = $this->route('admin');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:150',
            ],

            'email' => [
                'sometimes',
                'email',
                'max:150',

                Rule::unique(
                    'admins',
                    'email'
                )->ignore(
                    $targetAdmin?->id
                ),
            ],

            'password' => [
                'sometimes',
                'nullable',
                'string',
                Password::defaults(),
            ],

            'role' => [
                'sometimes',
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