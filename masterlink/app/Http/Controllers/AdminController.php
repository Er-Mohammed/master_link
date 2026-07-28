<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * عرض قائمة المسؤولين
     */
    public function index()
    {
        $admins = Admin::latest()->paginate(10);
        return view('admins.index', compact('admins'));
    }

    /**
     * عرض صفحة إنشاء مسؤول جديد
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * حفظ مسؤول جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email',
            'password'  => 'required|string|min:8',
            'role'      => 'required|string|in:admin,super_admin',
            'is_active' => 'boolean',
        ]);

        // تشفير كلمة المرور وتحديد الحالة الافتراضية
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        Admin::create($validated);

        return redirect()->route('admins.index')->with('success', 'تم إضافة المسؤول بنجاح.');
    }

    /**
     * عرض بيانات مسؤول محدد
     */
    public function show(Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }

    /**
     * عرض صفحة تعديل مسؤول
     */
    public function edit(Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    /**
     * تحديث بيانات مسؤول في قاعدة البيانات
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
            'password'  => 'nullable|string|min:8',
            'role'      => 'required|string|in:admin,super_admin',
            'is_active' => 'boolean',
        ]);

        // تعديل كلمة المرور فقط إذا تم إدخال قيم جديدة
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $admin->update($validated);

        return redirect()->route('admins.index')->with('success', 'تم تحديث بيانات المسؤول بنجاح.');
    }

    /**
     * حذف مسؤول من قاعدة البيانات
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'تم حذف المسؤول بنجاح.');
    }
}