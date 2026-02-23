<?php

namespace Modules\StudentModule\App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\QuestionModule\app\Http\Models\Question;

class StudentExamAnswer extends Model {
    use HasFactory;
    public $timestamps = false;
    protected $table = 'student_exam_answers';
    protected $fillable = [
        'student_exam_id',
        'question_id',
        'answer',
        'is_correct'
    ];

    public function studentExam() {
        return $this->belongsTo(StudentExam::class, 'student_exam_id');
    }

    public function question() {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
