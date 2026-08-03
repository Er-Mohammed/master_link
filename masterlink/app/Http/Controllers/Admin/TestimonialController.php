<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials.
     */
    public function index()
    {
        $testimonials = Testimonial::with('media')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $testimonials,
        ]);
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(StoreTestimonialRequest $request)
    {
        $testimonial = Testimonial::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Testimonial created successfully.',
            'data' => $testimonial->load('media'),
        ], 201);
    }

    /**
     * Display the specified testimonial.
     */
    public function show(Testimonial $testimonial)
    {
        return response()->json([
            'success' => true,
            'data' => $testimonial->load('media'),
        ]);
    }

    /**
     * Update the specified testimonial.
     */
    public function update(
        UpdateTestimonialRequest $request,
        Testimonial $testimonial
    ) {
        $testimonial->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Testimonial updated successfully.',
            'data' => $testimonial->fresh()->load('media'),
        ]);
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial deleted successfully.',
        ]);
    }
}