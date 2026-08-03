<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectCategoryRequest;
use App\Http\Requests\Admin\UpdateProjectCategoryRequest;
use App\Models\ProjectCategory;

class ProjectCategoryController extends Controller
{
    /**
     * عرض جميع التصنيفات
     */
    public function index()
    {
        $categories = ProjectCategory::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * إنشاء تصنيف جديد
     */
    public function store(StoreProjectCategoryRequest $request)
    {
        $category = ProjectCategory::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Project category created successfully.',
            'data' => $category,
        ], 201);
    }

    /**
     * عرض تصنيف واحد
     */
    public function show(ProjectCategory $projectCategory)
    {
        $projectCategory->load('projects');

        return response()->json([
            'success' => true,
            'data' => $projectCategory,
        ]);
    }

    /**
     * تحديث تصنيف
     */
    public function update(
        UpdateProjectCategoryRequest $request,
        ProjectCategory $projectCategory
    ) {
        $projectCategory->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Project category updated successfully.',
            'data' => $projectCategory->fresh()->load('projects'),
        ]);
    }

    /**
     * حذف تصنيف
     */
    public function destroy(ProjectCategory $projectCategory)
    {
        $projectCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project category deleted successfully.',
        ]);
    }
}