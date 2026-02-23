<?php

use Illuminate\Support\Facades\Route;
use Modules\QuestionModule\App\Http\Controllers\QuestionModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('questionmodules', QuestionModuleController::class)->names('questionmodule');
});
