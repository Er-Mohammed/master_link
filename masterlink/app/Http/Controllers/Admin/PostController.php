<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Http\Resources\Admin\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Post::class);

        $perPage = min(
            max((int) $request->input('per_page', 15), 1),
            100
        );

        $query = Post::query()
            ->with([
                'admin',
                'media',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Filtering
        |--------------------------------------------------------------------------
        */

        if ($request->filled('is_active')) {
            $query->where(
                'is_active',
                filter_var(
                    $request->input('is_active'),
                    FILTER_VALIDATE_BOOLEAN
                )
            );
        }

        if ($request->filled('is_featured')) {
            $query->where(
                'is_featured',
                filter_var(
                    $request->input('is_featured'),
                    FILTER_VALIDATE_BOOLEAN
                )
            );
        }

        if ($request->filled('status')) {
            $status = $request->input('status');

            if ($status === 'published') {
                $query->whereNotNull('published_at')
                    ->where(
                        'published_at',
                        '<=',
                        now()
                    );
            }

            if ($status === 'draft') {
                $query->where(function ($q) {
                    $q->whereNull('published_at')
                        ->orWhere(
                            'published_at',
                            '>',
                            now()
                        );
                });
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
                    'title',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'slug',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'short_description',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'content',
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
            'title',
            'published_at',
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
        | Pagination
        |--------------------------------------------------------------------------
        */

        $posts = $query
            ->paginate($perPage)
            ->withQueryString();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created post.
     */
    public function store(StorePostRequest $request)
    {
        $this->authorize(
            'create',
            Post::class
        );

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Assign authenticated admin as author
        |--------------------------------------------------------------------------
        */

        $validated['admin_id'] = $request
            ->user()
            ->id;

        $post = Post::create(
            $validated
        );

        $post->load([
            'admin',
            'media',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully.',
            'data' => new PostResource($post),
        ], 201);
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        $this->authorize(
            'view',
            $post
        );

        $post->load([
            'admin',
            'media',
        ]);

        return response()->json([
            'success' => true,
            'data' => new PostResource($post),
        ]);
    }

    /**
     * Update the specified post.
     */
    public function update(
        UpdatePostRequest $request,
        Post $post
    ) {
        $this->authorize(
            'update',
            $post
        );

        $post->update(
            $request->validated()
        );

        $post = $post->fresh();

        $post->load([
            'admin',
            'media',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully.',
            'data' => new PostResource($post),
        ]);
    }

    /**
     * Remove the specified post.
     */
    public function destroy(Post $post)
    {
        $this->authorize(
            'delete',
            $post
        );

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully.',
        ]);
    }
}