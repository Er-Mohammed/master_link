<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index()
    {
        $services = Service::with('media')
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }


    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'title' => 'required|string|max:255',

            'slug' => 'required|string|max:255|unique:services,slug',

            'short_description' => 'nullable|string',

            'full_description' => 'nullable|string',

            'sort_order' => 'nullable|integer',

            'is_active' => 'boolean',

        ]);


        $service = Service::create($validated);


        return response()->json([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => $service
        ], 201);
    }


    /**
     * Display specific service.
     */
    public function show(Service $service)
    {
        $service->load('media');


        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }


    /**
     * Update service.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([

            'title' => 'sometimes|string|max:255',

            'slug' => 'sometimes|string|max:255|unique:services,slug,' . $service->id,

            'short_description' => 'nullable|string',

            'full_description' => 'nullable|string',

            'sort_order' => 'nullable|integer',

            'is_active' => 'boolean',

        ]);


        $service->update($validated);


        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => $service
        ]);
    }


    /**
     * Delete service.
     */
    public function destroy(Service $service)
    {
        $service->delete();


        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ]);
    }
}