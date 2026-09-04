<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProjectCategoryResource;
use App\Models\ProjectCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectCategoryController extends Controller
{
    /**
     * Display a listing of active public project categories.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = ProjectCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return ProjectCategoryResource::collection($categories);
    }
}
