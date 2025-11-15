<?php

namespace Modules\CourseModule\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CourseModuleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('course_reg_statuses')->truncate();

        DB::table('course_reg_statuses')->insert(['status' => 'لم يسدد الدورة أو الاختبار', 'color' => '#BFBFBF']);

        DB::table('course_reg_statuses')->insert(['status' => 'يتم تقسيط الدورة + لم يسدد الاختبار', 'color' => '#FF0000']);
        DB::table('course_reg_statuses')->insert(['status' => 'يتم تقسيط الدورة + سدد الاختبار', 'color' => '#F27979']);

        DB::table('course_reg_statuses')->insert(['status' => 'سدد الدورة فقط', 'color' => '#FFFF00']);
        DB::table('course_reg_statuses')->insert(['status' => 'سدد الاختبار فقط', 'color' => '#FFC000']);

        DB::table('course_reg_statuses')->insert(['status' => 'سدد الدورة + الاختبار', 'color' => '#00B050']);
        DB::table('course_reg_statuses')->insert(['status' => 'سدد الدورة + الاختبار + استلم الشهادة', 'color' => '#FF00FF']);

        DB::table('course_reg_statuses')->insert(['status' => 'الدورة مجانا + لم يسدد رسوم الاختبار', 'color' => '#49b9e5']);
        DB::table('course_reg_statuses')->insert(['status' => 'الدورة مجانا + سدد رسوم الاختبار', 'color' => '#4A86E8']);
        
        DB::table('course_reg_statuses')->insert(['status' => 'لا يرغب في السداد', 'color' => '#000000']);
    }
}
