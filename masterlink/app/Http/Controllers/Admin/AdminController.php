<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display all admins.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Admin::class);

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
    public function store(StoreAdminRequest $request): JsonResponse
    {
        $this->authorize('create', Admin::class);

        $validated = $request->validated();

        $validated['password'] = Hash::make(
            $validated['password']
        );

        $admin = Admin::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully.',
            'data' => new AdminResource($admin),
        ], 201);
    }

    /**
     * Display the specified admin.
     */
    public function show(Admin $admin): JsonResponse
    {
        $this->authorize('view', $admin);

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
        $this->authorize('update', $admin);

        $validated = $request->validated();

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make(
                $validated['password']
            );
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

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
    public function destroy(Admin $admin): JsonResponse
    {
        $this->authorize('delete', $admin);

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin deleted successfully.',
        ]);
    }
}