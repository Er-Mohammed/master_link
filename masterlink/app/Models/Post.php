<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'media_id',
        'title',
        'slug',
        'short_description',
        'content',
        'published_at',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * The admin who created the post.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(
            Admin::class
        );
    }

    /**
     * The main media associated with the post.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(
            Media::class
        );
    }
}