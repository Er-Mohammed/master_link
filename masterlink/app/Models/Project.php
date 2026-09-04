<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'client_name',
        'short_description',
        'full_description',
        'project_url',
        'completion_date',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Project category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ProjectCategory::class
        );
    }

    /**
     * Project media.
     */
    public function media(): BelongsToMany
    {
        return $this->belongsToMany(
            Media::class,
            'project_media',
            'project_id',
            'media_id'
        )
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('project_media.sort_order');
    }

    /**
     * Services used in this project.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'project_services',
            'project_id',
            'service_id'
        )
            ->withTimestamps();
    }
}
