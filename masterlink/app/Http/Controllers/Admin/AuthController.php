<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Resources\Admin\AuthResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(
        protected AuthService $authService
    ) {
    }


    /**
     * Login admin.
     */
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->validated()
        );


        return response()->json([

            'success' => true,

            'message' => 'Login successful.',

            'token' => $result['token'],

            'admin' => new AuthResource(
                $result['admin']
            ),

        ]);
    }



    /**
     * Current authenticated admin.
     */
    public function me(Request $request)
    {
        return response()->json([

            'success' => true,

            'data' => new AuthResource(
                $request->user()
            ),

        ]);
    }



    /**
     * Logout admin.
     */
    public function logout(Request $request)
    {

        $this->authService->logout(
            $request->user()
        );


        return response()->json([

            'success' => true,

            'message' => 'Logout successful.',

        ]);
    }

}