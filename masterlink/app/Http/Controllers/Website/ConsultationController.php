<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultationRequest;
use App\Models\Consultation;
use Illuminate\Http\JsonResponse;

class ConsultationController extends Controller
{
    /**
     * Store a newly created consultation request.
     */
    public function store(StoreConsultationRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['service_id']) && !empty($data['services'])) {
            $data['service_id'] = $this->resolveServiceId($data['services']);
        }

        // Unset search helper `services` to avoid Eloquent mass assignment warnings / column mismatch
        unset($data['services']);

        $consultation = Consultation::create($data);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $consultation->id,
                'name' => $consultation->name,
                'email' => $consultation->email,
                'phone' => $consultation->phone,
                'company_name' => $consultation->company_name,
                'service_id' => $consultation->service_id,
                'message' => $consultation->message,
                'status' => $consultation->status,
                'created_at' => $consultation->created_at?->toISOString(),
            ],
        ], 201);
    }

    /**
     * Resolve a service id from an array of service names.
     */
    protected function resolveServiceId(?array $services): ?int
    {
        if (empty($services)) {
            return null;
        }

        $firstService = trim($services[0]);
        if (empty($firstService)) {
            return null;
        }

        // Try exact match first
        $exactService = \App\Models\Service::where('title', $firstService)->first();
        if ($exactService) {
            return $exactService->id;
        }

        // Predefined language map
        $map = [
            // Branding & Identity
            'العلامة التجارية والهوية' => ['الهوية', 'العلامة', 'Ident', 'Brand'],
            'Branding & Identity' => ['الهوية', 'العلامة', 'Ident', 'Brand'],

            // Website Development
            'تطوير مواقع الويب' => ['الموقع', 'موقع', 'Web', 'Dev'],
            'Website Development' => ['الموقع', 'موقع', 'Web', 'Dev'],

            // Mobile App Development
            'تطبيقات الهواتف المحمولة' => ['تطبي', 'الهواتف', 'App', 'Mobile'],
            'Mobile App Development' => ['تطبي', 'الهواتف', 'App', 'Mobile'],

            // Digital Marketing
            'التسويق الرقمي' => ['التسويق', 'تسويق', 'Market', 'Digital'],
            'Digital Marketing' => ['التسويق', 'تسويق', 'Market', 'Digital'],

            // Video Production
            'إنتاج الفيديو' => ['فيديو', 'تصوير', 'إعلاني', 'Video', 'Prod'],
            'Video Production' => ['فيديو', 'تصوير', 'إعلاني', 'Video', 'Prod'],

            // Company Profiles
            'الملفات التعريفية للمؤسسات' => ['الملفات', 'العلامة', 'Ident', 'Brand', 'Profile'],
            'Company Profiles' => ['الملفات', 'العلامة', 'Ident', 'Brand', 'Profile'],
        ];

        // If the service string is in our map, search database using associated keywords
        if (isset($map[$firstService])) {
            foreach ($map[$firstService] as $keyword) {
                $service = \App\Models\Service::where('title', 'like', "%{$keyword}%")->first();
                if ($service) {
                    return $service->id;
                }
            }
        }

        // Fuzzy match using the raw string
        $service = \App\Models\Service::where('title', 'like', "%{$firstService}%")->first();
        if ($service) {
            return $service->id;
        }

        // Final fallback: check if any keyword in the raw string matches database titles
        $words = explode(' ', $firstService);
        foreach ($words as $word) {
            if (strlen($word) > 3) {
                $service = \App\Models\Service::where('title', 'like', "%{$word}%")->first();
                if ($service) {
                    return $service->id;
                }
            }
        }

        return null;
    }
}
