<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientLogo extends Model
{
    use HasFactory, SoftDeletes;



    protected $fillable = [
        'media_id',
        'company_name',
        'website_url',
        'sort_order',
        'is_active',
    ];



    /**
     * ملف الوسائط المرتبط بالشعار
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(
            Media::class
        );
    }
}