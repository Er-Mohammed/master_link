<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceController extends Controller
{
    /**
     * Display a listing of active public services.
     */
    public function index(): AnonymousResourceCollection
    {
        $services = Service::query()
            ->where('is_active', true)
            ->with(['media'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return ServiceResource::collection($services);
    }
}
