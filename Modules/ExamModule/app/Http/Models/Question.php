<?php

namespace Modules\ExamModule\app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExamModule\app\Http\Models\Answer;
use Modules\ExamModule\app\Http\Models\Exam;

class Question extends Model
{
    protected $fillable = [
        'exam_id',
        'type',
        'question_text',
        'options',       // JSON for MCQ
        'correct_answer',
        'grade'
    ];

    protected $casts = [
        'options' => 'array',   // A, B, C, D stored in JSON
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Validate that the correct answer matches the question type
     */
    public function isValidAnswer()
    {
        if ($this->type === 'true_false') {
            return in_array($this->correct_answer, ['true', 'false']);
        }

        if ($this->type === 'mcq') {
            return in_array($this->correct_answer, ['A', 'B', 'C', 'D']);
        }

        return false;
    }

    // Helper: check if question is MCQ
    public function isMcq()
    {
        return $this->type === 'mcq';
    }

    // Helper: check if True/False
    public function isTrueFalse()
    {
        return $this->type === 'true_false';
    }

    // Relation: student submitted answers
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
