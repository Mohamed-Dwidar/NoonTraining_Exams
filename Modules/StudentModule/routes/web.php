<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentModule\app\Http\Controllers\StudentModuleController;
use Modules\StudentModule\app\Http\Controllers\Auth\StudentAuthController;
use Modules\StudentModule\app\Http\Controllers\Exam\StudentExamController;

foreach (['admin', 'user'] as $guard) {
    Route::prefix($guard)->middleware("auth:$guard")->name("$guard.")->group(function () {
        // Student Routes
        Route::get('students', [StudentModuleController::class, 'index'])->name('students.index');
        Route::get('students/create', [StudentModuleController::class, 'create'])->name('students.create');
        Route::post('students/store', [StudentModuleController::class, 'store'])->name('students.store');
        Route::get('students/{id}/show', [StudentModuleController::class, 'show'])->name('students.show');
        Route::get('students/{id}/edit', [StudentModuleController::class, 'edit'])->name('students.edit');
        Route::post('students/update', [StudentModuleController::class, 'update'])->name('students.update');
        Route::post('students/{id}/delete', [StudentModuleController::class, 'delete'])->name('students.delete');
        Route::get('/students/show-exams/{id}', [StudentModuleController::class, 'showExams'])->name('students.showExams');
        Route::post('/students/assign-exam', [StudentModuleController::class, 'assignExam'])->name('students.assignExam');
        Route::post('/students/assign-single-exam', [StudentModuleController::class, 'assignSingleExam'])->name('students.assignSingleExam');
        Route::post('/students/unassign-exam', [StudentModuleController::class, 'unassignExam'])->name('students.unassignExam');
    });
}

Route::prefix('student')->name('student.')->group(function () {
    Route::get('/', [StudentAuthController::class, 'index'])->name('login');
    Route::get('login', [StudentAuthController::class, 'index']);
    Route::post('login', [StudentAuthController::class, 'login'])->name('loginpost');

    Route::middleware('auth:student')->group(function () {
        Route::get('dashboard', [StudentModuleController::class, 'dashboard'])->name('dashboard');
        Route::get('logout', [StudentAuthController::class, 'logout'])->name('logout');
        Route::get('changePassword', [StudentAuthController::class, 'changePassword'])->name('changePassword');
        Route::post('updatePassword', [StudentAuthController::class, 'updatePassword'])->name('updatePassword');

        // Exam Routes
        Route::prefix('exam')->group(function () {
            Route::get('/start/{studentExamId}', [StudentExamController::class, 'startExam'])->name('exam.start');
            Route::post('/answer', [StudentExamController::class, 'submitAnswer'])->name('exam.submitAnswer');
            Route::get('/complete/{studentExamId}', [StudentExamController::class, 'completeExam'])->name('exam.complete');
            Route::get('/result/{studentExamId}', [StudentExamController::class, 'examResult'])->name('exam.result');
        });
    });
});
