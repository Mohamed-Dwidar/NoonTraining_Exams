<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminModule\Http\Controllers\AdminModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('adminmodules', AdminModuleController::class)->names('adminmodule');
});
