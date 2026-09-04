<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceMedia extends Model
{
    use HasFactory;

    protected $table = 'service_media';

    protected $fillable = [
        'service_id',
        'media_id',
        'sort_order',
    ];

    /**
     * الخدمة المرتبطة بالصورة
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * الصورة المرتبطة بالخدمة
     */
    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
