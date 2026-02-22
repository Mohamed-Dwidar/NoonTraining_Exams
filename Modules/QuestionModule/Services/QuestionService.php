<?php

namespace Modules\QuestionModule\Services;


use Illuminate\Validation\ValidationException;
use Modules\QuestionModule\Repository\AnswerRepository;
use Modules\QuestionModule\Repository\QuestionRepository;

class QuestionService {
    private $questionRepository;
    private $answerRepository;

    public function __construct(
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->answerRepository   = $answerRepository;
    }

    public function findAll() {
        return $this->questionRepository->all();
    }

    public function paginate($perPage = 15) {
        return $this->questionRepository->paginate($perPage);
    }

    public function find($id) {
        return $this->questionRepository->find($id);
    }

    /**
     * Create a single question
     */
    public function create($data) {
        if ($data['type'] === 'mcq') {
            $arr_options = array_values($data['options'] ?? []) ?? null;
        } else {
            $arr_options = ['true', 'false'];
        }

        $question_data = [
            'category_id'       => $data['category_id'],
            'type'          => $data['type'],
            'question_text' => $data['question_text'],
            'options'       => $arr_options,
        ];
        $question = $this->questionRepository->create($question_data);

        $this->answerRepository->create([
            'question_id'    => $question->id,
            'correct_answer' => $data['type'] === 'mcq' ? $data['options'][$data['answer']] : $data['answer'],
        ]);

        return $question;
    }

    /**
     * CREATE + UPDATE MULTIPLE QUESTIONS
     */
    public function createMultiple(array $questions, $examId) {
        $saved = [];

        foreach ($questions as $q) {
            $q['category_id'] = $examId;

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
    public function update(array $data) {
        // dd($data);
        if ($data['type'] === 'mcq') {
            $arr_options = array_values($data['options'] ?? []) ?? null;
        } else {
            $arr_options = ['true', 'false'];
        }

        $question = $this->questionRepository->update([
            'category_id' => $data['category_id'],
            'question_text' => $data['question_text'],
            'options' => $arr_options,
        ], $data['id']);

        // Fetch answer by question_id not by ID
        $answer = $this->answerRepository->findByQuestionId($data['id']);
        if ($answer) {
            $this->answerRepository->update([
                'correct_answer' => $data['type'] === 'mcq' ? $data['options'][$data['answer']] : $data['answer'],
            ], $answer->id);
        } else {
            $this->answerRepository->create([
                'question_id'    => $data['id'],
                'correct_answer' => $data['type'] === 'mcq' ? $data['options'][$data['answer']] : $data['answer'],
            ]);
        }

        return $question;
    }

    /**
     * Validate question data
     */
    private function validateQuestion(array $data) {
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

    public function delete($id) {
        $answer = $this->answerRepository->findByQuestionId($id);

        if ($answer) {
            $this->answerRepository->delete($answer->id);
        }

        return $this->questionRepository->delete($id);
    }

    public function getRandomQuestionsByType($categoryId, $type, $count) {
        return $this->questionRepository->getRandomByType($categoryId, $type, $count);
    }
}
