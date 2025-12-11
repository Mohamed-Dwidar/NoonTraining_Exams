<?php

namespace Modules\StudentModule\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\StudentModule\Repository\StudentRepository;

class StudentService
{
    private $students;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->students = $studentRepository;
    }

    /**
     * Get all students
     */
    public function findAll()
    {
        return $this->students->all();
    }

    /**
     * Get a single student
     */
    public function find($id)
    {
        return $this->students->find($id);
    }

    /**
     * Create new student
     */
    public function create(array $data)
    {
        $this->validateStudent($data);

        return $this->students->create([
            'name'         => $data['name'],
            'email'        => $data['email'] ?? null,
            'phone'        => $data['phone'],
            'national_id'  => $data['national_id'] ?? null,
            'birth_date'   => $data['birth_date'] ?? null,
            'gender'       => $data['gender'] ?? null,
            'student_code' => $data['student_code'] ?? null,
            'password'     => Hash::make($data['password']),
        ]);
    }

    /**
     * Update student
     */
    public function update(array $data)
    {
        $this->validateStudent($data, $isUpdate = true);

        $updateData = [
            'name'         => $data['name'],
            'email'        => $data['email'] ?? null,
            'phone'        => $data['phone'],
            'national_id'  => $data['national_id'] ?? null,
            'birth_date'   => $data['birth_date'] ?? null,
            'gender'       => $data['gender'] ?? null,
            'student_code' => $data['student_code'] ?? null,

        ];

        // update password only if provided
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        return $this->students->update($updateData, $data['id']);
    }

    /**
     * Delete student
     */
    public function delete($id)
    {
        return $this->students->delete($id);
    }

    public function assignExamToStudent($studentId, array $examIds)
    {
        $student = $this->students->find($studentId);

        if (!$student) {
            throw ValidationException::withMessages([
                'student' => 'Student not found.',
            ]);
        }

        // Get exams that are already assigned to this student
        $alreadyAssignedExams = $student->exams()
            ->whereIn('exam_id', $examIds)
            ->pluck('exam_id')
            ->toArray();

        $newExamIds = array_diff($examIds, $alreadyAssignedExams);

        if (empty($newExamIds)) {
            throw ValidationException::withMessages([
                'exam' => 'All these exams are already assigned to the student.',
            ]);
        }
        
        $student->exams()->attach($newExamIds);

        $result = [
            'success' => true,
            'student' => $student->load('exams'),
            'added_exams' => $newExamIds,
            'already_assigned_exams' => $alreadyAssignedExams,
            'message' => count($newExamIds) . ' exam(s) assigned successfully. ' .
                count($alreadyAssignedExams) . ' exam(s) were already assigned.'
        ];

        return $result;
    }


    /**
     * Student validation rules
     */
    private function validateStudent(array $data, bool $isUpdate = false)
    {
        if (empty($data['name'])) {
            throw ValidationException::withMessages(['name' => 'Student name is required.']);
        }

        if (empty($data['phone'])) {
            throw ValidationException::withMessages(['phone' => 'Phone number is required.']);
        }

        if (!$isUpdate && empty($data['password'])) {
            throw ValidationException::withMessages(['password' => 'Password is required.']);
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages(['email' => 'Invalid email format.']);
        }

        if (!empty($data['national_id']) && strlen($data['national_id']) !== 14) {
            throw ValidationException::withMessages(['national_id' => 'National ID must be 14 digits.']);
        }

        if (!empty($data['gender']) && !in_array($data['gender'], ['male', 'female'])) {
            throw ValidationException::withMessages(['gender' => 'Gender must be male or female.']);
        }
    }
}
