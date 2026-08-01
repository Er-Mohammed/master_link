<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientLogo;
use Illuminate\Http\Request;

class ClientLogoController extends Controller
{
    /**
     * عرض جميع شعارات العملاء
     */
    public function index()
    {
        $logos = ClientLogo::with('media')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logos
        ]);
    }


    /**
     * إنشاء شعار عميل جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'media_id' => [
                'required',
                'exists:media,id'
            ],

            'company_name' => [
                'required',
                'string',
                'max:150'
            ],

            'website_url' => [
                'nullable',
                'url'
            ],

            'sort_order' => [
                'nullable',
                'integer'
            ],

            'is_active' => [
                'nullable',
                'boolean'
            ],
        ]);


        $logo = ClientLogo::create($validated);


        return response()->json([
            'success' => true,
            'message' => 'Client logo created successfully',
            'data' => $logo->load('media')
        ], 201);
    }


    /**
     * عرض شعار محدد
     */
    public function show(ClientLogo $clientLogo)
    {
        return response()->json([
            'success' => true,
            'data' => $clientLogo->load('media')
        ]);
    }


    /**
     * تحديث شعار
     */
    public function update(Request $request, ClientLogo $clientLogo)
    {
        $validated = $request->validate([

            'media_id' => [
                'nullable',
                'exists:media,id'
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:150'
            ],

            'website_url' => [
                'nullable',
                'url'
            ],

            'sort_order' => [
                'nullable',
                'integer'
            ],

            'is_active' => [
                'nullable',
                'boolean'
            ],

        ]);


        $clientLogo->update($validated);


        return response()->json([
            'success' => true,
            'message' => 'Client logo updated successfully',
            'data' => $clientLogo->load('media')
        ]);
    }


    /**
     * حذف شعار
     */
    public function destroy(ClientLogo $clientLogo)
    {
        $clientLogo->delete();


        return response()->json([
            'success' => true,
            'message' => 'Client logo deleted successfully'
        ]);
    }
}