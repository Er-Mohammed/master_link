<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClientLogoResource;
use App\Models\ClientLogo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientLogoController extends Controller
{
    /**
     * Display a listing of public active client logos.
     */
    public function index(): AnonymousResourceCollection
    {
        $logos = ClientLogo::query()
            ->where('is_active', true)
            ->with('media')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return ClientLogoResource::collection($logos);
    }
}
