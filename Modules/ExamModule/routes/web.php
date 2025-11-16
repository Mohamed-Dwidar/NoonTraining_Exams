<?php

use Illuminate\Support\Facades\Route;
use Modules\ExamModule\App\Http\Controllers\ExamModuleController;

Route::group(['prefix' => 'admin/exams', 'middleware' => ['auth:admin']], function () {
    Route::get('/', [ExamModuleController::class, 'index'])->name('admin.exams.index');
    Route::get('create', [ExamModuleController::class, 'create'])->name('admin.exam.create');
    Route::post('store', [ExamModuleController::class, 'store'])->name('admin.exam.store');
    Route::get('edit/{id}', [ExamModuleController::class, 'edit'])->name('admin.exam.edit');
    Route::post('update', [ExamModuleController::class, 'update'])->name('admin.exam.update');
    Route::get('delete/{id}', [ExamModuleController::class, 'destroy'])->name('admin.exam.delete');
});
