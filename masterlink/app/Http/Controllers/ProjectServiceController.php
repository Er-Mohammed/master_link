<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;

class ProjectServiceController extends Controller
{
    /**
     * عرض الخدمات المربوطة بمشروع محدد وإتاحة اختيار خدمات جديدة
     */
    public function index(Project $project)
    {
        $projectServices = $project->services;
        $availableServices = Service::where('is_active', true)->get();

        return view('projects.services.index', compact('project', 'projectServices', 'availableServices'));
    }

    /**
     * مزامنة أو ربط مجموعة خدمات بالمشروع (Attach / Sync)
     */
    public function sync(Request $request, Project $project)
    {
        $validated = $request->validate([
            'service_ids'   => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
        ]);

        // استخدام sync لتحديث الخدمات المربوطة دفعة واحدة
        $project->services()->sync($validated['service_ids'] ?? []);

        return redirect()->back()->with('success', 'تم تحديث خدمات المشروع بنجاح.');
    }

    /**
     * إزالة خدمة معينة من المشروع
     */
    public function destroy(Project $project, Service $service)
    {
        $project->services()->detach($service->id);

        return redirect()->back()->with('success', 'تم إزالة الخدمة من المشروع بنجاح.');
    }
}