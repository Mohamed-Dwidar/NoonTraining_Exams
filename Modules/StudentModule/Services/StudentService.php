<?php

namespace Modules\StudentModule\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\StudentModule\Repository\StudentExamRepository;
use Modules\StudentModule\Repository\StudentRepository;

class StudentService {
    private $studentRepository;
    private $studentExamRepository;

    public function __construct(StudentRepository $studentRepository, StudentExamRepository $studentExamRepository) {
        $this->studentRepository = $studentRepository;
        $this->studentExamRepository = $studentExamRepository;
    }

    /**
     * Get all students
     */
    public function findAll() {
        return $this->studentRepository->all();
    }

    public function paginate($perPage = 15) {
        return $this->studentRepository->paginate($perPage);
    }

    public function filter($request = []) {
        return $this->studentRepository->filter($request);
    }

    /**
     * Get a single student
     */
    public function find($id) {
        return $this->studentRepository->find($id);
    }

    public function findByNationalId($nationalId) {
        return $this->studentRepository->findByNationalId($nationalId);
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
}
