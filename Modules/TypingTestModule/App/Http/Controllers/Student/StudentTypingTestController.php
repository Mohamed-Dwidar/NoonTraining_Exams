<?php

namespace Modules\TypingTestModule\App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\TypingTestModule\App\Http\Models\StudentTypingTest;
use Modules\TypingTestModule\Services\TypingTestService;
use Modules\TypingTestModule\Services\TypingTestSettingService;

class StudentTypingTestController extends Controller {
    private $typingTestService;
    private $settingService;

    public function __construct(TypingTestService $typingTestService, TypingTestSettingService $settingService) {
        $this->typingTestService = $typingTestService;
        $this->settingService = $settingService;
    }

    public function start($id) {
        $attempt = $this->ownedAttempt($id);

        if ($attempt->status === 'completed') {
            return redirect()->route('student.typing.result', $attempt->id);
        }

        if ($this->typingTestService->isExpired($attempt)) {
            $attempt = $this->typingTestService->complete($attempt);
            return $this->afterComplete($attempt);
        }

        $started = $this->typingTestService->begin($attempt);
        if (!$started) {
            return redirect()->route('student.dashboard')
                ->withErrors(['typing' => 'لا توجد نصوص متاحة لأختبار سرعة الكتابة حالياً، يرجى التواصل مع الإدارة.']);
        }

        $attempt = $started;
        $settings = $this->settingService->get();
        $remainingSeconds = $this->typingTestService->remainingSeconds($attempt);
        $examTitle = $attempt->isLinked() ? optional($attempt->studentExam->exam)->title : null;

        return view('typingtestmodule::student.start', compact('attempt', 'settings', 'remainingSeconds', 'examTitle'));
    }

    /** AJAX autosave. */
    public function save(Request $request, $id) {
        $attempt = $this->ownedAttempt($id);
        $request->validate(['typed_text' => 'nullable|string|max:100000']);

        $saved = $this->typingTestService->saveProgress($attempt, (string) $request->input('typed_text', ''));

        return response()->json([
            'success'   => $saved,
            'remaining' => $this->typingTestService->remainingSeconds($attempt),
        ]);
    }

    public function submit(Request $request, $id) {
        $attempt = $this->ownedAttempt($id);
        $request->validate(['typed_text' => 'nullable|string|max:100000']);

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('student.typing.result', $attempt->id);
        }

        $attempt = $this->typingTestService->complete($attempt, (string) $request->input('typed_text', ''));

        return $this->afterComplete($attempt);
    }

    public function result($id) {
        $attempt = $this->ownedAttempt($id);

        if ($attempt->status !== 'completed') {
            return redirect()->route('student.typing.start', $attempt->id);
        }

        $evaluation = $this->typingTestService->evaluation($attempt);
        $studentExam = $attempt->isLinked() ? $attempt->studentExam : null;

        return view('typingtestmodule::student.result', compact('attempt', 'evaluation', 'studentExam'));
    }

    /** Linked + passed: go straight to the questions. Otherwise show the result. */
    private function afterComplete(StudentTypingTest $attempt) {
        if ($attempt->isLinked() && $attempt->passed) {
            return redirect()->route('student.exam.start', $attempt->student_exam_id);
        }
        return redirect()->route('student.typing.result', $attempt->id);
    }

    private function ownedAttempt($id): StudentTypingTest {
        $attempt = $this->typingTestService->find($id);
        if (!$attempt || (int) $attempt->student_id !== (int) Auth::guard('student')->id()) {
            abort(404);
        }
        return $attempt;
    }
}
