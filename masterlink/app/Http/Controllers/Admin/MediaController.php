<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * عرض جميع الملفات
     */
    public function index()
    {
        $media = Media::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }


    /**
     * رفع ملف جديد
     */
    public function store(Request $request)
    {
        $request->validate([

            'file' => [
                'required',
                'file',
                'max:10240', // 10MB
            ],

            'media_type' => [
                'required',
                'in:image,video,document'
            ],

            'admin_id' => [
                'nullable',
                'exists:admins,id'
            ],

            'alt_text' => [
                'nullable',
                'string',
                'max:255'
            ],

        ]);


        $file = $request->file('file');


        // حفظ الملف داخل storage/app/public/media
        $path = $file->store('media', 'public');


        $media = Media::create([

            'admin_id' => $request->admin_id,

            'file_name' => $file->getClientOriginalName(),

            'file_path' => $path,

            'extension' => $file->getClientOriginalExtension(),

            'media_type' => $request->media_type,

            'mime_type' => $file->getMimeType(),

            'file_size' => $file->getSize(),

            'alt_text' => $request->alt_text,

        ]);


        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully',
            'data' => $media
        ]);
    }


    /**
     * عرض ملف واحد
     */
    public function show(Media $media)
    {
        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }


    /**
     * تحديث معلومات الملف
     */
    public function update(Request $request, Media $media)
    {

        $request->validate([

            'alt_text' => [
                'nullable',
                'string',
                'max:255'
            ],

            'media_type' => [
                'nullable',
                'in:image,video,document'
            ],

        ]);


        $media->update(
            $request->only([
                'alt_text',
                'media_type'
            ])
        );


        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'data' => $media
        ]);
    }


    /**
     * حذف ملف
     */
    public function destroy(Media $media)
    {

        // حذف الملف من التخزين
        if(Storage::disk('public')->exists($media->file_path))
        {
            Storage::disk('public')->delete($media->file_path);
        }


        // حذف من قاعدة البيانات
        $media->delete();


        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully'
        ]);
    }
}