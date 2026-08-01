<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;

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
    public function store(StoreServiceRequest $request)
    {
        $service = Service::create(
            $request->validated()
        );


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
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update(
            $request->validated()
        );


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