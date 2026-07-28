<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Media;
use Illuminate\Http\Request;

class ServiceMediaController extends Controller
{
    /**
     * ربط ميديا بخدمة محددة
     */
    public function store(Request $request, Service $service)
    {
        $validated = $request->validate([
            'media_id'   => 'required|exists:media,id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $service->media()->attach($validated['media_id'], [
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'تم ربط ملف الميديا بالخدمة بنجاح.');
    }

    /**
     * تحديث ترتيب الميديا داخل الخدمة
     */
    public function update(Request $request, Service $service, Media $media)
    {
        $validated = $request->validate([
            'sort_order' => 'required|integer|min:0',
        ]);

        $service->media()->updateExistingPivot($media->id, [
            'sort_order' => $validated['sort_order'],
        ]);

        return back()->with('success', 'تم تحديث الترتيب بنجاح.');
    }

    /**
     * إلغاء ربط الميديا من الخدمة
     */
    public function destroy(Service $service, Media $media)
    {
        $service->media()->detach($media->id);

        return back()->with('success', 'تم إلغاء ربط الميديا بالخدمة.');
    }
}