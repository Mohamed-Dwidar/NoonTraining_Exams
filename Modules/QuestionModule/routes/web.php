<?php

use Illuminate\Support\Facades\Route;
use Modules\QuestionModule\App\Http\Controllers\CategoryController;
use Modules\QuestionModule\App\Http\Controllers\QuestionModuleController;

foreach (['admin', 'user'] as $guard) {
    Route::prefix($guard)->middleware("auth:$guard")->name("$guard.")->group(function () {
        // Questions routes
        Route::group(['prefix' => 'questions'], function () {
            Route::get('/', [QuestionModuleController::class, 'indexQuestions'])->name('questions.index');
            Route::get('create', [QuestionModuleController::class, 'createQuestion'])->name('question.create');
            Route::post('store', [QuestionModuleController::class, 'storeQuestion'])->name('question.store');
            Route::get('{id}/edit', [QuestionModuleController::class, 'editQuestion'])->name('question.edit');
            Route::put('update', [QuestionModuleController::class, 'updateQuestion'])->name('question.update');
            Route::get('{id}/delete', [QuestionModuleController::class, 'deleteQuestion'])->name('question.delete');
            Route::post('import', [QuestionModuleController::class, 'importStudents'])->name('questions.import');
        });

        // Categories routes
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('create', [CategoryController::class, 'create'])->name('categories.create');
            Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
            Route::post('{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        });
    });
}
