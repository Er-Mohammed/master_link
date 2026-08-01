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

        'extension',

        'media_type',

        'mime_type',

        'file_size',

        'width',

        'height',

        'alt_text',

    ];

    /**
     * المدير الذي قام برفع الملف
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * المقالات التي تستخدم هذا الملف
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * المشاريع المرتبطة بهذا الملف
     */
    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'project_media'
        )
        ->withPivot('sort_order')
        ->withTimestamps();
    }

    /**
     * الخدمات المرتبطة بهذا الملف
     */
    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'service_media'
        )
        ->withPivot('sort_order')
        ->withTimestamps();
    }

    /**
     * شهادات العملاء
     */
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * شعار العميل المرتبط بهذا الملف
     */
    public function clientLogo()
    {
        return $this->hasOne(ClientLogo::class);
    }
}