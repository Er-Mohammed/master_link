<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Resources\Admin\AuthResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Login admin.
     */
    public function login(
        LoginRequest $request
    ): JsonResponse {
        $result = $this->authService->login(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح.',
            'data' => [
                'token' => $result['token'],
                'admin' => new AuthResource(
                    $result['admin']
                ),
            ],
        ]);
    }

    /**
     * Get authenticated admin.
     */
    public function me(
        Request $request
    ): JsonResponse {
        $admin = $request->user();
        $admin->load('profileMedia');

        return response()->json([
            'success' => true,
            'data' => new AuthResource(
                $admin
            ),
        ]);
    }

    /**
     * Update authenticated admin profile.
     */
    public function updateProfile(
        UpdateProfileRequest $request
    ): JsonResponse {
        $admin = $request->user();
        $data = $request->safe()->only(['name', 'email', 'profile_media_id']);
        $admin->update($data);
        $admin->load('profileMedia');

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث البيانات الشخصية بنجاح.',
            'data' => new AuthResource(
                $admin
            ),
        ]);
    }

    /**
     * Logout admin.
     */
    public function logout(
        Request $request
    ): JsonResponse {
        $this->authService->logout(
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج بنجاح.',
        ]);
    }

    /**
     * Change admin password.
     */
    public function changePassword(
        ChangePasswordRequest $request
    ): JsonResponse {
        $token = $this->authService->changePassword(
            $request->user(),
            $request->validated('current_password'),
            $request->validated('new_password')
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح.',
            'data' => [
                'token' => $token,
            ],
        ]);
    }
}
