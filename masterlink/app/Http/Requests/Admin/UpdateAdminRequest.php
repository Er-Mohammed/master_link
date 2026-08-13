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
        $currentAdmin = $this->user();
        $targetAdmin = $this->route('admin');

        $isSelfUpdate =
            $currentAdmin
            && $targetAdmin
            && $currentAdmin->id === $targetAdmin->id;

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
                    ->ignore($targetAdmin?->id),
            ],

            'password' => [
                'nullable',
                'string',
                Password::defaults(),
            ],

            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            |
            | Super Admin cannot change his own role.
            | Other admins can have their role changed by Super Admin.
            |
            */
            'role' => [
                'sometimes',
                Rule::in([
                    Admin::ROLE_SUPER_ADMIN,
                    Admin::ROLE_ADMIN,
                    Admin::ROLE_CONTENT_MANAGER,
                    Admin::ROLE_MARKETING,
                ]),
                Rule::when(
                    $isSelfUpdate,
                    Rule::prohibited()
                ),
            ],

            /*
            |--------------------------------------------------------------------------
            | Account Status
            |--------------------------------------------------------------------------
            |
            | Super Admin cannot deactivate himself.
            |
            */
            'is_active' => [
                'sometimes',
                'boolean',
                Rule::when(
                    $isSelfUpdate,
                    Rule::prohibitedIf(
                        $currentAdmin?->isSuperAdmin()
                    )
                ),
            ],
        ];
    }
}
