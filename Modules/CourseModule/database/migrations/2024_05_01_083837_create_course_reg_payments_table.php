<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseRegPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_reg_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_reg_id');
            $table->bigInteger('student_id')->index();
            $table->enum('pay_type', ['رسوم إعادة الاختبار', 'رسوم الاختبار', 'دفعة للدورة'])->nullable();
            $table->decimal('amount', 8, 2)->default(0);
            $table->date('paid_at')->nullable();
            $table->enum('pay_method', ['كاش', 'تحويل', 'شبكة', 'تابي', 'تمارا', 'يور واي', 'أخرى'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_reg_payments');
    }
}
