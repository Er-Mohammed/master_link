<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateConsultationRequest;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    /**
     * Display all consultations.
     */
    public function index()
    {
        $consultations = Consultation::with('service')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $consultations,
        ]);
    }

    /**
     * Display one consultation.
     */
    public function show(Consultation $consultation)
    {
        return response()->json([
            'success' => true,
            'data' => $consultation->load('service'),
        ]);
    }

    /**
     * Update consultation status.
     */
    public function update(
        UpdateConsultationRequest $request,
        Consultation $consultation
    ) {
        $consultation->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Consultation status updated successfully.',
            'data' => $consultation->fresh()->load('service'),
        ]);
    }

    /**
     * Delete consultation.
     */
    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Consultation deleted successfully.',
        ]);
    }
}