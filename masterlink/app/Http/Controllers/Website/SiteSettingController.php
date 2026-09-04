<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SiteSettingResource;
use App\Models\SiteSetting;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SiteSettingController extends Controller
{
    /**
     * Display a listing of public site settings.
     */
    public function index(): AnonymousResourceCollection
    {
        $settings = SiteSetting::query()
            ->orderBy('group_name')
            ->orderBy('key')
            ->get();

        return SiteSettingResource::collection($settings);
    }
}
