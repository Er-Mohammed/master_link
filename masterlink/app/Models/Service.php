<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'full_description',
        'sort_order',
        'is_active',
    ];


    protected $casts = [
        'is_active' => 'boolean',
    ];



    /**
     * Media attached to service
     */
    public function media(): BelongsToMany
    {
        return $this->belongsToMany(
            Media::class,
            'service_media',
            'service_id',
            'media_id'
        )
        ->withPivot('sort_order')
        ->withTimestamps()
        ->orderBy('service_media.sort_order');
    }



    /**
     * Projects related to service
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'project_services',
            'service_id',
            'project_id'
        )
        ->withTimestamps();
    }



    /**
     * Consultation requests
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(
            Consultation::class
        );
    }
}