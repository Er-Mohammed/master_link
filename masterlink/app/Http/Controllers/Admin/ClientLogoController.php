<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientLogoRequest;
use App\Http\Requests\Admin\UpdateClientLogoRequest;
use App\Models\ClientLogo;

class ClientLogoController extends Controller
{
    /**
     * Display a listing of client logos.
     */
    public function index()
    {
        $logos = ClientLogo::with('media')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logos,
        ]);
    }

    /**
     * Store a newly created client logo.
     */
    public function store(StoreClientLogoRequest $request)
    {
        $logo = ClientLogo::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Client logo created successfully.',
            'data' => $logo->load('media'),
        ], 201);
    }

    /**
     * Display the specified client logo.
     */
    public function show(ClientLogo $clientLogo)
    {
        return response()->json([
            'success' => true,
            'data' => $clientLogo->load('media'),
        ]);
    }

    /**
     * Update the specified client logo.
     */
    public function update(
        UpdateClientLogoRequest $request,
        ClientLogo $clientLogo
    ) {
        $clientLogo->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Client logo updated successfully.',
            'data' => $clientLogo->fresh()->load('media'),
        ]);
    }

    /**
     * Remove the specified client logo.
     */
    public function destroy(ClientLogo $clientLogo)
    {
        $clientLogo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client logo deleted successfully.',
        ]);
    }
}