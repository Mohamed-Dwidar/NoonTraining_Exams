<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // One row = one attempt. student_exam_id is NULL for the standalone test,
        // or points to the course attempt (student_exams) when linked to an exam.
        Schema::create('student_typing_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('student_exam_id')->nullable()->index();

            // Snapshot taken when the attempt starts (settings may change later)
            $table->text('source_text')->nullable();
            $table->unsignedInteger('word_count')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('success_grade')->nullable();

            $table->longText('typed_text')->nullable();
            $table->unsignedInteger('typed_words')->default(0);
            $table->unsignedInteger('correct_words')->nullable();
            $table->unsignedInteger('wrong_words')->nullable();
            $table->boolean('passed')->nullable();

            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('student_typing_tests');
    }
};
