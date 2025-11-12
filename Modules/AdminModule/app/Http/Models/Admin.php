<?php

namespace Modules\AdminModule\app\Http\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\LogModule\app\Http\Models\Log;

class Admin extends Authenticatable
{

    protected $guard = 'admin';

    protected $guarded = [];

    public function logable()
    {
        return $this->morphTo();
    }

    public function logs() 
    {
        return $this->morphMany(Log::class, 'loggable');
    }
}
