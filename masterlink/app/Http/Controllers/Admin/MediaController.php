<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaRequest;
use App\Http\Requests\Admin\UpdateMediaRequest;
use App\Http\Resources\Admin\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of media.
     *
     * Supports:
     * - Pagination
     * - Filtering by media_type
     * - Searching by file_name, extension, mime_type, alt_text
     * - Sorting by allowed columns
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Media::class);

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = min(
            max(
                (int) $request->input('per_page', 15),
                1
            ),
            100
        );

        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = Media::query();

        /*
        |--------------------------------------------------------------------------
        | Filtering
        |--------------------------------------------------------------------------
        */

        if ($request->filled('media_type')) {
            $mediaType = $request->input('media_type');

            if (in_array(
                $mediaType,
                ['image', 'video', 'document'],
                true
            )) {
                $query->where('media_type', $mediaType);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Searching
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim(
                $request->input('search')
            );

            $query->where(function ($q) use ($search) {
                $q->where(
                    'file_name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'extension',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'mime_type',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'alt_text',
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
            'file_name',
            'media_type',
            'file_size',
            'created_at',
            'updated_at',
        ];

        $sort = $request->input(
            'sort',
            'created_at'
        );

        if (! in_array(
            $sort,
            $allowedSorts,
            true
        )) {
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
        | Pagination Result
        |--------------------------------------------------------------------------
        */

        $media = $query
            ->paginate($perPage)
            ->withQueryString();

        return MediaResource::collection($media);
    }

    /**
     * Store a newly uploaded media file.
     */
    public function store(StoreMediaRequest $request)
    {
        $this->authorize(
            'create',
            Media::class
        );

        $file = $request->file('file');

        $path = $file->store(
            'media',
            'public'
        );

        $media = Media::create([
            'admin_id' => $request->user()->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'extension' => $file->getClientOriginalExtension(),
            'media_type' => $request->validated('media_type'),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'width' => $this->getImageWidth($file),
            'height' => $this->getImageHeight($file),
            'alt_text' => $request->validated('alt_text'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded successfully.',
            'data' => new MediaResource($media),
        ], 201);
    }

    /**
     * Display the specified media.
     */
    public function show(Media $medium)
    {
        $this->authorize(
            'view',
            $medium
        );

        return response()->json([
            'success' => true,
            'data' => new MediaResource($medium),
        ]);
    }

    /**
     * Update the specified media.
     */
    public function update(
        UpdateMediaRequest $request,
        Media $medium
    ) {
        $this->authorize(
            'update',
            $medium
        );

        $medium->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully.',
            'data' => new MediaResource(
                $medium->fresh()
            ),
        ]);
    }

    /**
     * Remove the specified media.
     */
    public function destroy(Media $medium)
    {
        $this->authorize(
            'delete',
            $medium
        );

        if (
            $medium->file_path &&
            Storage::disk('public')->exists(
                $medium->file_path
            )
        ) {
            Storage::disk('public')->delete(
                $medium->file_path
            );
        }

        $medium->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully.',
        ]);
    }

    /**
     * Get image width.
     */
    private function getImageWidth($file): ?int
    {
        if (! str_starts_with(
            $file->getMimeType() ?? '',
            'image/'
        )) {
            return null;
        }

        $dimensions = @getimagesize(
            $file->getRealPath()
        );

        return $dimensions[0] ?? null;
    }

    /**
     * Get image height.
     */
    private function getImageHeight($file): ?int
    {
        if (! str_starts_with(
            $file->getMimeType() ?? '',
            'image/'
        )) {
            return null;
        }

        $dimensions = @getimagesize(
            $file->getRealPath()
        );

        return $dimensions[1] ?? null;
    }
}
