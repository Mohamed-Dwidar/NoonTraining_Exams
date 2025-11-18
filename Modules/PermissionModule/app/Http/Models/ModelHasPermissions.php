<?php

namespace Modules\PermissionModule\app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelHasPermissions extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    // protected static function newFactory()
    // {
    //     return \Modules\PermissionModule\Database\factories\ModelHasPermissionsFactory::new();
    // }
}
