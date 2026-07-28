<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Media;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * عرض قائمة آراء العملاء
     */
    public function index()
    {
        $testimonials = Testimonial::with('media')
            ->orderBy('sort_order', 'asc')
            ->latest()
            ->paginate(10);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * صفحة إضافة رأي عميل جديد
     */
    public function create()
    {
        $mediaFiles = Media::latest()->get();
        return view('admin.testimonials.create', compact('mediaFiles'));
    }

    /**
     * حفظ رأي العميل
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'media_id'     => 'nullable|exists:media,id',
            'display_name' => 'required|string|max:255',
            'message'      => 'required|string',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['is_active']  = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Testimonial::create($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'تم إضافة رأي العميل بنجاح.');
    }

    /**
     * صفحة تعديل رأي العميل
     */
    public function edit(Testimonial $testimonial)
    {
        $mediaFiles = Media::latest()->get();
        return view('admin.testimonials.edit', compact('testimonial', 'mediaFiles'));
    }

    /**
     * تحديث بيانات رأي العميل
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'media_id'     => 'nullable|exists:media,id',
            'display_name' => 'required|string|max:255',
            'message'      => 'required|string',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['is_active']  = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $testimonial->update($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'تم تحديث البيانات بنجاح.');
    }

    /**
     * حذف رأي العميل
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'تم حذف رأي العميل بنجاح.');
    }
}