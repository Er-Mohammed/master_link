<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Authenticate an admin and create a Sanctum token.
     */
    public function login(array $data): array
    {
        $admin = Admin::query()
            ->where('email', strtolower(trim($data['email'])))
            ->first();

        if (! $admin) {
            throw ValidationException::withMessages([
                'email' => 'بيانات الدخول غير صحيحة.',
            ]);
        }

        if (! $admin->isActive()) {
            throw ValidationException::withMessages([
                'email' => 'الحساب غير مفعل.',
            ]);
        }

        if (! Hash::check($data['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => 'بيانات الدخول غير صحيحة.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Single Active Token
        |--------------------------------------------------------------------------
        */

        $admin->tokens()->delete();

        $token = $admin
            ->createToken('admin-token')
            ->plainTextToken;

        return [
            'admin' => $admin->fresh(),
            'token' => $token,
        ];
    }

    /**
     * Logout the current admin token.
     */
    public function logout(Admin $admin): void
    {
        $admin->currentAccessToken()?->delete();
    }

    /**
     * Change the authenticated admin password.
     */
    public function changePassword(
        Admin $admin,
        string $currentPassword,
        string $newPassword
    ): string {
        if (! Hash::check($currentPassword, $admin->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'كلمة المرور الحالية غير صحيحة.',
            ]);
        }

        $admin->password = $newPassword;
        $admin->save();

        /*
        |--------------------------------------------------------------------------
        | Revoke All Existing Tokens
        |--------------------------------------------------------------------------
        */

        $admin->tokens()->delete();

        /*
        |--------------------------------------------------------------------------
        | Create New Token
        |--------------------------------------------------------------------------
        */

        return $admin
            ->createToken('admin-token')
            ->plainTextToken;
    }
}