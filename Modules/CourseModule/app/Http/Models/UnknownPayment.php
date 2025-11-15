<?php

namespace Modules\CourseModule\app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\StudentModule\app\Http\Models\Student;

class UnknownPayment extends Model
{
    // protected $guarded = [];
    protected $fillable = [
        'transferor_name',
        'course_reg_id',
        'student_id',
        'amount',
        'paid_at',
        'pay_method'
    ];

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    function courseReg()
    {
        return $this->belongsTo(CourseReg::class);
    }

    public function scopeFilter($query)
    {
        // Add the condition for student_id = 0
        $query->where('course_reg_id', 0)
            ->where('student_id', 0);

        return $query;
    }
}
