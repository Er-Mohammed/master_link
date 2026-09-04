<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    /**
     * Display a listing of public active projects.
     */
    public function index(): AnonymousResourceCollection
    {
        $projects = Project::query()
            ->where('is_active', true)
            ->with(['category', 'media', 'services'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return ProjectResource::collection($projects);
    }
}
