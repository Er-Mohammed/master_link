<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * صور الخدمة
     */
    public function media()
    {
        return $this->belongsToMany(
            Media::class,
            'service_media'
        )
        ->withPivot('sort_order')
        ->withTimestamps();
    }

    /**
     * المشاريع المرتبطة بالخدمة
     */
    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'project_services'
        )->withTimestamps();
    }

    /**
     * طلبات الاستشارة
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}