<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    /**
     * عرض جميع إعدادات الموقع مقسمة حسب مجموعاتها
     */
    public function index()
    {
        $settings = SiteSetting::all()->groupBy('group_name');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * تحديث قيم الإعدادات دفعة واحدة
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'settings'         => 'required|array',
            'settings.*.id'    => 'required|exists:site_settings,id',
            'settings.*.value' => 'nullable|string',
        ]);

        foreach ($data['settings'] as $item) {
            SiteSetting::where('id', $item['id'])->update([
                'value' => $item['value'],
            ]);
        }

        return back()->with('success', 'تم حفظ إعدادات الموقع بنجاح.');
    }
}