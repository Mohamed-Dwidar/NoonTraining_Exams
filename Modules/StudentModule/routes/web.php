<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentModule\app\Http\Controllers\StudentModuleController;

foreach (['admin', 'user'] as $guard) {

    Route::prefix($guard)
        ->middleware("auth:$guard")
        ->name("$guard.")
        ->group(function () {

            // Student Routes
            Route::get('students', [StudentModuleController::class, 'index'])
                ->name('students.index');

            Route::get('students/create', [StudentModuleController::class, 'create'])
                ->name('students.create');

            Route::post('students/store', [StudentModuleController::class, 'store'])
                ->name('students.store');

            Route::get('students/{id}/show', [StudentModuleController::class, 'show'])
                ->name('students.show');

            Route::get('students/{id}/edit', [StudentModuleController::class, 'edit'])
                ->name('students.edit');

            Route::POST('students/update', [StudentModuleController::class, 'update'])
                ->name('students.update');

            Route::delete('students/{id}/delete', [StudentModuleController::class, 'delete'])
                ->name('students.delete');

            Route::get('/students/show-exams/{id}', [StudentModuleController::class, 'showExams'])
                ->name('students.showExams');

                
            Route::post('/students/assign-exam', [StudentModuleController::class, 'assignExam'])
                ->name('students.assignExam');
        });
}
