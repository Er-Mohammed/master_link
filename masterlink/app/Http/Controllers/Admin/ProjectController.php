<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('category')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:project_categories,id'],
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:220', 'unique:projects,slug'],
            'client_name' => ['nullable', 'string', 'max:150'],
            'short_description' => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
            'project_url' => ['nullable', 'url'],
            'completion_date' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer'],
            'is_active' => ['boolean'],
        ]);

        $project = Project::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully.',
            'data' => $project->load('category'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load([
            'category',
        ]);

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'category_id' => ['sometimes', 'exists:project_categories,id'],
            'title' => ['sometimes', 'string', 'max:200'],
            'slug' => [
                'sometimes',
                'string',
                'max:220',
                Rule::unique('projects', 'slug')->ignore($project->id),
            ],
            'client_name' => ['nullable', 'string', 'max:150'],
            'short_description' => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
            'project_url' => ['nullable', 'url'],
            'completion_date' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'sort_order' => ['integer'],
            'is_active' => ['boolean'],
        ]);

        $project->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully.',
            'data' => $project->fresh()->load('category'),
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully.',
        ]);
    }
}