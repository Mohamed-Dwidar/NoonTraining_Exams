<?php

namespace Modules\QuestionModule\app\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\QuestionModule\app\Http\Models\Answer;

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


    public function answer()
    {
        return $this->hasOne(Answer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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
