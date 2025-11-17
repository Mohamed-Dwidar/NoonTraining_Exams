<?php

use Illuminate\Support\Facades\Route;
use Modules\ExamModule\App\Http\Controllers\ExamModuleController;

Route::prefix('admin/exam')->middleware(['auth:admin'])->name('admin.exam.')->group(function() {
    // Exams
    Route::get('/', [ExamModuleController::class, 'index'])->name('index');
    Route::get('create', [ExamModuleController::class, 'create'])->name('create');
    Route::post('store', [ExamModuleController::class, 'store'])->name('store');
    Route::get('{id}/edit', [ExamModuleController::class, 'edit'])->name('edit');
    Route::put('update', [ExamModuleController::class, 'update'])->name('update');
    Route::delete('{id}/delete', [ExamModuleController::class, 'destroy'])->name('delete');

});

Route::prefix('admin/exam')->middleware(['auth:admin'])->name('question.')->group(function() {
 
     Route::get('/{id}/questions', [ExamModuleController::class, 'questions'])->name('list');
     Route::get('/{id}/question/show', [ExamModuleController::class, 'showQuestions'])->name('show');
    Route::post('question/store', [ExamModuleController::class, 'storeQuestion'])->name('store');
    Route::put('question/{id}/update', [ExamModuleController::class, 'updateQuestion'])->name('update');
    Route::delete('question/{id}/delete', [ExamModuleController::class, 'deleteQuestion'])->name('delete');
});
