<?php

namespace App\Http\Controllers;

use App\Models\ClientLogo;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientLogoController extends Controller
{
    /**
     * عرض قائمة شعارات العملاء
     */
    public function index()
    {
        // جلب الشعارات مرتبة حسب الترتيب المحدد sort_order مع جلب الوسائط المرتبطة
        $clientLogos = ClientLogo::with('media')
            ->orderBy('sort_order', 'asc')
            ->paginate(15);

        return view('client_logos.index', compact('clientLogos'));
    }

    /**
     * عرض صفحة إضافة شعار جديد
     */
    public function create()
    {
        // جلب الوسائط لاستعراضها واختيار الشعار منها
        $mediaFiles = Media::latest()->get();
        return view('client_logos.create', compact('mediaFiles'));
    }

    /**
     * حفظ شعار عميل جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'media_id'     => 'nullable|exists:media,id',
            'company_name' => 'required|string|max:255',
            'website_url'  => 'nullable|url|max:255',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active']  = $request->has('is_active') ? $request->boolean('is_active') : true;

        ClientLogo::create($validated);

        return redirect()->route('client-logos.index')
            ->with('success', 'تم إضافة شعار العميل بنجاح.');
    }

    /**
     * عرض بيانات شعار محدد
     */
    public function show(ClientLogo $clientLogo)
    {
        $clientLogo->load('media');
        return view('client_logos.show', compact('clientLogo'));
    }

    /**
     * عرض صفحة تعديل الشعار
     */
    public function edit(ClientLogo $clientLogo)
    {
        $mediaFiles = Media::latest()->get();
        return view('client_logos.edit', compact('clientLogo', 'mediaFiles'));
    }

    /**
     * تحديث بيانات الشعار في قاعدة البيانات
     */
    public function update(Request $request, ClientLogo $clientLogo)
    {
        $validated = $request->validate([
            'media_id'     => 'nullable|exists:media,id',
            'company_name' => 'required|string|max:255',
            'website_url'  => 'nullable|url|max:255',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active']  = $request->boolean('is_active');

        $clientLogo->update($validated);

        return redirect()->route('client-logos.index')
            ->with('success', 'تم تحديث بيانات شعار العميل بنجاح.');
    }

    /**
     * حذف شعار من قاعدة البيانات
     */
    public function destroy(ClientLogo $clientLogo)
    {
        $clientLogo->delete();

        return redirect()->route('client-logos.index')
            ->with('success', 'تم حذف شعار العميل بنجاح.');
    }
}