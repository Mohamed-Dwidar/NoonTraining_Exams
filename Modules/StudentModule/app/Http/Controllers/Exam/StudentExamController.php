<?php

namespace Modules\StudentModule\app\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ExamModule\app\Http\Models\Exam;
use Modules\QuestionModule\app\Http\Models\Question;
use Modules\QuestionModule\app\Http\Models\Answer;
use Modules\StudentModule\app\Http\Models\StudentExam;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentExamController extends Controller
{
    /**
     * Show exam start page
     */
    public function startExam($examId)
    {
        $student = Auth::guard('student')->user();
        $exam = Exam::findOrFail($examId);

        // Check if already started
        $attempt = StudentExam::firstOrCreate(
            [
                'student_id' => $student->id,
                'exam_id' => $examId,
            ],
            [
                'status' => 'not_started',
                'started_at' => null,
                'completed_at' => null,
                'score' => 0,
            ]
        );

        // If not started yet, update status
        if ($attempt->status === 'not_started') {
            $attempt->update([
                'status' => 'in_progress',
                'started_at' => Carbon::now()
            ]);
        }

        // Get questions for this exam's category
        $questions = Question::where('category_id', $exam->category_id)->get();

        return view('studentmodule::exam.start', compact('exam', 'questions', 'attempt'));
    }

    /**
     * Submit exam answers
     */
    public function submitExam(Request $request, $examId)
    {
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
    public function examResult($examId)
    {
        $student = Auth::guard('student')->user();
        $exam = Exam::findOrFail($examId);

        // Get the attempt using the relationship
        $attempt = $exam->studentAttempt($student->id)->first();

        // Alternative: Get directly from StudentExam model
        // $attempt = StudentExam::where('student_id', $student->id)
        //     ->where('exam_id', $examId)
        //     ->first();

        if (!$attempt) {
            abort(404, 'Exam attempt not found');
        }

        return view('studentmodule::exam.result', compact('exam', 'attempt'));
    }

    /**
     * Store individual question answers (optional)
     */
    private function storeQuestionAnswers($attemptId, $answers)
    {
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
}