<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

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


    /**
     * تصنيف المشروع
     */
    public function category()
    {
        return $this->belongsTo(ProjectCategory::class);
    }


    /**
     * صور المشروع
     */
    public function media()
    {
        return $this->belongsToMany(
            Media::class,
            'project_media'
        )->withTimestamps();
    }


    /**
     * الخدمات المستخدمة في المشروع
     */
    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'project_services'
        )->withTimestamps();
    }
}
