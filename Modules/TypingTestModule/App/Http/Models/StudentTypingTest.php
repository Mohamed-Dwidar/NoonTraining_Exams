<?php

namespace Modules\TypingTestModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\StudentModule\App\Http\Models\Student;
use Modules\StudentModule\App\Http\Models\StudentExam;

class StudentTypingTest extends Model {
    protected $table = 'student_typing_tests';

    protected $fillable = [
        'student_id',
        'student_exam_id',
        'source_text',
        'word_count',
        'duration_minutes',
        'success_grade',
        'typed_text',
        'typed_words',
        'correct_words',
        'wrong_words',
        'passed',
        'status',
        'started_at',
        'completed_at',
        'created_by',
    ];

    protected $casts = [
        'passed'       => 'boolean',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function student() {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function studentExam() {
        return $this->belongsTo(StudentExam::class, 'student_exam_id');
    }

    public function isLinked(): bool {
        return !is_null($this->student_exam_id);
    }
}
