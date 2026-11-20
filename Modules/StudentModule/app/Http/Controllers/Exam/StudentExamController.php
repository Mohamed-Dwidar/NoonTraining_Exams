<?php

namespace Modules\StudentModule\App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ExamModule\app\Http\Models\Exam;
use Modules\QuestionModule\app\Http\Models\Question;
use Modules\QuestionModule\app\Http\Models\Answer;
use Modules\StudentModule\app\Http\Models\StudentExam;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\ExamModule\Services\ExamService;
use Modules\QuestionModule\Services\QuestionService;
use Modules\StudentModule\Services\StudentExamService;
use Modules\TypingTestModule\Services\TypingTestService;

class StudentExamController extends Controller {
    private $examService;
    private $studentExamService;
    private $questionService;
    private $typingTestService;

    public function __construct(StudentExamService $studentExamService, ExamService $examService, QuestionService $questionService, TypingTestService $typingTestService) {
        $this->studentExamService = $studentExamService;
        $this->examService = $examService;
        $this->questionService = $questionService;
        $this->typingTestService = $typingTestService;
    }

    /**
     * Show exam start page
     */
    public function startExam(Request $request, $studentExamId) {
        $student = Auth::guard('student')->user();
        $studentExam = $this->studentExamService->findById($studentExamId);
        $exam = $studentExam->exam;

        // dd($studentExamId, $studentExam->toArray(), $studentExam->student_id, $student->id);

        if (!$studentExam || $studentExam->student_id != $student->id) {
            abort(404, 'Exam attempt not found');
        }

        if ($studentExam->status === 'completed') {
            return redirect()->route('student.exam.result', $studentExam->id);
        }

        // Exam linked to the typing test: the typing test comes first (one attempt per course attempt).
        // Passed -> straight to the questions. Failed -> result page offers "continue" (?proceed=1) or "exit".
        if ($exam->has_typing_test && $studentExam->status === 'not_started') {
            $typingTest = $this->typingTestService->getOrCreateForStudentExam($studentExam);

            if ($typingTest->status !== 'completed') {
                return redirect()->route('student.typing.start', $typingTest->id);
            }

            if (!$typingTest->passed && !$request->boolean('proceed')) {
                return redirect()->route('student.typing.result', $typingTest->id);
            }
        }

        //Initialize exam attempt if not already done
        if ($studentExam->status === 'not_started') {
            $this->initExam($studentExam->id);
        } elseif ($this->studentExamService->completeIfExpired($studentExam)) {
            // Time ran out while the student was away: graded and closed
            return redirect()->route('student.exam.result', $studentExam->id);
        }

        // Load existing questions for this attempt
        $examQuestions = $studentExam->studentExamAnswers;

        // If not started yet, update status
        if ($studentExam->status === 'not_started') {
            $studentExam->update([
                'status' => 'in_progress',
                'started_at' => Carbon::now()
            ]);
        }
        // Timer on the page comes from the server (resume on any device keeps the real remaining time)
        $remainingSeconds = $this->studentExamService->remainingSeconds($studentExam->fresh('exam'));
        return view('studentmodule::exam.start', compact('exam', 'examQuestions', 'studentExam', 'remainingSeconds'));
    }

    private function initExam($studentExamId) {
        $studentExam = $this->studentExamService->findById($studentExamId);
        $exam = $studentExam->exam;

        // Get MCQ questions based on exam criteria
        $mcqQuestions = $this->questionService->getRandomQuestionsByType(
            $exam->category_id,
            'mcq',
            $exam->mcq_count
        );

        // Get True/False questions based on exam criteria
        $trueFalseQuestions = $this->questionService->getRandomQuestionsByType(
            $exam->category_id,
            'true_false',
            $exam->true_false_count
        );
        // dd($exam->toArray(), $mcqQuestions->toArray(), $trueFalseQuestions->toArray());
        // Merge all questions
        $allQuestions = $mcqQuestions->merge($trueFalseQuestions)->shuffle();

        // Save each question using addQuestionAnswer
        foreach ($allQuestions as $question) {
            $this->studentExamService->addQuestionAnswer($studentExamId, $question->id);
        }
        return true;
    }

    /**
     * Submit exam answers
     */
    public function submitExam(Request $request, $examId) {
        $student = Auth::guard('student')->user();
        $exam = Exam::findOrFail($examId);

        // Get or create the attempt
        $attempt = StudentExam::firstOrCreate(
            [
                'student_id' => $student->id,
                'exam_id' => $examId,
            ]
        );

        $submitted = $request->answers; // array: question_id => user_answer
        $score = 0;
        $total = count($submitted);

        foreach ($submitted as $questionId => $userAnswer) {
            $correct = Answer::where('question_id', $questionId)->first();
            if (!$correct) continue;

            if (strtolower(trim($userAnswer)) == strtolower(trim($correct->correct_answer))) {
                $score++;
            }
        }

        $percentage = $total > 0 ? ($score / $total) * 100 : 0;

        // Update attempt
        $attempt->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
            'score' => $percentage
        ]);

        // You can also store individual question answers if needed
        // $this->storeQuestionAnswers($attempt->id, $submitted);

        return redirect()->route('student.exam.result', $examId);
    }

    /**
     * Show exam result
     */
    public function examResult($studentExamId) {
        $student = Auth::guard('student')->user();
        $studentExam = $this->studentExamService->findById($studentExamId);
        $exam = $studentExam->exam;
        if (!$studentExam || $studentExam->student_id != $student->id) {
            abort(404, 'Exam attempt not found');
        }
        $typingTest = $this->typingTestService->findForStudentExam($studentExam->id);
        return view('studentmodule::exam.result', compact('exam', 'studentExam', 'typingTest'));
    }

    /**
     * Store individual question answers (optional)
     */
    private function storeQuestionAnswers($attemptId, $answers) {
        foreach ($answers as $questionId => $userAnswer) {
            DB::table('student_exam_answers')->insert([
                'student_exam_id' => $attemptId,
                'question_id' => $questionId,
                'user_answer' => $userAnswer,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function submitAnswer(Request $request) {
        $request->validate([
            'studentExamId' => 'required|exists:student_exams,id',
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required'
        ]);

        $student = Auth::guard('student')->user();
        $studentExam = $this->studentExamService->findById($request->studentExamId);
        $studentExamAnswer = $this->studentExamService->getStudentExamAnswer($request->studentExamId, $request->question_id);
        if (!$studentExam || $studentExam->student_id != $student->id || !$studentExamAnswer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }



        $studentExamAnswer = $this->studentExamService->saveAnswer(
            $studentExamAnswer->id,
            $request->answer
        );

        return response()->json([
            'success' => true,
            'message' => 'Answer saved successfully.',
            'data' => $studentExamAnswer
        ]);
    }

    public function completeExam($studentExamId) {
        $student = Auth::guard('student')->user();
        $studentExam = $this->studentExamService->findById($studentExamId);

        if (!$studentExam || $studentExam->student_id != $student->id) {
            return redirect()->route('student.dashboard')->with('error', 'Unauthorized');
        }

        // Correct answers and calculate score
        $this->studentExamService->correctExamAnswers($studentExamId);

        // Update student exam status
        $this->studentExamService->update($studentExamId, [
            'status' => 'completed',
            'completed_at' => Carbon::now()
        ]);

        return redirect()->route('student.exam.result', $studentExamId);
    }
}
