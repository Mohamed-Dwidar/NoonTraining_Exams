<?php

namespace Modules\StudentModule\app\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ExamModule\app\Http\Models\Exam;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\QuestionModule\app\Http\Models\Question;
use Modules\QuestionModule\app\Http\Models\Answer;
use Modules\StudentModule\app\Http\Models\Student;
use Modules\StudentModule\Services\StudentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentExamController extends Controller
{
    private $students;

    public function __construct(StudentService $studentService)
    {
        $this->students = $studentService;
    }

    /**
     * Show exam start page
     */
    public function startExam($examId)
    {
        $student = Auth::guard('student')->user();

        $exam = Exam::findOrFail($examId);

        DB::table('student_exam')
            ->where('student_id', $student->id)
            ->where('exam_id', $examId)
            ->update([
                'status' => 'in_progress',
                'started_at' => Carbon::now()
            ]);

        $categoryId = $exam->category_id;

        $questions = Question::where('category_id', $categoryId)->get();

        return view('studentmodule::exam.start', compact('exam', 'questions'));
    }

    /**
     * Submit exam answers
     */
    public function submitExam(Request $request, $examId)
    {
        $student = Auth::guard('student')->user();

        $exam = Exam::findOrFail($examId);

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

        DB::table('student_exam')
            ->where('student_id', $student->id)
            ->where('exam_id', $examId)
            ->update([
                'status' => 'completed',
                'completed_at' => Carbon::now(),
                'score' => $percentage
            ]);

        return redirect()->route('studentmodule::exam.result', $examId);
    }

    /**
     * Show exam result
     */
    public function examResult($examId)
    {
        $student = Auth::guard('student')->user();

        $pivot = DB::table('student_exam')
            ->where('student_id', $student->id)
            ->where('exam_id', $examId)
            ->first();

        $exam = Exam::findOrFail($examId);

        return view('studentmodule::exam.result', compact('exam', 'pivot'));
    }
}
