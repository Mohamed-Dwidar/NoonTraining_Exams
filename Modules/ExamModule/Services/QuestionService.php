<?php

namespace Modules\ExamModule\Services;

use Modules\ExamModule\Repository\QuestionRepository;
use Modules\ExamModule\Repository\AnswerRepository;
use Illuminate\Validation\ValidationException;

class QuestionService
{
    private $questionRepository;
    private $answerRepository;

    public function __construct(
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->answerRepository   = $answerRepository;
    }

    /**
     * Create a question
     */
    public function create(array $data)
    {
        $this->validateQuestion($data);

        // Create question
        $question = $this->questionRepository->create([
            'exam_id'      => $data['exam_id'],
            'type'         => $data['type'],
            'question_text'=> $data['question_text'],
            'options'      => $data['options'] ?? null,
            'grade'        => $data['grade'] ?? 1,
        ]);

        // Create answer via repository
        $this->answerRepository->create([
            'question_id'     => $question->id,
            'correct_answer'  => $data['answer'],
        ]);

        return $question;
    }

    /**
     * Update question
     */
    public function update(array $data)
    {
        $this->validateQuestion($data);

        // Update question
        $question = $this->questionRepository->update([
            'type'         => $data['type'],
            'question_text'=> $data['question_text'],
            'options'      => $data['options'] ?? null,
            'grade'        => $data['grade'] ?? 1,
        ], $data['id']);

        // Update or create answer
        $answer = $this->answerRepository->findByQuestionId($data['id']);
        if ($answer) {
            $this->answerRepository->update([
                'correct_answer' => $data['answer']
            ], $answer->id);
        } else {
            $this->answerRepository->create([
                'question_id'    => $data['id'],
                'correct_answer' => $data['answer']
            ]);
        }

        return $question;
    }

    /**
     * Question Validation Logic
     */
    private function validateQuestion(array $data)
    {
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

    /**
     * Get all questions for an exam
     */
    public function getQuestionsByExam($examId)
    {
        return $this->questionRepository->where('exam_id', $examId)->get();
    }

    /**
     * Delete a question and its answer
     */
    public function delete($id)
    {
        // Delete answer via repository
        $answer = $this->answerRepository->findByQuestionId($id);
        if ($answer) {
            $this->answerRepository->delete($answer->id);
        }

        return $this->questionRepository->delete($id);
    }
}
