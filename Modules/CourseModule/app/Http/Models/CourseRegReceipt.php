<?php

namespace Modules\CourseModule\app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\StudentModule\app\Http\Models\Student;

class CourseRegReceipt extends Model
{
    // protected $guarded = [];
    protected $fillable = [
        'course_reg_id',
        'student_id',
        'file_title',
        'file_name'
    ];

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    function courseReg()
    {
        return $this->belongsTo(CourseReg::class);
    }
}
