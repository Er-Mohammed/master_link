<?php

namespace App\Http\Controllers;

use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectCategoryController extends Controller
{
    /**
     * عرض قائمة تصنيفات المشاريع
     */
    public function index()
    {
        $categories = ProjectCategory::orderBy('sort_order', 'asc')
            ->latest('id')
            ->paginate(10);

        return view('project_categories.index', compact('categories'));
    }

    /**
     * عرض صفحة إضافة تصنيف جديد
     */
    public function create()
    {
        return view('project_categories.create');
    }

    /**
     * حفظ تصنيف جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:project_categories,name',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        // توليد الـ slug ودعم الحروف العربية باستخدام Str::slug مع تحديد المفصل
        $slug = Str::slug($validated['name'], '-', null);
        
        // إذا كان الاسم عربياً بالكامل وتسبب Str::slug في فارغ، نستخدم البديل
        if (empty($slug)) {
            $slug = preg_replace('/\s+/u', '-', trim($validated['name']));
        }

        // التأكد من عدم تكرار الـ slug
        if (ProjectCategory::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $validated['slug']       = $slug;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active']  = $request->has('is_active') ? $request->boolean('is_active') : true;

        ProjectCategory::create($validated);

        return redirect()->route('project-categories.index')
            ->with('success', 'تم إضافة تصنيف المشروع بنجاح.');
    }

    /**
     * عرض تفاصيل تصنيف محدد
     */
    public function show(ProjectCategory $projectCategory)
    {
        // تحميل المشاريع المرتبطة بهذا التصنيف عند العرض
        $projectCategory->load('projects');
        return view('project_categories.show', compact('projectCategory'));
    }

    /**
     * عرض صفحة تعديل تصنيف
     */
    public function edit(ProjectCategory $projectCategory)
    {
        return view('project_categories.edit', compact('projectCategory'));
    }

    /**
     * تحديث بيانات التصنيف
     */
    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('project_categories')->ignore($projectCategory->id)],
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        if ($projectCategory->name !== $validated['name']) {
            $slug = Str::slug($validated['name'], '-', null);
            if (empty($slug)) {
                $slug = preg_replace('/\s+/u', '-', trim($validated['name']));
            }
            if (ProjectCategory::where('slug', $slug)->where('id', '!=', $projectCategory->id)->exists()) {
                $slug = $slug . '-' . time();
            }
            $validated['slug'] = $slug;
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active']  = $request->boolean('is_active');

        $projectCategory->update($validated);

        return redirect()->route('project-categories.index')
            ->with('success', 'تم تحديث تصنيف المشروع بنجاح.');
    }

    /**
     * حذف تصنيف مشروع
     */
    public function destroy(ProjectCategory $projectCategory)
    {
        $projectCategory->delete();

        return redirect()->route('project-categories.index')
            ->with('success', 'تم حذف التصنيف بنجاح.');
    }
}