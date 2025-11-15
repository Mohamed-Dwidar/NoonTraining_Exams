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
        Schema::create('unknown_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transferor_name');
            $table->bigInteger('course_reg_id')->default(0);
            $table->bigInteger('student_id')->default(0);
            $table->decimal('amount', 8, 2)->default(0);
            $table->date('paid_at')->nullable();
            $table->enum('pay_method', ['كاش', 'تحويل', 'شبكة', 'تابي', 'تمارا', 'يور واي', 'أخرى'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unknown_payments');
    }
};
