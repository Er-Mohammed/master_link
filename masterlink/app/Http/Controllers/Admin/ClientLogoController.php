<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientLogoRequest;
use App\Http\Requests\Admin\UpdateClientLogoRequest;
use App\Http\Resources\Admin\ClientLogoResource;
use App\Models\ClientLogo;

class ClientLogoController extends Controller
{
    /**
     * Display a listing of client logos.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $this->authorize('viewAny', ClientLogo::class);

        $query = ClientLogo::query()->with('media');

        if ($request->has('search') && !empty($request->query('search'))) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('website_url', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active') && $request->query('is_active') !== null && $request->query('is_active') !== '') {
            $isActive = filter_var($request->query('is_active'), FILTER_VALIDATE_BOOLEAN);
            $query->where('is_active', $isActive);
        }

        $sort = $request->query('sort', 'sort_order');
        $direction = strtolower($request->query('direction', 'asc')) === 'desc' ? 'desc' : 'asc';
        
        $allowedSorts = ['id', 'company_name', 'sort_order', 'is_active', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
        }

        if ($request->has('per_page')) {
            $perPage = (int) $request->query('per_page', 15);
            return ClientLogoResource::collection($query->paginate($perPage));
        }

        return ClientLogoResource::collection($query->get());
    }

    /**
     * Store a newly created client logo.
     */
    public function store(
        StoreClientLogoRequest $request
    ) {
        $this->authorize(
            'create',
            ClientLogo::class
        );

        $logo = ClientLogo::create(
            $request->validated()
        );

        $logo->load('media');

        return response()->json([
            'success' => true,
            'message' =>
                'Client logo created successfully.',
            'data' => new ClientLogoResource($logo),
        ], 201);
    }

    /**
     * Display the specified client logo.
     */
    public function show(ClientLogo $clientLogo)
    {
        $this->authorize(
            'view',
            $clientLogo
        );

        $clientLogo->load('media');

        return response()->json([
            'success' => true,
            'data' => new ClientLogoResource(
                $clientLogo
            ),
        ]);
    }

    /**
     * Update the specified client logo.
     */
    public function update(
        UpdateClientLogoRequest $request,
        ClientLogo $clientLogo
    ) {
        $this->authorize(
            'update',
            $clientLogo
        );

        $clientLogo->update(
            $request->validated()
        );

        $clientLogo = $clientLogo->fresh();

        $clientLogo->load('media');

        return response()->json([
            'success' => true,
            'message' =>
                'Client logo updated successfully.',
            'data' => new ClientLogoResource(
                $clientLogo
            ),
        ]);
    }

    /**
     * Remove the specified client logo.
     */
    public function destroy(ClientLogo $clientLogo)
    {
        $this->authorize(
            'delete',
            $clientLogo
        );

        $clientLogo->delete();

        return response()->json([
            'success' => true,
            'message' =>
                'Client logo deleted successfully.',
        ]);
    }
}