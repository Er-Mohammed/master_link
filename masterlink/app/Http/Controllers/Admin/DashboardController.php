<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Consultation;
use App\Models\Media;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get aggregated admin dashboard statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        $projectsCount = Project::count();
        $servicesCount = Service::count();
        $newConsultationsCount = Consultation::where('status', 'new')->count();
        $mediaCount = Media::count();
        $mediaTotalSize = (int) Media::sum('file_size');
        $activeAdminsCount = Admin::where('is_active', true)->count();
        $totalAdminsCount = Admin::count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projects_count' => $projectsCount,
                'services_count' => $servicesCount,
                'new_consultations_count' => $newConsultationsCount,
                'media_count' => $mediaCount,
                'media_total_size' => $mediaTotalSize,
                'admins_count' => $activeAdminsCount,
                'total_admins_count' => $totalAdminsCount,
            ],
        ]);
    }
}
