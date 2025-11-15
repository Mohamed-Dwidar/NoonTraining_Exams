<?php

namespace Modules\CourseModule\app\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRegStatus extends Model
{
    // protected $guarded = [];
    protected $fillable = [
        'status',
        'color',
    ];
}
