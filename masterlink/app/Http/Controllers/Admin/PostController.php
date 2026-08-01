<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => ['nullable', 'exists:admins,id'],
            'media_id' => ['nullable', 'exists:media,id'],
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:220', 'unique:posts,slug'],
            'short_description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $post = Post::create($validated);

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
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'admin_id' => ['sometimes', 'nullable', 'exists:admins,id'],
            'media_id' => ['sometimes', 'nullable', 'exists:media,id'],
            'title' => ['sometimes', 'string', 'max:200'],
            'slug' => [
                'sometimes',
                'string',
                'max:220',
                Rule::unique('posts', 'slug')->ignore($post->id),
            ],
            'short_description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $post->update($validated);

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