<?php

namespace Modules\TypingTestModule\Services;

use Carbon\Carbon;
use Modules\TypingTestModule\App\Http\Models\StudentTypingTest;
use Modules\TypingTestModule\Repository\StudentTypingTestRepository;

/**
 * Student typing test attempts (standalone or linked to a course attempt).
 */
class TypingTestService {
    /** Seconds accepted after the deadline (network latency on auto-submit). */
    public const GRACE_SECONDS = 30;

    private $attemptRepository;
    private $settingService;
    private $textService;

    public function __construct(
        StudentTypingTestRepository $attemptRepository,
        TypingTestSettingService $settingService,
        TypingTextService $textService
    ) {
        $this->attemptRepository = $attemptRepository;
        $this->settingService = $settingService;
        $this->textService = $textService;
    }

    public function find($id): ?StudentTypingTest {
        return $this->attemptRepository->find($id);
    }

    // ================= Admin / staff =================

    /** Standalone attempt: each assignment gives the student one attempt. */
    public function assignToStudent(int $studentId, ?int $createdBy = null): StudentTypingTest {
        return $this->attemptRepository->create([
            'student_id' => $studentId,
            'status'     => 'not_started',
            'created_by' => $createdBy,
        ]);
    }

    /** Only a standalone attempt that hasn't started can be unassigned. */
    public function unassign(int $attemptId): bool {
        $attempt = $this->attemptRepository->find($attemptId);
        if ($attempt->status !== 'not_started' || $attempt->isLinked()) {
            return false;
        }
        $this->attemptRepository->delete($attemptId);
        return true;
    }

    public function results(array $filters = []) {
        return $this->attemptRepository->results($filters);
    }

    // ================= Student =================

    public function standaloneForStudent(int $studentId) {
        return $this->attemptRepository->standaloneForStudent($studentId);
    }

    public function linkedForStudent(int $studentId) {
        return $this->attemptRepository->linkedForStudent($studentId);
    }

    /** Grade typing attempts whose time ran out while the student was away. */
    public function completeExpiredForStudent(int $studentId): void {
        foreach ($this->attemptRepository->inProgressForStudent($studentId) as $attempt) {
            if ($this->isExpired($attempt)) {
                $this->complete($attempt);
            }
        }
    }

    public function findForStudentExam(int $studentExamId): ?StudentTypingTest {
        return $this->attemptRepository->forStudentExam($studentExamId);
    }

    /** One typing attempt per course attempt. */
    public function getOrCreateForStudentExam($studentExam): StudentTypingTest {
        $attempt = $this->attemptRepository->forStudentExam($studentExam->id);

        return $attempt ?: $this->attemptRepository->create([
            'student_id'      => $studentExam->student_id,
            'student_exam_id' => $studentExam->id,
            'status'          => 'not_started',
        ]);
    }

    /** True when a course attempt has a typing attempt the student already opened. */
    public function hasStartedForStudentExam(int $studentExamId): bool {
        $attempt = $this->attemptRepository->forStudentExam($studentExamId);
        return $attempt && $attempt->status !== 'not_started';
    }

    /**
     * Start the attempt on first open: draw a random text and snapshot the settings.
     * Returns null when the text bank is empty.
     */
    public function begin(StudentTypingTest $attempt): ?StudentTypingTest {
        if ($attempt->status !== 'not_started') {
            return $attempt;
        }

        $settings = $this->settingService->get();
        $text = $this->textService->randomActive($settings->word_count);
        if (!$text) {
            return null;
        }

        $source = TypingEvaluator::firstWords($text->content, $settings->word_count);

        return $this->attemptRepository->update([
            'source_text'      => $source,
            'word_count'       => count(TypingEvaluator::words($source)),
            'duration_minutes' => $settings->duration_minutes,
            'success_grade'    => $settings->success_grade,
            'status'           => 'in_progress',
            'started_at'       => Carbon::now(),
        ], $attempt->id);
    }

    public function remainingSeconds(StudentTypingTest $attempt): int {
        if (!$attempt->started_at) {
            return (int) $attempt->duration_minutes * 60;
        }
        $deadline = $attempt->started_at->copy()->addMinutes((int) $attempt->duration_minutes);
        return max(0, (int) ceil(Carbon::now()->diffInSeconds($deadline, false)));
    }

    public function isExpired(StudentTypingTest $attempt): bool {
        return $attempt->status === 'in_progress' && $this->remainingSeconds($attempt) <= 0;
    }

    /** Within the time limit plus the grace period. */
    private function acceptsInput(StudentTypingTest $attempt): bool {
        if ($attempt->status !== 'in_progress' || !$attempt->started_at) {
            return false;
        }
        $limit = $attempt->started_at->copy()
            ->addMinutes((int) $attempt->duration_minutes)
            ->addSeconds(self::GRACE_SECONDS);
        return Carbon::now()->lessThanOrEqualTo($limit);
    }

    /** Periodic autosave of the typed text. */
    public function saveProgress(StudentTypingTest $attempt, ?string $typed): bool {
        if (!$this->acceptsInput($attempt)) {
            return false;
        }
        $this->attemptRepository->update([
            'typed_text'  => $typed,
            'typed_words' => count(TypingEvaluator::words($typed)),
        ], $attempt->id);
        return true;
    }

    /**
     * Grade and close the attempt. A submission received after the deadline (plus grace)
     * is ignored and the last autosaved text is graded instead.
     */
    public function complete(StudentTypingTest $attempt, ?string $typed = null): StudentTypingTest {
        if ($attempt->status === 'completed') {
            return $attempt;
        }

        $text = ($typed !== null && $this->acceptsInput($attempt)) ? $typed : $attempt->typed_text;
        $result = TypingEvaluator::evaluate($attempt->source_text, $text);

        return $this->attemptRepository->update([
            'typed_text'    => $text,
            'typed_words'   => $result['typed_words'],
            'correct_words' => $result['correct'],
            'wrong_words'   => $result['wrong'],
            'passed'        => $result['correct'] >= (int) $attempt->success_grade,
            'status'        => 'completed',
            'completed_at'  => Carbon::now(),
        ], $attempt->id);
    }

    /** Per-word statuses of a stored attempt (for result/detail pages). */
    public function evaluation(StudentTypingTest $attempt): array {
        return TypingEvaluator::evaluate($attempt->source_text, $attempt->typed_text);
    }
}
