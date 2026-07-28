<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectMediaController extends Controller
{
    /**
     * عرض جميع وسائط مشروع محدد إدارة الترتيب
     */
    public function index(Project $project)
    {
        // جلب جميع وسائل الإعلام المربوطة بالمشروع مرتبة بحسب sort_order
        $projectMedia = $project->media()->orderBy('project_media.sort_order', 'asc')->get();

        // جلب بقية الوسائط غير المربوطة بهذا المشروع لإتاحة إضافتها
        $availableMedia = Media::whereNotIn('id', $projectMedia->pluck('id'))->latest()->get();

        return view('projects.media.index', compact('project', 'projectMedia', 'availableMedia'));
    }

    /**
     * ربط وسيط (Media) بمشروع معين
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'media_id'   => 'required|exists:media,id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // التحقق من عدم ربط الصورة سابقاً بالمشروع
        if ($project->media()->where('media_id', $validated['media_id'])->exists()) {
            return redirect()->back()->with('error', 'هذا الملف مرتبط بالفعل بالمشروع.');
        }

        $sortOrder = $validated['sort_order'] ?? ($project->media()->max('project_media.sort_order') + 1);

        // إرفاق الوسيط في الجدول الوسيط project_media
        $project->media()->attach($validated['media_id'], [
            'sort_order' => $sortOrder,
        ]);

        return redirect()->back()->with('success', 'تم إضافة الوسيط إلى المشروع بنجاح.');
    }

    /**
     * تحديث ترتيب الوسائط المرفقة بالمشروع (Sort Order)
     */
    public function updateOrder(Request $request, Project $project)
    {
        $validated = $request->validate([
            'items'              => 'required|array',
            'items.*.media_id'   => 'required|exists:media,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['items'] as $item) {
            $project->media()->updateExistingPivot($item['media_id'], [
                'sort_order' => $item['sort_order'],
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'تم تحديث ترتيب الوسائط بنجاح.',
        ]);
    }

    /**
     * فك ربط وسيط من مشروع (دون حذف الملف الأصلي من جدول Media)
     */
    public function destroy(Project $project, Media $media)
    {
        $project->media()->detach($media->id);

        return redirect()->back()->with('success', 'تم إزالة الوسيط من المشروع بنجاح.');
    }
}