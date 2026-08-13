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
    public function index()
    {
        $this->authorize(
            'viewAny',
            ClientLogo::class
        );

        $logos = ClientLogo::query()
            ->with('media')
            ->latest()
            ->get();

        return ClientLogoResource::collection(
            $logos
        );
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