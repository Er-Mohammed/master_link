<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Http\Resources\Admin\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     *
     * Supports:
     * - Pagination
     * - Filtering
     * - Searching
     * - Sorting
     */
    public function index(Request $request)
    {
        $this->authorize(
            'viewAny',
            Service::class
        );

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = min(
            max(
                (int) $request->input('per_page', 15),
                1
            ),
            100
        );

        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = Service::query()
            ->with(['media'])
            ->withCount([
                'media',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Filtering
        |--------------------------------------------------------------------------
        */

        if ($request->filled('is_active')) {
            $isActive = filter_var(
                $request->input('is_active'),
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );

            if ($isActive !== null) {
                $query->where(
                    'is_active',
                    $isActive
                );
            }
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

            $query->where(function ($q) use ($search) {
                $q->where(
                    'title',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'slug',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'short_description',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'full_description',
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
            'sort_order',
            'created_at',
            'updated_at',
        ];

        $sort = $request->input(
            'sort',
            'sort_order'
        );

        if (! in_array(
            $sort,
            $allowedSorts,
            true
        )) {
            $sort = 'sort_order';
        }

        $direction = strtolower(
            $request->input(
                'direction',
                'asc'
            )
        );

        if (! in_array(
            $direction,
            ['asc', 'desc'],
            true
        )) {
            $direction = 'asc';
        }

        $query->orderBy(
            $sort,
            $direction
        );

        /*
        |--------------------------------------------------------------------------
        | Pagination Result
        |--------------------------------------------------------------------------
        */

        $services = $query
            ->paginate($perPage)
            ->withQueryString();

        return ServiceResource::collection(
            $services
        );
    }

    /**
     * Store a newly created service.
     */
    public function store(
        StoreServiceRequest $request
    ) {
        $this->authorize(
            'create',
            Service::class
        );

        $service = Service::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully.',
            'data' => new ServiceResource(
                $service
            ),
        ], 201);
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        $this->authorize(
            'view',
            $service
        );

        $service->load([
            'media',
            'projects',
            'consultations',
        ]);

        $service->loadCount([
            'media',
            'projects',
            'consultations',
        ]);

        return response()->json([
            'success' => true,
            'data' => new ServiceResource(
                $service
            ),
        ]);
    }

    /**
     * Update the specified service.
     */
    public function update(
        UpdateServiceRequest $request,
        Service $service
    ) {
        $this->authorize(
            'update',
            $service
        );

        $service->update(
            $request->validated()
        );

        $service->load([
            'media',
            'projects',
            'consultations',
        ]);

        $service->loadCount([
            'media',
            'projects',
            'consultations',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully.',
            'data' => new ServiceResource(
                $service
            ),
        ]);
    }

    /**
     * Remove the specified service.
     */
    public function destroy(Service $service)
    {
        $this->authorize(
            'delete',
            $service
        );

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully.',
        ]);
    }

    /**
     * Attach media to a service.
     */
    public function attachMedia(
        Request $request,
        Service $service
    ) {
        $this->authorize(
            'update',
            $service
        );

        $validated = $request->validate([
            'media_id' => [
                'required',
                'integer',
                'exists:media,id',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $service->media()->syncWithoutDetaching([
            $validated['media_id'] => [
                'sort_order' => $validated['sort_order'] ?? 0,
            ],
        ]);

        $service->load('media');

        return response()->json([
            'success' => true,
            'message' => 'Media attached successfully.',
            'data' => new ServiceResource(
                $service
            ),
        ]);
    }

    /**
     * Detach media from a service.
     */
    public function detachMedia(
        Service $service,
        int $media
    ) {
        $this->authorize(
            'update',
            $service
        );

        $service->media()->detach(
            $media
        );

        $service->load('media');

        return response()->json([
            'success' => true,
            'message' => 'Media detached successfully.',
            'data' => new ServiceResource(
                $service
            ),
        ]);
    }
}
