<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentModule\Http\Controllers\StudentModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('studentmodules', StudentModuleController::class)->names('studentmodule');
});
