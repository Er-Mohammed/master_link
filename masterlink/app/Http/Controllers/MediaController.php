<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * عرض قائمة جميع الوسائط
     */
    public function index(Request $request)
    {
        $query = Media::with('admin')->latest();

        // التصفية حسب نوع الوسائط (image أو video)
        if ($request->filled('media_type')) {
            $query->where('media_type', $request->media_type);
        }

        $mediaFiles = $query->paginate(20);

        return view('media.index', compact('mediaFiles'));
    }

    /**
     * عرض صفحة رفع وسائط جديدة
     */
    public function create()
    {
        return view('media.create');
    }

    /**
     * رفع وحفظ ملف وسائط جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'file'     => 'required|file|mimes:jpg,jpeg,png,webp,mp4|max:20480', // حد أقصى 20 ميجابايت
            'alt_text' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $originalFileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // تحديد نوع الوسائط بناءً على الامتداد
        $mediaType = ($extension === 'mp4') ? 'video' : 'image';

        // حساب الأبعاد إن كان الملف صورة
        $width = null;
        $height = null;
        if ($mediaType === 'image') {
            $imageDimensions = @getimagesize($file->getRealPath());
            if ($imageDimensions) {
                $width  = $imageDimensions[0];
                $height = $imageDimensions[1];
            }
        }

        // رفع الملف وتخزينه في مجلد public/media
        $filePath = $file->store('media', 'public');

        Media::create([
            'admin_id'  => Auth::id() ?? 1, // معرف المسؤول المسجل
            'file_name' => $originalFileName,
            'file_path' => $filePath,
            'extension' => $extension,
            'media_type'=> $mediaType,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'width'     => $width,
            'height'    => $height,
            'alt_text'  => $request->input('alt_text'),
        ]);

        return redirect()->route('media.index')
            ->with('success', 'تم رفع ملف الوسائط بنجاح.');
    }

    /**
     * عرض بيانات ملف وسائط محدد
     */
    public function show(Media $medium)
    {
        $medium->load('admin');
        return view('media.show', compact('medium'));
    }

    /**
     * عرض صفحة تعديل النص البديل (alt_text) أو اسم الملف
     */
    public function edit(Media $medium)
    {
        return view('media.edit', compact('medium'));
    }

    /**
     * تحديث بيانات النص البديل
     */
    public function update(Request $request, Media $medium)
    {
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
        ]);

        $medium->update($validated);

        return redirect()->route('media.index')
            ->with('success', 'تم تحديث بيانات الوسائط بنجاح.');
    }

    /**
     * حذف ملف الوسائط من السيرفر وقاعدة البيانات
     */
    public function destroy(Media $medium)
    {
        // حذف الملف الفعلي من التخزين
        if (Storage::disk('public')->exists($medium->file_path)) {
            Storage::disk('public')->delete($medium->file_path);
        }

        // حذف السجل من قاعدة البيانات
        $medium->delete();

        return redirect()->route('media.index')
            ->with('success', 'تم حذف ملف الوسائط بنجاح.');
    }
}