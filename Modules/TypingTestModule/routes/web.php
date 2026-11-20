<?php

use Illuminate\Support\Facades\Route;
use Modules\TypingTestModule\App\Http\Controllers\Admin\TypingResultController;
use Modules\TypingTestModule\App\Http\Controllers\Admin\TypingTestSettingsController;
use Modules\TypingTestModule\App\Http\Controllers\Admin\TypingTextController;
use Modules\TypingTestModule\App\Http\Controllers\Student\StudentTypingTestController;

// Main admin only: settings (duration, word count, pass mark, description)
Route::prefix('admin/typing-test')->middleware('auth:admin')->name('admin.typing.')->group(function () {
    Route::get('settings', [TypingTestSettingsController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [TypingTestSettingsController::class, 'update'])->name('settings.update');
});

// Admin + staff: texts bank, results, assign / unassign the standalone test
foreach (['admin', 'user'] as $guard) {
    Route::prefix("$guard/typing-test")->middleware("auth:$guard")->name("$guard.typing.")->group(function () {
        Route::get('texts', [TypingTextController::class, 'index'])->name('texts.index');
        Route::get('texts/create', [TypingTextController::class, 'create'])->name('texts.create');
        Route::post('texts/store', [TypingTextController::class, 'store'])->name('texts.store');
        Route::get('texts/{id}/edit', [TypingTextController::class, 'edit'])->name('texts.edit');
        Route::post('texts/update', [TypingTextController::class, 'update'])->name('texts.update');
        Route::post('texts/{id}/delete', [TypingTextController::class, 'delete'])->name('texts.delete');

        Route::get('results', [TypingResultController::class, 'index'])->name('results.index');
        Route::get('results/{id}', [TypingResultController::class, 'show'])->name('results.show');
        Route::post('assign', [TypingResultController::class, 'assign'])->name('assign');
        Route::post('{id}/unassign', [TypingResultController::class, 'unassign'])->name('unassign');
    });
}

// Student
Route::prefix('student/typing-test')->middleware('auth:student')->name('student.typing.')->group(function () {
    Route::get('{id}/start', [StudentTypingTestController::class, 'start'])->name('start');
    Route::post('{id}/save', [StudentTypingTestController::class, 'save'])->name('save');
    Route::post('{id}/submit', [StudentTypingTestController::class, 'submit'])->name('submit');
    Route::get('{id}/result', [StudentTypingTestController::class, 'result'])->name('result');
});
