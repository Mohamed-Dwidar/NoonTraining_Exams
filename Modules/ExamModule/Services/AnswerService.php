<?php

namespace Modules\ExamModule\Services;

use Modules\ExamModule\Repository\ExamRepository;
use Carbon\Carbon;

class QuestionService
{
    private $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    /**
     * Create a new exam
     */
    public function create(array $data)
    {
        $exam_data = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'duration_minutes' => $data['duration_minutes'],
            'total_questions' => $data['total_questions'],
            'mcq_count' => $data['mcq_count'] ?? 0,
            'true_false_count' => $data['true_false_count'] ?? 0,
            'success_grade' => $data['success_grade'],
            'total_grade' => $data['total_grade'],
            'created_by' => auth()->id(),
        ];

        return $this->examRepository->create($exam_data);
    }

    /**
     * Update an existing exam
     */
    public function update(array $data)
    {
        $exam_data = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'duration_minutes' => $data['duration_minutes'],
            'total_questions' => $data['total_questions'],
            'mcq_count' => $data['mcq_count'] ?? 0,
            'true_false_count' => $data['true_false_count'] ?? 0,
            'success_grade' => $data['success_grade'],
            'total_grade' => $data['total_grade'],
            'created_by' => $data['created_by'] ?? null,
        ];

        return $this->examRepository->update($exam_data, $data['id']);
    }

    /**
     * Find all exams
     */
    public function findAll()
    {
        return $this->examRepository->get();
    }

    /**
     * Find one exam by ID
     */
    public function findOne($id)
    {
        return $this->examRepository->find($id);
    }

    /**
     * Delete an exam
     */
    public function deleteOne($id)
    {
        return $this->examRepository->delete($id);
    }

    /**
     * Count total exams
     */
    public function countAll()
    {
        return $this->examRepository->count();
    }

    /**
     * Count upcoming exams (start_date > today)
     */
    public function countUpcoming()
    {
        $today = Carbon::today()->toDateString();
        return $this->examRepository->where('start_date', '>', $today)->count();
    }

    /**
     * Count ongoing exams (start_date <= today <= end_date)
     */
    public function countOngoing()
    {
        $today = Carbon::today()->toDateString();
        return $this->examRepository->where('start_date', '<=', $today)
                                    ->where('end_date', '>=', $today)
                                    ->count();
    }

    /**
     * Count finished exams (end_date < today)
     */
    public function countFinished()
    {
        $today = Carbon::today()->toDateString();
        return $this->examRepository->where('end_date', '<', $today)->count();
    }

    /**
     * Get upcoming exams list (optional for dashboard)
     */
    public function upcomingExams($limit = 5)
    {
        $today = Carbon::today()->toDateString();
        return $this->examRepository->where('start_date', '>=', $today)
                                    ->orderBy('start_date', 'asc')
                                    ->limit($limit)
                                    ->get();
    }
}
