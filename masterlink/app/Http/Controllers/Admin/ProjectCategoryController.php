<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:150'
            ],

            'slug' => [
                'required',
                'string',
                'max:180',
                'unique:project_categories,slug'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'sort_order' => [
                'nullable',
                'integer'
            ],

            'is_active' => [
                'boolean'
            ],

        ]);


        $category = ProjectCategory::create($validated);


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
    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $validated = $request->validate([

            'name' => [
                'sometimes',
                'string',
                'max:150'
            ],

            'slug' => [
                'sometimes',
                'string',
                'max:180',
                Rule::unique(
                    'project_categories',
                    'slug'
                )->ignore($projectCategory->id),
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'sort_order' => [
                'integer'
            ],

            'is_active' => [
                'boolean'
            ],

        ]);


        $projectCategory->update($validated);


        return response()->json([
            'success' => true,
            'message' => 'Project category updated successfully.',
            'data' => $projectCategory,
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