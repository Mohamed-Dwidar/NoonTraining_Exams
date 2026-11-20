<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Single fixed row: the typing test cannot be deleted or renamed.
        Schema::create('typing_test_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_minutes')->default(10);
            $table->unsignedInteger('word_count')->default(150);
            $table->unsignedInteger('success_grade')->default(60);
            $table->timestamps();
        });

        DB::table('typing_test_settings')->insert([
            'title'            => 'أختبار سرعة الكتابة',
            'description'      => 'اكتب النص المعروض أمامك كما هو تماماً خلال الوقت المحدد.',
            'duration_minutes' => 10,
            'word_count'       => 150,
            'success_grade'    => 60,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('typing_test_settings');
    }
};
