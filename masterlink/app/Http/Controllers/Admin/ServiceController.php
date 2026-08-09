<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Http\Resources\Admin\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Service::class);

        $services = Service::with('media')
            ->orderBy('sort_order')
            ->get();

        return ServiceResource::collection($services);
    }

    public function store(StoreServiceRequest $request)
    {
        Gate::authorize('create', Service::class);

        $service = Service::create(
            $request->validated()
        );

        return new ServiceResource(
            $service->load('media')
        );
    }

    public function show(Service $service)
    {
        Gate::authorize('view', $service);

        $service->load('media');

        return new ServiceResource($service);
    }

    public function update(
        UpdateServiceRequest $request,
        Service $service
    ) {
        Gate::authorize('update', $service);

        $service->update(
            $request->validated()
        );

        return new ServiceResource(
            $service->fresh()->load('media')
        );
    }

    public function destroy(Service $service)
    {
        Gate::authorize('delete', $service);

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully',
        ]);
    }

    public function attachMedia(
        Request $request,
        Service $service
    ) {
        Gate::authorize('update', $service);

        $validated = $request->validate([
            'media' => [
                'required',
                'array',
            ],
            'media.*.id' => [
                'required',
                'exists:media,id',
            ],
            'media.*.sort_order' => [
                'nullable',
                'integer',
            ],
        ]);

        foreach ($validated['media'] as $item) {
            $service->media()->syncWithoutDetaching([
                $item['id'] => [
                    'sort_order' => $item['sort_order'] ?? 0,
                ],
            ]);
        }

        return new ServiceResource(
            $service->load('media')
        );
    }

    public function detachMedia(
        Service $service,
        $media
    ) {
        Gate::authorize('update', $service);

        $service->media()->detach($media);

        return new ServiceResource(
            $service->load('media')
        );
    }
}
