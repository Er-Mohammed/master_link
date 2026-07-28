<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConsultationController extends Controller
{
    /**
     * عرض قائمة الاستشارات (للوحة التحكم)
     */
    public function index(Request $request)
    {
        // إمكانية التصفية حسب الحالة (new, pending, completed)
        $query = Consultation::with('service')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $consultations = $query->paginate(15);

        return view('consultations.index', compact('consultations'));
    }

    /**
     * عرض نموذج طلب استشارة (في الموقع أو لوحة التحكم)
     */
    public function create()
    {
        $services = Service::where('is_active', true)->get();
        return view('consultations.create', compact('services'));
    }

    /**
     * استقبال وحفظ طلب استشارة جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'service_id'   => 'required|exists:services,id',
            'message'      => 'required|string',
            'status'       => ['nullable', Rule::in(['new', 'pending', 'completed'])],
        ]);

        // تعيين الحالة الافتراضية "new" إذا لم تُحدد
        $validated['status'] = $validated['status'] ?? 'new';

        Consultation::create($validated);

        return redirect()->back()
            ->with('success', 'تم إرسال طلب الاستشارة بنجاح، وسنتواصل معك قريباً.');
    }

    /**
     * عرض تفاصيل استشارة معينة
     */
    public function show(Consultation $consultation)
    {
        $consultation->load('service');
        return view('consultations.show', compact('consultation'));
    }

    /**
     * عرض صفحة تعديل الاستشارة (لتغيير الحالة أو البيانات من الآدمن)
     */
    public function edit(Consultation $consultation)
    {
        $services = Service::all();
        return view('consultations.edit', compact('consultation', 'services'));
    }

    /**
     * تحديث بيانات الاستشارة أو تغيير حالتها
     */
    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'service_id'   => 'required|exists:services,id',
            'message'      => 'required|string',
            'status'       => ['required', Rule::in(['new', 'pending', 'completed'])],
        ]);

        $consultation->update($validated);

        return redirect()->route('consultations.index')
            ->with('success', 'تم تحديث طلب الاستشارة بنجاح.');
    }

    /**
     * حذف طلب استشارة
     */
    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        return redirect()->route('consultations.index')
            ->with('success', 'تم حذف طلب الاستشارة بنجاح.');
    }
}