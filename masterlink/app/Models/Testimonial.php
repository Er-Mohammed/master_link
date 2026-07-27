<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'media_id',
        'display_name',
        'message',
        'sort_order',
        'is_active',
    ];

    /**
     * الصورة أو الشعار المرتبط بالشهادة
     */
    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}