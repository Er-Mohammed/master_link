<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * عرض قائمة الخدمات
     */
    public function index()
    {
        $services = Service::orderBy('sort_order', 'asc')
            ->latest()
            ->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    /**
     * صفحة إضافة خدمة جديدة
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * حفظ الخدمة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:services,slug',
            'short_description' => 'nullable|string|max:500',
            'full_description'  => 'nullable|string',
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'         => 'nullable|boolean',
        ]);

        // توليد الـ slug تلقائياً بحال لم يُدخل
        $validated['slug'] = $validated['slug'] 
            ? Str::slug($validated['slug']) 
            : Str::slug($validated['title']) . '-' . rand(100, 9999);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Service::create($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم إضافة الخدمة بنجاح.');
    }

    /**
     * صفحة تعديل الخدمة
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * تحديث بيانات الخدمة
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:services,slug,' . $service->id,
            'short_description' => 'nullable|string|max:500',
            'full_description'  => 'nullable|string',
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'         => 'nullable|boolean',
        ]);

        $validated['slug'] = $validated['slug'] 
            ? Str::slug($validated['slug']) 
            : Str::slug($validated['title']) . '-' . rand(100, 9999);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم تحديث بيانات الخدمة بنجاح.');
    }

    /**
     * حذف الخدمة
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'تم حذف الخدمة بنجاح.');
    }
}