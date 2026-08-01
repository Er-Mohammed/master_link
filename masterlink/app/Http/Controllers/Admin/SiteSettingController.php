<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    /**
     * عرض جميع الإعدادات
     */
    public function index()
    {
        $settings = SiteSetting::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }


    /**
     * إنشاء إعداد جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'key' => [
                'required',
                'string',
                'max:100',
                'unique:site_settings,key'
            ],

            'value' => [
                'nullable',
                'string'
            ],

            'type' => [
                'nullable',
                'string',
                'max:50'
            ],

            'group_name' => [
                'nullable',
                'string',
                'max:100'
            ],

        ]);


        $setting = SiteSetting::create($validated);


        return response()->json([
            'success' => true,
            'message' => 'Site setting created successfully',
            'data' => $setting
        ], 201);
    }


    /**
     * عرض إعداد واحد
     */
    public function show(SiteSetting $siteSetting)
    {
        return response()->json([
            'success' => true,
            'data' => $siteSetting
        ]);
    }


    /**
     * تحديث إعداد
     */
    public function update(Request $request, SiteSetting $siteSetting)
    {
        $validated = $request->validate([

            'key' => [
                'nullable',
                'string',
                'max:100',
                'unique:site_settings,key,' . $siteSetting->id
            ],

            'value' => [
                'nullable',
                'string'
            ],

            'type' => [
                'nullable',
                'string',
                'max:50'
            ],

            'group_name' => [
                'nullable',
                'string',
                'max:100'
            ],

        ]);


        $siteSetting->update($validated);


        return response()->json([
            'success' => true,
            'message' => 'Site setting updated successfully',
            'data' => $siteSetting
        ]);
    }


    /**
     * حذف إعداد
     */
    public function destroy(SiteSetting $siteSetting)
    {
        $siteSetting->delete();


        return response()->json([
            'success' => true,
            'message' => 'Site setting deleted successfully'
        ]);
    }
}