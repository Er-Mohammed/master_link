<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * عرض جميع الشهادات
     */
    public function index()
    {
        $testimonials = Testimonial::with('media')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $testimonials
        ]);
    }


    /**
     * إنشاء شهادة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'media_id' => [
                'nullable',
                'exists:media,id'
            ],

            'display_name' => [
                'required',
                'string',
                'max:150'
            ],

            'message' => [
                'required',
                'string'
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


        $testimonial = Testimonial::create($validated);


        return response()->json([
            'success' => true,
            'message' => 'Testimonial created successfully',
            'data' => $testimonial->load('media')
        ], 201);
    }


    /**
     * عرض شهادة واحدة
     */
    public function show(Testimonial $testimonial)
    {
        return response()->json([
            'success' => true,
            'data' => $testimonial->load('media')
        ]);
    }


    /**
     * تحديث شهادة
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([

            'media_id' => [
                'nullable',
                'exists:media,id'
            ],

            'display_name' => [
                'nullable',
                'string',
                'max:150'
            ],

            'message' => [
                'nullable',
                'string'
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


        $testimonial->update($validated);


        return response()->json([
            'success' => true,
            'message' => 'Testimonial updated successfully',
            'data' => $testimonial->load('media')
        ]);
    }


    /**
     * حذف شهادة
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();


        return response()->json([
            'success' => true,
            'message' => 'Testimonial deleted successfully'
        ]);
    }
}