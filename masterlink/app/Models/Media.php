<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'alt_text',
        'file_size',
    ];

    /**
     * المدير الذي رفع الملف
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * المقالات التي تستخدم هذه الصورة
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * المشاريع المرتبطة بهذه الصورة
     */
    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'project_media'
        )->withTimestamps();
    }

    /**
     * الخدمات المرتبطة بهذه الصورة
     */
    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'service_media'
        )->withTimestamps();
    }
}
