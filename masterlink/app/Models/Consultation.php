<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'service_id',
        'message',
        'status',
    ];

    /**
     * Service requested by the client.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(
            Service::class
        );
    }
}
