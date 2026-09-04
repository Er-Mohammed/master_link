<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TestimonialController extends Controller
{
    /**
     * Display a listing of public active testimonials.
     */
    public function index(): AnonymousResourceCollection
    {
        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->with('media')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return TestimonialResource::collection($testimonials);
    }
}
