<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Http\Resources\Admin\TestimonialResource;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials.
     */
    public function index()
    {
        $this->authorize('viewAny', Testimonial::class);

        $testimonials = Testimonial::query()
            ->with('media')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return TestimonialResource::collection($testimonials);
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(StoreTestimonialRequest $request)
    {
        $this->authorize('create', Testimonial::class);

        $testimonial = Testimonial::create(
            $request->validated()
        );

        $testimonial->load('media');

        return response()->json([
            'success' => true,
            'message' => 'Testimonial created successfully.',
            'data' => new TestimonialResource($testimonial),
        ], 201);
    }

    /**
     * Display the specified testimonial.
     */
    public function show(Testimonial $testimonial)
    {
        $this->authorize('view', $testimonial);

        $testimonial->load('media');

        return response()->json([
            'success' => true,
            'data' => new TestimonialResource($testimonial),
        ]);
    }

    /**
     * Update the specified testimonial.
     */
    public function update(
        UpdateTestimonialRequest $request,
        Testimonial $testimonial
    ) {
        $this->authorize('update', $testimonial);

        $testimonial->update(
            $request->validated()
        );

        $testimonial = $testimonial->fresh();

        $testimonial->load('media');

        return response()->json([
            'success' => true,
            'message' => 'Testimonial updated successfully.',
            'data' => new TestimonialResource($testimonial),
        ]);
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(Testimonial $testimonial)
    {
        $this->authorize('delete', $testimonial);

        $testimonial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial deleted successfully.',
        ]);
    }
}
