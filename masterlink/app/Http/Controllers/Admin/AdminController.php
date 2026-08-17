<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display all admins.
     */
    public function index(): JsonResponse
    {
        $this->authorize(
            'viewAny',
            Admin::class
        );

        $admins = Admin::query()
            ->select([
                'id',
                'name',
                'email',
                'role',
                'is_active',
                'created_at',
                'updated_at',
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => AdminResource::collection($admins),
        ]);
    }

    /**
     * Store a newly created admin.
     */
    public function store(
        StoreAdminRequest $request
    ): JsonResponse {
        $this->authorize(
            'create',
            Admin::class
        );

        $validated = $request->validated();

        $validated['password'] = Hash::make(
            $validated['password']
        );

        $admin = DB::transaction(
            fn () => Admin::create($validated)
        );

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully.',
            'data' => new AdminResource($admin),
        ], 201);
    }

    /**
     * Display the specified admin.
     */
    public function show(
        Admin $admin
    ): JsonResponse {
        $this->authorize(
            'view',
            $admin
        );

        return response()->json([
            'success' => true,
            'data' => new AdminResource($admin),
        ]);
    }

    /**
     * Update the specified admin.
     */
    public function update(
        UpdateAdminRequest $request,
        Admin $admin
    ): JsonResponse {
        $this->authorize(
            'update',
            $admin
        );

        $validated = $request->validated();

        $passwordChanged = false;

        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        if (
            isset($validated['password'])
            && filled($validated['password'])
        ) {
            $validated['password'] = Hash::make(
                $validated['password']
            );

            $passwordChanged = true;
        } else {
            unset($validated['password']);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Admin
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $admin,
            $validated,
            $passwordChanged
        ) {
            $admin->update($validated);

            /*
            |--------------------------------------------------------------------------
            | Revoke Existing Tokens
            |--------------------------------------------------------------------------
            |
            | If the Super Admin changes another admin's password,
            | all existing authentication tokens become invalid.
            |
            */

            if ($passwordChanged) {
                $admin->tokens()->delete();
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Admin updated successfully.',
            'data' => new AdminResource(
                $admin->fresh()
            ),
        ]);
    }

    /**
     * Remove the specified admin.
     */
    public function destroy(
        Admin $admin
    ): JsonResponse {
        $this->authorize(
            'delete',
            $admin
        );

        DB::transaction(function () use ($admin) {

            /*
            |--------------------------------------------------------------------------
            | Revoke all authentication tokens.
            |--------------------------------------------------------------------------
            */

            $admin->tokens()->delete();

            /*
            |--------------------------------------------------------------------------
            | Soft delete the admin.
            |--------------------------------------------------------------------------
            */

            $admin->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Admin deleted successfully.',
        ]);
    }
}