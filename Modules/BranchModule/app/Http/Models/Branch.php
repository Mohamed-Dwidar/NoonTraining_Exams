<?php

namespace Modules\BranchModule\app\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{

    // protected $guarded = [];
    protected $fillable = [
        'name',
        'address',
        'is_available',
    ];

    function courses()
    {
        // return $this->hasMany(Course::class);
    }
}
