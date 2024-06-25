<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'path',
        'alt',
        'image_type',
        'project_id',
        'task_id'
    ];
}
