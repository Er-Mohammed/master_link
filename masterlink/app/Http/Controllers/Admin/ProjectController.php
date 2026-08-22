<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\SyncProjectMediaRequest;
use App\Http\Requests\Admin\SyncProjectServicesRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Http\Resources\Admin\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $perPage = min(
            max((int) $request->input('per_page', 15), 1),
            100
        );

        $query = Project::query()
            ->with([
                'category',
                'media',
                'services',
            ])
            ->withCount([
                'media',
                'services',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Filtering
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->input('category_id')
            );
        }

        if ($request->filled('is_active')) {
            $query->where(
                'is_active',
                filter_var(
                    $request->input('is_active'),
                    FILTER_VALIDATE_BOOLEAN
                )
            );
        }

        if ($request->filled('is_featured')) {
            $query->where(
                'is_featured',
                filter_var(
                    $request->input('is_featured'),
                    FILTER_VALIDATE_BOOLEAN
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Searching
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim(
                $request->input('search')
            );

            $query->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere(
                        'short_description',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'title',
            'client_name',
            'completion_date',
            'sort_order',
            'created_at',
            'updated_at',
        ];

        $sort = $request->input(
            'sort',
            'created_at'
        );

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $direction = strtolower(
            $request->input(
                'direction',
                'desc'
            )
        );

        if (! in_array(
            $direction,
            ['asc', 'desc'],
            true
        )) {
            $direction = 'desc';
        }

        $query->orderBy(
            $sort,
            $direction
        );

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $projects = $query
            ->paginate($perPage)
            ->withQueryString();

        return ProjectResource::collection(
            $projects
        );
    }

    /**
     * Store a newly created project.
     */
    public function store(
        StoreProjectRequest $request
    ) {
        $this->authorize(
            'create',
            Project::class
        );

        $project = Project::create(
            $request->validated()
        );

        $project->load([
            'category',
            'media',
            'services',
        ]);

        $project->loadCount([
            'media',
            'services',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully.',
            'data' => new ProjectResource($project),
        ], 201);
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $this->authorize(
            'view',
            $project
        );

        $project->load([
            'category',
            'media',
            'services',
        ]);

        $project->loadCount([
            'media',
            'services',
        ]);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
        ]);
    }

    /**
     * Update the specified project.
     */
    public function update(
        UpdateProjectRequest $request,
        Project $project
    ) {
        $this->authorize(
            'update',
            $project
        );

        $project->update(
            $request->validated()
        );

        $project = $project->fresh();

        $project->load([
            'category',
            'media',
            'services',
        ]);

        $project->loadCount([
            'media',
            'services',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully.',
            'data' => new ProjectResource($project),
        ]);
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Project $project)
    {
        $this->authorize(
            'delete',
            $project
        );

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully.',
        ]);
    }

    /**
     * Synchronize media associated with the project.
     */
    public function syncMedia(
        SyncProjectMediaRequest $request,
        Project $project
    ) {
        $this->authorize(
            'update',
            $project
        );

        DB::transaction(function () use ($request, $project) {
            $project->media()->sync(
                $request->getNormalizedMediaData()
            );
        });

        $project->load([
            'category',
            'media',
            'services',
        ]);

        $project->loadCount([
            'media',
            'services',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project media synchronized successfully.',
            'data' => new ProjectResource($project),
        ]);
    }

    /**
     * Synchronize services associated with the project.
     */
    public function syncServices(
        SyncProjectServicesRequest $request,
        Project $project
    ) {
        $this->authorize(
            'update',
            $project
        );

        DB::transaction(function () use ($request, $project) {
            $project->services()->sync(
                $request->validated('services')
            );
        });

        $project->load([
            'category',
            'media',
            'services',
        ]);

        $project->loadCount([
            'media',
            'services',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project services synchronized successfully.',
            'data' => new ProjectResource($project),
        ]);
    }
}