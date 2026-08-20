<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientLogo extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'company_name',
        'website_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Media file associated with the client logo.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
