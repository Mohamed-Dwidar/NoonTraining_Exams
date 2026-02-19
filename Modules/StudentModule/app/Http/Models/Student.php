<?php

namespace Modules\StudentModule\app\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\ExamModule\app\Http\Models\Exam;
use Modules\QuestionModule\app\Http\Models\Category;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'national_id',
        'gender',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'student_exam', 'student_id', 'exam_id');
    }

    public function studentExams()
    {
        return $this->hasMany(StudentExam::class, 'student_id');
    }




    // public function examAttempts()
    // {
    //     return $this->hasMany(StudentExam::class, 'student_id');
    // }

    // public function examAttempt($examId)
    // {
    //     return $this->examAttempts()->where('exam_id', $examId)->first();
    // }
}
