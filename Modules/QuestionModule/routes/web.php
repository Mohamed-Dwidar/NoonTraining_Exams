<?php

use Illuminate\Support\Facades\Route;
use Modules\QuestionModule\app\Http\Controllers\CategoryController;
use Modules\QuestionModule\Http\Controllers\QuestionModuleController;

foreach (['admin', 'user'] as $guard) {

    Route::prefix($guard)
        ->middleware("auth:$guard")
        ->name("$guard.")
        ->group(function () {

            // Questions routes
            Route::post('question/store', [QuestionModuleController::class, 'storeQuestion'])
                ->name('question.store');

            Route::put('question/{id}/update', [QuestionModuleController::class, 'updateQuestion'])
                ->name('question.update')
                ->middleware("permission:edit_questions");

            Route::get('question/{id}/delete', [QuestionModuleController::class, 'deleteQuestion'])
                ->name('question.delete')
                ->middleware("permission:delete_questions");

            // Category routes (all CRUD manually)
            Route::get('categories', [CategoryController::class, 'index'])
                ->name('categories.index');

            Route::get('categories/create', [CategoryController::class, 'create'])
                ->name('categories.create');

            Route::post('categories', [CategoryController::class, 'store'])
                ->name('categories.store');

            Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])
                ->name('categories.edit');

            Route::put('categories/{category}', [CategoryController::class, 'update'])
                ->name('categories.update');

            Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
                ->name('categories.destroy');
        });
}
