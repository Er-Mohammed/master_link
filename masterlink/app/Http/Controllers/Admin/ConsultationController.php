<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * عرض جميع طلبات الاستشارة
     */
    public function index()
    {
        $consultations = Consultation::with('service')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $consultations
        ]);
    }


    /**
     * عرض طلب استشارة واحد
     */
    public function show(Consultation $consultation)
    {
        return response()->json([
            'success' => true,
            'data' => $consultation->load('service')
        ]);
    }


    /**
     * تحديث حالة الطلب
     */
    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([

            'status' => [
                'required',
                'string',
                'max:50'
            ],

        ]);


        $consultation->update($validated);


        return response()->json([
            'success' => true,
            'message' => 'Consultation status updated successfully',
            'data' => $consultation->load('service')
        ]);
    }


    /**
     * حذف طلب الاستشارة
     */
    public function destroy(Consultation $consultation)
    {
        $consultation->delete();


        return response()->json([
            'success' => true,
            'message' => 'Consultation deleted successfully'
        ]);
    }
}