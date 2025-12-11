<?php

namespace Modules\StudentModule\app\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\ExamModule\app\Http\Models\Exam;

class StudentExam extends Model
{
    use HasFactory;

    protected $table = 'student_exam';

    protected $fillable = [
        'student_id',
        'exam_id',
        'score',
        'status',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'float'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}