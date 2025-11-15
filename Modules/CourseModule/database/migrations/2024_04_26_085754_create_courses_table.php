<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('branch_id')->index();
            $table->string('name');
            $table->string('group_nu');
            $table->string('course_org_nu');
            $table->string('start_at', 50)->nullable();
            $table->string('end_at', 50)->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->decimal('exam_fees', 8, 2)->default(0);
            $table->boolean('is_available')->default(1)->index();

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
        Schema::dropIfExists('courses');
    }
}
