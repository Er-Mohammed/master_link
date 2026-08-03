<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display all admins.
     */
    public function index()
    {
        $admins = Admin::select(
                'id',
                'name',
                'email',
                'role',
                'is_active',
                'created_at'
            )
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $admins,
        ]);
    }

    /**
     * Store a newly created admin.
     */
    public function store(StoreAdminRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make(
            $validated['password']
        );

        $admin = Admin::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully.',
            'data' => $admin->only([
                'id',
                'name',
                'email',
                'role',
                'is_active',
            ]),
        ], 201);
    }

    /**
     * Display the specified admin.
     */
    public function show(Admin $admin)
    {
        return response()->json([
            'success' => true,
            'data' => $admin->only([
                'id',
                'name',
                'email',
                'role',
                'is_active',
            ]),
        ]);
    }

    /**
     * Update the specified admin.
     */
    public function update(
        UpdateAdminRequest $request,
        Admin $admin
    ) {
        $validated = $request->validated();

        if (!empty($validated['password'])) {
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
            'data' => $admin->fresh()->only([
                'id',
                'name',
                'email',
                'role',
                'is_active',
            ]),
        ]);
    }

    /**
     * Remove the specified admin.
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin deleted successfully.',
        ]);
    }
}