<?php

namespace Modules\ExamModule\App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\UserModule\app\Http\Models\User;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\StudentModule\app\Http\Models\Student;
use Modules\StudentModule\app\Http\Models\StudentExam;

class Exam extends Model {
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'duration_minutes',
        'total_questions',
        'mcq_count',
        'true_false_count',
        'success_grade',
        'total_grade',
        'created_by',
    ];

    // Exam creator (Admin/User)
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Students who took this exam
    public function students() {
        return $this->belongsToMany(
            Student::class,
            'student_exams',
            'exam_id',
            'student_id'
        )->withPivot(['score', 'status', 'started_at', 'completed_at']);
    }

    // Exam attempts (through pivot table)
    public function attempts() {
        return $this->hasMany(
            StudentExam::class,
            'exam_id'
        );
    }

    // Get student's specific attempt
    public function studentAttempt($studentId = null) {
        if (!$studentId) {
            $studentId = auth()->guard('student')->id();
        }

        return $this->hasOne(
            StudentExam::class,
            'exam_id'
        )->where('student_id', $studentId);
    }

    public function attempt() {
        return $this->studentAttempt();
    }
}
