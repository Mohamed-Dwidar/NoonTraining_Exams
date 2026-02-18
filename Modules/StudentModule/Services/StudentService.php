<?php

namespace Modules\StudentModule\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\StudentModule\Repository\StudentRepository;

class StudentService {
    private $studentRepository;

    public function __construct(StudentRepository $studentRepository) {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Get all students
     */
    public function findAll() {
        return $this->studentRepository->all();
    }

    /**
     * Get a single student
     */
    public function find($id) {
        return $this->studentRepository->find($id);
    }

    /**
     * Create new student
     */
    public function create(array $data) {
        return $this->studentRepository->create([
            'name'         => $data['name'],
            'email'        => $data['email'] ?? null,
            'phone'        => $data['phone'],
            'national_id'  => $data['national_id'] ?? null,
            'gender'       => $data['gender'] ?? null,
            'password'     => Hash::make($data['password']),
        ]);
    }

    /**
     * Update student
     */
    public function update(array $data) {
        $updateData = [
            'name'         => $data['name'],
            'email'        => $data['email'] ?? null,
            'phone'        => $data['phone'],
            'national_id'  => $data['national_id'] ?? null,
            'gender'       => $data['gender'] ?? null,
        ];

        // update password only if provided
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        return $this->studentRepository->update($updateData, $data['id']);
    }

    /**
     * Delete student
     */
    public function delete($id) {
        return $this->studentRepository->delete($id);
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
}
