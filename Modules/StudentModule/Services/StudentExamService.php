<?php

namespace Modules\StudentModule\Services;

use Illuminate\Validation\ValidationException;
use Modules\StudentModule\Repository\StudentExamAnswerRepository;
use Modules\StudentModule\Repository\StudentExamRepository;
use Modules\StudentModule\Repository\StudentRepository;

class StudentExamService {
    private $studentRepository;
    private $studentExamRepository;
    private $studentExamAnswerRepository;

    public function __construct(StudentRepository $studentRepository, StudentExamRepository $studentExamRepository, StudentExamAnswerRepository $studentExamAnswerRepository) {
        $this->studentRepository = $studentRepository;
        $this->studentExamRepository = $studentExamRepository;
        $this->studentExamAnswerRepository = $studentExamAnswerRepository;
    }

    public function findById($id) {
        return $this->studentExamRepository->find($id);
    }

    public function update($id, array $data) {
        return $this->studentExamRepository->update($data, $id);
    }

    public function assignOneExamToStudent($studentId, $examId) {
        $student = $this->studentRepository->find($studentId);

        if (!$student) {
            throw ValidationException::withMessages([
                'student' => 'Student not found.',
            ]);
        }

        // Create StudentExam record and get its ID
        $studentExam = $this->studentExamRepository->create([
            'student_id' => $studentId,
            'exam_id' => $examId,
            'created_at' => now()
        ]);

        return [
            'success' => true,
            'student' => $student->load('exams'),
            'student_exam_id' => $studentExam->id,
            'message' => 'Exam assigned successfully.',
        ];
    }

    public function assignExamToStudent($studentId, array $examIds) {
        $student = $this->studentRepository->find($studentId);

        if (!$student) {
            throw ValidationException::withMessages([
                'student' => 'Student not found.',
            ]);
        }

        // Get all exams currently assigned to this student
        $alreadyAssignedExams = $student->exams()
            ->pluck('exam_id')
            ->toArray();

        $newExamIds = array_values(array_diff($examIds, $alreadyAssignedExams));
        $removeExamIds = array_values(array_diff($alreadyAssignedExams, $examIds));

        if (!empty($newExamIds)) {
            $student->exams()->attach($newExamIds);
        }

        if (!empty($removeExamIds)) {
            $student->exams()->detach($removeExamIds);
        }

        $result = [
            'success' => true,
            'student' => $student->load('exams'),
            'added_exams' => $newExamIds,
            'removed_exams' => $removeExamIds,
            'message' => 'Exams updated successfully.',
        ];

        return $result;
    }

    public function unassignExam($studentExamId) {
        return $this->studentExamRepository->delete($studentExamId);
    }

    public function getStudentExam($studentExamId) {
        return $this->studentExamRepository->find($studentExamId);
    }

    public function addQuestionAnswer($studentExamId, $questionId) {
        return $this->studentExamAnswerRepository->create([
            'student_exam_id' => $studentExamId,
            'question_id' => $questionId
        ]);
    }

    public function getStudentExamAnswer($studentExamId, $questionId) {
        return $this->studentExamAnswerRepository->findWhere([
            'student_exam_id' => $studentExamId,
            'question_id' => $questionId
        ])->first();
    }

    public function saveAnswer($studentExamAnswerId, $userAnswer) {
        return $this->studentExamAnswerRepository->update(
            ['answer' => $userAnswer],
            $studentExamAnswerId
        );
    }

    public function correctExamAnswers($studentExamId) {
        $studentExam = $this->studentExamRepository->find($studentExamId);
        $exam = $studentExam->exam;
        $correctCount = 0;

        foreach ($studentExam->studentExamAnswers as $answer) {
            $correctAnswer = $answer->question->answer->correct_answer ?? null;
            $isCorrect = $correctAnswer && ($correctAnswer == $answer->answer);

            // Update is_correct field for this answer
            $answer->is_correct = $isCorrect;
            $answer->save();

            if ($isCorrect) {
                $correctCount++;
            }
        }

        // Update the student exam record with the score
        $this->studentExamRepository->update([
            'score' => $correctCount,
        ], $studentExamId);

        return $correctCount;
    }
}
