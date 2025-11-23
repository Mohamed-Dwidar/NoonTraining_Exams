<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('title');
            $table->text('description')->nullable();

            $table->date('start_date');
            $table->date('end_date');

            $table->integer('duration_minutes');
            $table->integer('total_questions');
            $table->integer('mcq_count')->default(0);
            $table->integer('true_false_count')->default(0);

            $table->decimal('success_grade', 5, 2);
            $table->decimal('total_grade', 5, 2);

            $table->integer('created_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
