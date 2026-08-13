<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSiteSettingRequest;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Http\Resources\Admin\SiteSettingResource;
use App\Models\SiteSetting;

class SiteSettingController extends Controller
{
    /**
     * Display all settings.
     */
    public function index()
    {
        $this->authorize(
            'viewAny',
            SiteSetting::class
        );

        $settings = SiteSetting::query()
            ->orderBy('group_name')
            ->orderBy('key')
            ->get();

        return SiteSettingResource::collection(
            $settings
        );
    }

    /**
     * Store a new setting.
     */
    public function store(
        StoreSiteSettingRequest $request
    ) {
        $this->authorize(
            'create',
            SiteSetting::class
        );

        $setting = SiteSetting::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' =>
                'Site setting created successfully.',
            'data' => new SiteSettingResource(
                $setting
            ),
        ], 201);
    }

    /**
     * Display one setting.
     */
    public function show(
        SiteSetting $siteSetting
    ) {
        $this->authorize(
            'view',
            $siteSetting
        );

        return response()->json([
            'success' => true,
            'data' => new SiteSettingResource(
                $siteSetting
            ),
        ]);
    }

    /**
     * Update a setting.
     */
    public function update(
        UpdateSiteSettingRequest $request,
        SiteSetting $siteSetting
    ) {
        $this->authorize(
            'update',
            $siteSetting
        );

        $siteSetting->update(
            $request->validated()
        );

        $siteSetting = $siteSetting->fresh();

        return response()->json([
            'success' => true,
            'message' =>
                'Site setting updated successfully.',
            'data' => new SiteSettingResource(
                $siteSetting
            ),
        ]);
    }

    /**
     * Delete a setting.
     */
    public function destroy(
        SiteSetting $siteSetting
    ) {
        $this->authorize(
            'delete',
            $siteSetting
        );

        $siteSetting->delete();

        return response()->json([
            'success' => true,
            'message' =>
                'Site setting deleted successfully.',
        ]);
    }
}
