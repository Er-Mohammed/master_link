<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectService extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'service_id',
    ];


    /**
     * المشروع المرتبط
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    /**
     * الخدمة المرتبطة
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}