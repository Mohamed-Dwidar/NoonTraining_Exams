<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentModule\app\Http\Controllers\StudentModuleController;
use Modules\StudentModule\app\Http\Controllers\Auth\StudentAuthController;

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

            Route::post('students/update', [StudentModuleController::class, 'update'])
                ->name('students.update');

            Route::post('students/{id}/delete', [StudentModuleController::class, 'delete'])
                ->name('students.delete');

            Route::get('/students/show-exams/{id}', [StudentModuleController::class, 'showExams'])
                ->name('students.showExams');

            Route::post('/students/assign-exam', [StudentModuleController::class, 'assignExam'])
                ->name('students.assignExam');
        });
}

Route::prefix('student')->group(function () {
    Route::get('/', [StudentAuthController::class, 'index'])->name('student.login');
    Route::get('login', [StudentAuthController::class, 'index']);
    Route::post('login', [StudentAuthController::class, 'login'])->name('student.loginpost');
});

Route::group(['prefix' => 'student', 'middleware' => ['auth:student']], function () {
    Route::get('dashboard', [StudentModuleController::class, 'dashboard'])->name('student.dashboard');
    Route::get('logout', [StudentAuthController::class, 'logout'])->name('student.logout');
    Route::get('changePassword', [StudentAuthController::class, 'changePassword'])->name('student.changePassword');
    Route::post('updatePassword', [StudentAuthController::class, 'updatePassword'])->name('student.updatePassword');
});