<?php

use Illuminate\Support\Facades\Route;
use Modules\ExamModule\App\Http\Controllers\ExamModuleController;

foreach (['admin', 'user'] as $guard) {

    Route::prefix("$guard/exam")
        ->middleware("auth:$guard")
        ->name("$guard.exam.")
        ->group(function () {

            Route::get('/', [ExamModuleController::class, 'index'])
                ->name('index');
             

            Route::get('create', [ExamModuleController::class, 'create'])
                ->name('create');
        

            Route::post('store', [ExamModuleController::class, 'store'])
                ->name('store')
                ->middleware("permission:create_exam");

            Route::get('{id}/edit', [ExamModuleController::class, 'edit'])
                ->name('edit')
                ->middleware("permission:edit_exam");

            Route::put('update', [ExamModuleController::class, 'update'])
                ->name('update')
                ->middleware("permission:edit_exam");

            Route::get('{id}/delete', [ExamModuleController::class, 'destroy'])
                ->name('delete')
                ->middleware("permission:delete_exam");

            // Questions
            Route::get('{id}/questions', [ExamModuleController::class, 'questions'])
                ->name('question.list');
              

            Route::get('{id}/question/show', [ExamModuleController::class, 'showQuestions'])
                ->name('question.show')
                ->middleware("permission:view_questions");

            Route::post('question/store', [ExamModuleController::class, 'storeQuestion'])
                ->name('question.store');
          
            Route::put('question/{id}/update', [ExamModuleController::class, 'updateQuestion'])
                ->name('question.update')
                ->middleware("permission:edit_questions");

            Route::get('question/{id}/delete', [ExamModuleController::class, 'deleteQuestion'])
                ->name('question.delete')
                ->middleware("permission:delete_questions");
        });
}
