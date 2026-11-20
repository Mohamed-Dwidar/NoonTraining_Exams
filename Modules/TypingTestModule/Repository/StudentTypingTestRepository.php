<?php

namespace Modules\TypingTestModule\Repository;

use Modules\TypingTestModule\App\Http\Models\StudentTypingTest;
use Prettus\Repository\Eloquent\BaseRepository;

class StudentTypingTestRepository extends BaseRepository {
    function model() {
        return StudentTypingTest::class;
    }

    public function standaloneForStudent(int $studentId) {
        return StudentTypingTest::query()
            ->where('student_id', $studentId)
            ->whereNull('student_exam_id')
            ->orderByDesc('id')
            ->get();
    }

    public function linkedForStudent(int $studentId) {
        return StudentTypingTest::query()
            ->where('student_id', $studentId)
            ->whereNotNull('student_exam_id')
            ->get();
    }

    public function inProgressForStudent(int $studentId) {
        return StudentTypingTest::query()
            ->where('student_id', $studentId)
            ->where('status', 'in_progress')
            ->get();
    }

    public function forStudentExam(int $studentExamId): ?StudentTypingTest {
        return StudentTypingTest::query()->where('student_exam_id', $studentExamId)->first();
    }

    public function results(array $filters = []) {
        $query = StudentTypingTest::query()
            ->with(['student', 'studentExam.exam'])
            ->where('status', 'completed');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('national_id', 'LIKE', "%$search%")
                    ->orWhere('phone', 'LIKE', "%$search%");
            });
        }

        if (isset($filters['result']) && in_array($filters['result'], ['passed', 'failed'], true)) {
            $query->where('passed', $filters['result'] === 'passed');
        }

        if (isset($filters['type']) && $filters['type'] === 'standalone') {
            $query->whereNull('student_exam_id');
        } elseif (isset($filters['type']) && $filters['type'] === 'linked') {
            $query->whereNotNull('student_exam_id');
        }

        return $query->orderByDesc('completed_at');
    }
}
