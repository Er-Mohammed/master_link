<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Media extends Model
{
    use HasFactory;

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

    protected $casts = [
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    /**
     * Admin who uploaded the media.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(
            Admin::class
        );
    }

    /**
     * Services related to the media.
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
            ->withTimestamps()
            ->orderBy('service_media.sort_order');
    }

    /**
     * Client logo related to this media.
     */
    public function clientLogo(): HasOne
    {
        return $this->hasOne(
            ClientLogo::class
        );
    }

    /**
     * Get public URL for the media.
     */
    public function url(): string
    {
        return asset(
            'storage/'.$this->file_path
        );
    }
}
