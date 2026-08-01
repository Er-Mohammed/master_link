<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    /**
     * عرض جميع المدراء
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
            'data' => $admins
        ]);
    }


    /**
     * إنشاء مدير جديد
     */
    public function store(Request $request)
    {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:150'
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:admins,email'
            ],

            'password' => [
                'required',
                'string',
                'min:8'
            ],

            'role' => [
                'required',
                Rule::in([
                    Admin::ROLE_SUPER_ADMIN,
                    Admin::ROLE_ADMIN,
                    Admin::ROLE_CONTENT_MANAGER,
                    Admin::ROLE_MARKETING,
                ])
            ],

            'is_active' => [
                'boolean'
            ],

        ]);


        $validated['password'] = Hash::make(
            $validated['password']
        );


        $admin = Admin::create($validated);


        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully',
            'data' => $admin->only([
                'id',
                'name',
                'email',
                'role',
                'is_active'
            ])
        ],201);
    }


    /**
     * عرض مدير واحد
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
                'is_active'
            ])
        ]);
    }


    /**
     * تحديث بيانات المدير
     */
    public function update(Request $request, Admin $admin)
    {

        $validated = $request->validate([

            'name' => [
                'sometimes',
                'string',
                'max:150'
            ],

            'email' => [
                'sometimes',
                'email',
                'max:150',
                Rule::unique('admins','email')
                    ->ignore($admin->id)
            ],

            'password' => [
                'nullable',
                'string',
                'min:8'
            ],

            'role' => [
                'sometimes',
                Rule::in([
                    Admin::ROLE_SUPER_ADMIN,
                    Admin::ROLE_ADMIN,
                    Admin::ROLE_CONTENT_MANAGER,
                    Admin::ROLE_MARKETING,
                ])
            ],

            'is_active' => [
                'boolean'
            ],

        ]);


        if(isset($validated['password'])){

            $validated['password'] =
                Hash::make($validated['password']);

        }


        $admin->update($validated);


        return response()->json([
            'success'=>true,
            'message'=>'Admin updated successfully',
            'data'=>$admin->only([
                'id',
                'name',
                'email',
                'role',
                'is_active'
            ])
        ]);
    }


    /**
     * حذف مدير
     */
    public function destroy(Admin $admin)
    {

        $admin->delete();


        return response()->json([
            'success'=>true,
            'message'=>'Admin deleted successfully'
        ]);

    }
}