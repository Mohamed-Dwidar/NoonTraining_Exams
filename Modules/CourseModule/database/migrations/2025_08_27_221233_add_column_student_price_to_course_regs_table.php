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
        Schema::table('course_regs', function (Blueprint $table) {
            $table->decimal('student_price', 8, 2)->default(0)->after('main_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_regs', function (Blueprint $table) {
            $table->dropColumn('student_price'); 
        });
    }
};
