<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseRegsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_regs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('student_id')->index();
            $table->bigInteger('status_id')->default(1)->index();
            $table->decimal('main_price', 8, 2)->default(0);
            $table->decimal('price', 8, 2)->default(0);
            $table->decimal('exam_fees', 8, 2)->default(0);
            $table->boolean('is_free')->default(0)->index();
            $table->boolean('is_leave')->default(0)->index();
            $table->bigInteger('coupon_id')->default(0);
            $table->boolean('is_course_paid')->default(0)->index();
            $table->boolean('is_exam_paid')->default(0)->index();
            // $table->boolean('is_nopaying')->default(0)->index();            
            $table->boolean('is_recive_cert')->default(0)->index();
            $table->boolean('need_work_agree')->default(0);
            $table->string('registered_by')->nullable();

            $table->timestamps();
            $table->softDeletes(); // This adds the 'deleted_at' column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_regs');
    }
}
