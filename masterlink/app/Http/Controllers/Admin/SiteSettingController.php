<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSiteSettingRequest;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Models\SiteSetting;

class SiteSettingController extends Controller
{
    /**
     * Display all settings.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => SiteSetting::orderBy('group_name')
                ->orderBy('key')
                ->get(),
        ]);
    }

    /**
     * Store a new setting.
     */
    public function store(StoreSiteSettingRequest $request)
    {
        $setting = SiteSetting::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Site setting created successfully.',
            'data' => $setting,
        ], 201);
    }

    /**
     * Display one setting.
     */
    public function show(SiteSetting $siteSetting)
    {
        return response()->json([
            'success' => true,
            'data' => $siteSetting,
        ]);
    }

    /**
     * Update a setting.
     */
    public function update(
        UpdateSiteSettingRequest $request,
        SiteSetting $siteSetting
    ) {
        $siteSetting->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Site setting updated successfully.',
            'data' => $siteSetting->fresh(),
        ]);
    }

    /**
     * Delete a setting.
     */
    public function destroy(SiteSetting $siteSetting)
    {
        $siteSetting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Site setting deleted successfully.',
        ]);
    }
}