<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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


    /**
     * الكاتب الذي أنشأ المقال
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }


    /**
     * الصورة الرئيسية للمقال
     */
    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}