<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectCategoryRequest;
use App\Http\Requests\Admin\UpdateProjectCategoryRequest;
use App\Http\Resources\Admin\ProjectCategoryResource;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;

class ProjectCategoryController extends Controller
{
    /**
     * Display a listing of project categories.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', ProjectCategory::class);

        $perPage = min(
            max((int) $request->input('per_page', 15), 1),
            100
        );

        $query = ProjectCategory::query()
            ->withCount('projects');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by active status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('is_active')) {
            $isActive = filter_var(
                $request->input('is_active'),
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );

            if ($isActive !== null) {
                $query->where('is_active', $isActive);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'name',
            'sort_order',
            'created_at',
            'updated_at',
        ];

        $sort = $request->input('sort', 'sort_order');

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'sort_order';
        }

        $direction = strtolower(
            $request->input('direction', 'asc')
        );

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        $query->orderBy($sort, $direction);

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $categories = $query
            ->paginate($perPage)
            ->withQueryString();

        return ProjectCategoryResource::collection($categories);
    }

    /**
     * Store a newly created project category.
     */
    public function store(StoreProjectCategoryRequest $request)
    {
        $this->authorize('create', ProjectCategory::class);

        $category = ProjectCategory::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Project category created successfully.',
            'data' => new ProjectCategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified project category.
     */
    public function show(ProjectCategory $projectCategory)
    {
        $this->authorize('view', $projectCategory);

        $projectCategory->load('projects');

        $projectCategory->loadCount('projects');

        return response()->json([
            'success' => true,
            'data' => new ProjectCategoryResource($projectCategory),
        ]);
    }

    /**
     * Update the specified project category.
     */
    public function update(
        UpdateProjectCategoryRequest $request,
        ProjectCategory $projectCategory
    ) {
        $this->authorize('update', $projectCategory);

        $projectCategory->update(
            $request->validated()
        );

        $projectCategory->load('projects');
        $projectCategory->loadCount('projects');

        return response()->json([
            'success' => true,
            'message' => 'Project category updated successfully.',
            'data' => new ProjectCategoryResource($projectCategory),
        ]);
    }

    /**
     * Remove the specified project category.
     */
    public function destroy(ProjectCategory $projectCategory)
    {
        $this->authorize('delete', $projectCategory);

        $projectCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project category deleted successfully.',
        ]);
    }
}
