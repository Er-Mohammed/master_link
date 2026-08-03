<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $admin = $this->route('admin');

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
                Rule::unique('admins', 'email')
                    ->ignore($admin?->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
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
                'boolean',
            ],

        ];
    }
}