<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaRequest;
use App\Http\Requests\Admin\UpdateMediaRequest;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of media.
     */
    public function index()
    {
        $media = Media::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $media,
        ]);
    }

    /**
     * Store a newly uploaded media file.
     */
    public function store(StoreMediaRequest $request)
    {
        $file = $request->file('file');

        $path = $file->store('media', 'public');

        $media = Media::create([

            'admin_id'   => $request->validated()['admin_id'] ?? null,

            'file_name'  => $file->getClientOriginalName(),

            'file_path'  => $path,

            'extension'  => $file->getClientOriginalExtension(),

            'media_type' => $request->validated()['media_type'],

            'mime_type'  => $file->getMimeType(),

            'file_size'  => $file->getSize(),

            'alt_text'   => $request->validated()['alt_text'] ?? null,

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully.',
            'data' => $media,
        ], 201);
    }

    /**
     * Display the specified media.
     */
    public function show(Media $media)
    {
        return response()->json([
            'success' => true,
            'data' => $media,
        ]);
    }

    /**
     * Update the specified media.
     */
    public function update(UpdateMediaRequest $request, Media $media)
    {
        $media->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully.',
            'data' => $media,
        ]);
    }

    /**
     * Remove the specified media.
     */
    public function destroy(Media $media)
    {
        if (
            $media->file_path &&
            Storage::disk('public')->exists($media->file_path)
        ) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully.',
        ]);
    }
}