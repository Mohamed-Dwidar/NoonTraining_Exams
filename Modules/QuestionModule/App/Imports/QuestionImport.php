<?php

namespace Modules\QuestionModule\App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\QuestionModule\Services\QuestionService;

class QuestionImport implements ToCollection {
    protected $questionService;
    protected $categoryId;
    protected $questionType;

    public function __construct(QuestionService $questionService, $categoryId, $questionType) {
        $this->questionService = $questionService;
        $this->categoryId = $categoryId;
        $this->questionType = $questionType;
    }

    public function collection(Collection $rows) {
        foreach ($rows as $key => $row) {
            // Skip the header row
            if ($key === 0) {
                continue;
            }
            if ($row[0] === null) {
                // Handle error: Question text is required
                continue; // Skip this row or log an error
            }

            // Prepare question data based on type
            $data = [
                'category_id' => $this->categoryId,
                'type' => $this->questionType,
                'question_text' => $row[0],
            ];

            // If MCQ, add options and answer
            if ($this->questionType === 'mcq') {
                if ($row[1] === null || $row[2] === null || $row[3] === null || $row[4] === null || $row[5] === null) {
                    // Handle error: Not enough columns for MCQ
                    continue; // Skip this row or log an error
                }
                $options = [
                    'A' => $row[1] ?? '',
                    'B' => $row[2] ?? '',
                    'C' => $row[3] ?? '',
                    'D' => $row[4] ?? '',
                ];
                // $answerLetter = strtoupper($row[5] ?? '');
                $data['options'] = $options;
                // $data['answer'] = $options[$answerLetter] ?? ''; // Map letter to actual answer text
                $data['answer'] = $row[5];
            } else {
                if ($row[1] === null || !in_array(strtolower((string)$row[1]), ['صح', 'خطأ'])) {
                    // Handle error: Answer is required for true/false
                    continue; // Skip this row or log an error
                }
                // For true_false
                $data['answer'] = strtolower((string)$row[1]) === 'صح'  ? 'true' : 'false';
            }
            // dd($data);
            $this->questionService->create($data);
        }
    }
}
