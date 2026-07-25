<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'media_id',
        'sort_order',
    ];


    /**
     * المشروع المرتبط بالصورة
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    /**
     * الصورة المرتبطة بالمشروع
     */
    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}