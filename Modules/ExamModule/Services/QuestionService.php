<?php

namespace Modules\ExamModule\Services;

use Modules\ExamModule\Repository\QuestionRepository;
use Modules\ExamModule\Repository\AnswerRepository;
use Illuminate\Validation\ValidationException;

class QuestionService
{
    private $questions;
    private $answers;

    public function __construct(
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository
    ) {
        $this->questions = $questionRepository;
        $this->answers   = $answerRepository;
    }

    /**
     * Create a single question
     */
    public function create(array $data)
    {
        $this->validateQuestion($data);

        $question = $this->questions->create([
            'exam_id'       => $data['exam_id'],
            'type'          => $data['type'],
            'question_text' => $data['question_text'],
            'options'       => $data['options'] ?? null,
        ]);

        $this->answers->create([
            'question_id'    => $question->id,
            'correct_answer' => $data['answer'],
        ]);

        return $question;
    }

    /**
     * CREATE + UPDATE MULTIPLE QUESTIONS
     */
    public function createMultiple(array $questions, $examId)
    {
        $saved = [];

        foreach ($questions as $q) {
            $q['exam_id'] = $examId;

            // UPDATE
            if (!empty($q['id'])) {
                $saved[] = $this->update($q);
            }
            // CREATE
            else {
                $saved[] = $this->create($q);
            }
        }

        return $saved;
    }

    /**
     * Update a question
     */
    public function update(array $data)
    {
        $this->validateQuestion($data);

        $question = $this->questions->update([
            'type'          => $data['type'],
            'question_text' => $data['question_text'],
            'options'       => $data['options'] ?? null,
        ], $data['id']);

        // Fetch answer by question_id not by ID
        $answer = $this->answers->findByQuestionId($data['id']);

        if ($answer) {
            $this->answers->update([
                'correct_answer' => $data['answer'],
            ], $answer->id);
        } else {
            $this->answers->create([
                'question_id'    => $data['id'],
                'correct_answer' => $data['answer'],
            ]);
        }

        return $question;
    }

    /**
     * Validate question data
     */
    private function validateQuestion(array $data)
    {
        if (!isset($data['type'])) {
            throw ValidationException::withMessages(['type' => 'Question type is required.']);
        }

        if (!isset($data['answer'])) {
            throw ValidationException::withMessages(['answer' => 'Answer is required.']);
        }

        if (!isset($data['question_text'])) {
            throw ValidationException::withMessages(['question_text' => 'Question text is required.']);
        }

        if ($data['type'] === 'mcq') {
            if (empty($data['options']) || !is_array($data['options'])) {
                throw ValidationException::withMessages([
                    'options' => 'MCQ questions require options as an array.'
                ]);
            }

            if (!array_key_exists($data['answer'], $data['options'])) {
                throw ValidationException::withMessages([
                    'answer' => 'Answer must be one of the MCQ options.'
                ]);
            }
        } elseif ($data['type'] === 'true_false') {
            if (!in_array(strtolower($data['answer']), ['true', 'false'])) {
                throw ValidationException::withMessages([
                    'answer' => 'True/False answer must be "true" or "false".'
                ]);
            }
        } else {
            throw ValidationException::withMessages([
                'type' => 'Invalid question type.'
            ]);
        }
    }

    public function delete($id)
    {
        $answer = $this->answers->findByQuestionId($id);

        if ($answer) {
            $this->answers->delete($answer->id);
        }

        return $this->questions->delete($id);
    }
}
