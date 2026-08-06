<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{

    /**
     * تسجيل دخول المدير
     */
    public function login(array $data): array
    {

        $admin = Admin::where('email', $data['email'])
            ->first();


        if (!$admin) {

            throw ValidationException::withMessages([
                'email' => 'بيانات الدخول غير صحيحة.'
            ]);

        }


        if (!$admin->is_active) {

            throw ValidationException::withMessages([
                'email' => 'الحساب غير مفعل.'
            ]);

        }


        if (!Hash::check(
            $data['password'],
            $admin->password
        )) {

            throw ValidationException::withMessages([
                'email' => 'بيانات الدخول غير صحيحة.'
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | حذف التوكنات القديمة
        |--------------------------------------------------------------------------
        */

        $admin->tokens()->delete();



        /*
        |--------------------------------------------------------------------------
        | إنشاء Token جديد
        |--------------------------------------------------------------------------
        */

        $token = $admin
            ->createToken('admin-token')
            ->plainTextToken;



        return [

            'admin' => $admin,

            'token' => $token,

        ];

    }



    /**
     * تسجيل خروج المدير
     */
    public function logout(Admin $admin): void
    {

        $token = $admin->currentAccessToken();


        if ($token) {

            $token->delete();

        }

    }

}