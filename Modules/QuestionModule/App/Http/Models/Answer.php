<?php

namespace Modules\QuestionModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExamModule\app\Http\Models\Question;

class Answer extends Model
{
    protected $fillable = [
        'question_id',
        'correct_answer'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Validate if the stored correct answer is acceptable
     * based on the question type.
     */
    public function isValidCorrectAnswer()
    {
        $question = $this->question;

        // TRUE/FALSE validation
        if ($question->isTrueFalse()) {
            return in_array(
                strtolower($this->correct_answer),
                ['true', 'false']
            );
        }

        // MCQ validation
        if ($question->isMcq()) {
            // Must be A/B/C/D
            if (!in_array($this->correct_answer, ['A', 'B', 'C', 'D'])) {
                return false;
            }

            // Must exist in options JSON
            return isset($question->options[$this->correct_answer]);
        }

        return false; // unsupported question type
    }

    /**
     * Check if a student's answer matches the correct answer.
     */
    public function matches(string $studentAnswer)
    {
        return strtolower(trim($studentAnswer)) === strtolower(trim($this->correct_answer));
    }

    /**
     * Get the correct text for MCQ (e.g. "A" → "Paris")
     */
    public function getCorrectAnswerText()
    {
        $question = $this->question;

        if ($question->isMcq() && isset($question->options[$this->correct_answer])) {
            return $question->options[$this->correct_answer];
        }

        return $this->correct_answer; // true/false
    }

    /**
     * Check if the answer belongs to the question options (for MCQ).
     */
    public function existsInOptions()
    {
        $question = $this->question;

        if ($question->isMcq()) {
            return isset($question->options[$this->correct_answer]);
        }

        return true; // true/false doesn't use options
    }
}
