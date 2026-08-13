<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateConsultationRequest;
use App\Http\Resources\Admin\ConsultationResource;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Display a listing of consultations.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Consultation::class);

        $perPage = min(
            max((int) $request->input('per_page', 15), 1),
            100
        );

        $query = Consultation::query()
            ->with('service');

        /*
        |--------------------------------------------------------------------------
        | Filter by status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $allowedStatuses = [
                'new',
                'contacted',
                'in_progress',
                'completed',
                'cancelled',
            ];

            $status = $request->input('status');

            if (in_array($status, $allowedStatuses, true)) {
                $query->where('status', $status);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by service
        |--------------------------------------------------------------------------
        */

        if ($request->filled('service_id')) {
            $query->where(
                'service_id',
                $request->input('service_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim(
                $request->input('search')
            );

            $query->where(function ($q) use ($search) {
                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'company_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'message',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'name',
            'company_name',
            'status',
            'created_at',
            'updated_at',
        ];

        $sort = $request->input(
            'sort',
            'created_at'
        );

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $direction = strtolower(
            $request->input(
                'direction',
                'desc'
            )
        );

        if (! in_array(
            $direction,
            ['asc', 'desc'],
            true
        )) {
            $direction = 'desc';
        }

        $query->orderBy(
            $sort,
            $direction
        );

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $consultations = $query
            ->paginate($perPage)
            ->withQueryString();

        return ConsultationResource::collection(
            $consultations
        );
    }

    /**
     * Display the specified consultation.
     */
    public function show(
        Consultation $consultation
    ) {
        $this->authorize(
            'view',
            $consultation
        );

        $consultation->load('service');

        return response()->json([
            'success' => true,
            'data' => new ConsultationResource(
                $consultation
            ),
        ]);
    }

    /**
     * Update the consultation status.
     */
    public function update(
        UpdateConsultationRequest $request,
        Consultation $consultation
    ) {
        $this->authorize(
            'update',
            $consultation
        );

        $consultation->update(
            $request->validated()
        );

        $consultation->load('service');

        return response()->json([
            'success' => true,
            'message' =>
                'Consultation status updated successfully.',
            'data' => new ConsultationResource(
                $consultation
            ),
        ]);
    }

    /**
     * Remove the specified consultation.
     */
    public function destroy(
        Consultation $consultation
    ) {
        $this->authorize(
            'delete',
            $consultation
        );

        $consultation->delete();

        return response()->json([
            'success' => true,
            'message' =>
                'Consultation deleted successfully.',
        ]);
    }
}