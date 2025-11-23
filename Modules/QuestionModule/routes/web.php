<?php

use Illuminate\Support\Facades\Route;
use Modules\QuestionModule\Http\Controllers\QuestionModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('questionmodules', QuestionModuleController::class)->names('questionmodule');
});
