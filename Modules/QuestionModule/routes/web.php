<?php

use Illuminate\Support\Facades\Route;
use Modules\QuestionModule\app\Http\Controllers\CategoryController;
use Modules\QuestionModule\app\Http\Controllers\QuestionModuleController;

foreach (['admin', 'user'] as $guard) {

    Route::prefix($guard)
        ->middleware("auth:$guard")
        ->name("$guard.")
        ->group(function () {
            // Questions routes
            Route::get('question', [QuestionModuleController::class, 'indexQuestions'])
                ->name('questions.index');

            Route::get('question/create', [QuestionModuleController::class, 'createQuestion'])
                ->name('question.create');

            Route::post('question/store', [QuestionModuleController::class, 'storeQuestion'])
                ->name('question.store');

            Route::get('question/{id}/edit', [QuestionModuleController::class, 'editQuestion'])
                ->name('question.edit');

            Route::put('question/update', [QuestionModuleController::class, 'updateQuestion'])
                ->name('question.update');

            Route::get('question/{id}/delete', [QuestionModuleController::class, 'deleteQuestion'])
                ->name('question.delete');

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
