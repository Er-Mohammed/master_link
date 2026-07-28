<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * عرض قائمة المقالات
     */
    public function index(Request $request)
    {
        $query = Post::with(['admin', 'media'])->latest();

        // إمكانية البحث حسب العنوان
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // التصفية حسب حالة النشر
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $posts = $query->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * عرض صفحة إنشاء مقال جديد
     */
    public function create()
    {
        $mediaFiles = Media::where('media_type', 'image')->latest()->get();
        return view('posts.create', compact('mediaFiles'));
    }

    /**
     * حفظ مقال جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'media_id'          => 'nullable|exists:media,id',
            'short_description' => 'nullable|string|max:500',
            'content'           => 'required|string',
            'published_at'      => 'nullable|date',
            'is_featured'       => 'boolean',
            'is_active'          => 'boolean',
        ]);

        // توليد الـ slug بشكل آلي وفريد
        $validated['slug'] = Str::slug($validated['title']);
        
        // التحقق من تكرار الـ slug وإنشائه برقم عشوائي إن وُجد
        if (Post::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $validated['slug'] . '-' . time();
        }

        // تعيين معرّف المسؤول الحالي والتحقق من التحديدات
        $validated['admin_id']    = Auth::id() ?? 1;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->has('is_active') ? $request->boolean('is_active') : true;

        Post::create($validated);

        return redirect()->route('posts.index')
            ->with('success', 'تم إنشاء المقال بنجاح.');
    }

    /**
     * عرض تفاصيل مقال محدد
     */
    public function show(Post $post)
    {
        $post->load(['admin', 'media']);
        return view('posts.show', compact('post'));
    }

    /**
     * عرض صفحة تعديل مقال
     */
    public function edit(Post $post)
    {
        $mediaFiles = Media::where('media_type', 'image')->latest()->get();
        return view('posts.edit', compact('post', 'mediaFiles'));
    }

    /**
     * تحديث بيانات المقال في قاعدة البيانات
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'media_id'          => 'nullable|exists:media,id',
            'short_description' => 'nullable|string|max:500',
            'content'           => 'required|string',
            'published_at'      => 'nullable|date',
            'is_featured'       => 'boolean',
            'is_active'          => 'boolean',
        ]);

        // إعادة توليد الـ slug إذا تم تغيير العنوان
        if ($post->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            if (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $slug . '-' . time();
            }
            $validated['slug'] = $slug;
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active']   = $request->boolean('is_active');

        $post->update($validated);

        return redirect()->route('posts.index')
            ->with('success', 'تم تحديث المقال بنجاح.');
    }

    /**
     * حذف مقال
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'تم حذف المقال بنجاح.');
    }
}