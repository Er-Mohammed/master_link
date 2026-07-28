<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * عرض قائمة المشاريع
     */
    public function index(Request $request)
    {
        $query = Project::with('category')->orderBy('sort_order', 'asc')->latest('id');

        // التصفية حسب التصنيف
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // البحث حسب عنوان المشروع أو اسم العميل
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        $projects = $query->paginate(12);
        $categories = ProjectCategory::where('is_active', true)->get();

        return view('projects.index', compact('projects', 'categories'));
    }

    /**
     * عرض صفحة إنشاء مشروع جديد
     */
    public function create()
    {
        $categories = ProjectCategory::where('is_active', true)->get();
        return view('projects.create', compact('categories'));
    }

    /**
     * حفظ مشروع جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'       => 'required|exists:project_categories,id',
            'title'             => 'required|string|max:255',
            'client_name'       => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'full_description'  => 'nullable|string',
            'project_url'       => 'nullable|url|max:255',
            'completion_date'   => 'nullable|date',
            'is_featured'       => 'boolean',
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'          => 'boolean',
        ]);

        // توليد الـ slug مع دعم الحروف العربية
        $slug = Str::slug($validated['title'], '-', null);
        if (empty($slug)) {
            $slug = preg_replace('/\s+/u', '-', trim($validated['title']));
        }

        if (Project::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $validated['slug']        = $slug;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order']  = $validated['sort_order'] ?? 0;
        $validated['is_active']   = $request->has('is_active') ? $request->boolean('is_active') : true;

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'تم إضافة المشروع بنجاح.');
    }

    /**
     * عرض تفاصيل مشروع محدد
     */
    public function show(Project $project)
    {
        $project->load(['category', 'media', 'services']);
        return view('projects.show', compact('project'));
    }

    /**
     * عرض صفحة تعديل مشروع
     */
    public function edit(Project $project)
    {
        $categories = ProjectCategory::where('is_active', true)->get();
        return view('projects.edit', compact('project', 'categories'));
    }

    /**
     * تحديث بيانات المشروع
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'category_id'       => 'required|exists:project_categories,id',
            'title'             => 'required|string|max:255',
            'client_name'       => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'full_description'  => 'nullable|string',
            'project_url'       => 'nullable|url|max:255',
            'completion_date'   => 'nullable|date',
            'is_featured'       => 'boolean',
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'          => 'boolean',
        ]);

        if ($project->title !== $validated['title']) {
            $slug = Str::slug($validated['title'], '-', null);
            if (empty($slug)) {
                $slug = preg_replace('/\s+/u', '-', trim($validated['title']));
            }
            if (Project::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                $slug = $slug . '-' . time();
            }
            $validated['slug'] = $slug;
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order']  = $validated['sort_order'] ?? 0;
        $validated['is_active']   = $request->boolean('is_active');

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'تم تحديث بيانات المشروع بنجاح.');
    }

    /**
     * حذف مشروع
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'تم حذف المشروع بنجاح.');
    }
}