<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
     * الخدمات المرتبطة بالملف
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'service_media',
            'media_id',
            'service_id'
        )
        ->withPivot('sort_order')
        ->withTimestamps();
    }



    /**
     * شعار العميل المرتبط بهذا الملف
     */
    public function clientLogo(): HasOne
    {
        return $this->hasOne(
            ClientLogo::class
        );
    }



    /**
     * رابط الملف
     */
    public function url()
    {
        return asset(
            'storage/' . $this->file_path
        );
    }
}