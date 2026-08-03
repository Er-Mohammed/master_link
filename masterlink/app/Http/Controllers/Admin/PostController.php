<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['admin', 'media'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully.',
            'data' => $post->load(['admin', 'media']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['admin', 'media']);

        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(
        UpdatePostRequest $request,
        Post $post
    ) {
        $post->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully.',
            'data' => $post->fresh()->load(['admin', 'media']),
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully.',
        ]);
    }
}