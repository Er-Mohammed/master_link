<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * الخدمة المطلوبة في الاستشارة
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}