<?php

namespace Modules\ExamModule\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\ExamModule\Services\ExamService;
use Modules\ExamModule\Services\QuestionService;

class ExamModuleController extends Controller
{
    private $examService;
    private $questionService;

    public function __construct(ExamService $examService, QuestionService $questionService)
    {
        $this->examService = $examService;
        $this->questionService = $questionService;
    }

    public function index()
    {
        $exams = $this->examService->findAll();
        return view('exammodule::admin.index', compact('exams'));
    }

    public function create()
    {
        return view('exammodule::admin.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_questions' => 'nullable|integer|min:0',
            'mcq_count' => 'nullable|integer|min:0',
            'true_false_count' => 'nullable|integer|min:0',
            'success_grade' => 'nullable|numeric|min:0',
            'total_grade' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $exam = $this->examService->create($request->all());
        
        return redirect()->route(Auth::getDefaultDriver() . '.exam.edit', $exam->id)
            ->with('success', 'تم إنشاء الامتحان بنجاح. يمكنك الآن إضافة الأسئلة.');
    }

    public function edit($id)
    {
        $exam = $this->examService->findOne($id);
        return view('exammodule::admin.edit', compact('exam'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_questions' => 'nullable|integer|min:0',
            'mcq_count' => 'nullable|integer|min:0',
            'true_false_count' => 'nullable|integer|min:0',
            'success_grade' => 'nullable|numeric|min:0',
            'total_grade' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $request->merge(['id' => $id]);
        $this->examService->update($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.exam.edit', $id)
            ->with('success', 'تم تحديث بيانات الامتحان.');
    }

    public function destroy($id)
    {
        $this->examService->deleteOne($id);

        return redirect()->route(Auth::getDefaultDriver() . '.exam.index')
            ->with('success', 'تم حذف الامتحان بنجاح.');
    }

    public function questions($id)
    {
        $exam = $this->examService->findOne($id);
        return view('exammodule::questions.create', compact('exam'));
    }

    public function showQuestions($examId)
    {
        $exam = $this->examService->findOne($examId);
        return view('exammodule::questions.show', compact('exam'));
    }

    public function storeQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:mcq,true_false',
            'questions.*.answer' => 'required|string',
            'questions.*.options' => 'nullable|array',
            'questions.*.id' => 'nullable|integer|exists:questions,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $questionsData = [];
        foreach ($request->questions as $question) {
            $questionsData[] = [
                'id' => $question['id'] ?? null,
                'question_text' => $question['question_text'],
                'type' => $question['type'],
                'options' => $question['type'] === 'mcq' ? ($question['options'] ?? null) : null,
                'answer' => $question['answer'],
            ];
        }

        $this->questionService->createMultiple($questionsData, $request->exam_id);

        return redirect()->route(Auth::getDefaultDriver() . '.exam.question.show', $request->exam_id)
            ->with('success', 'تم حفظ جميع الأسئلة بنجاح.');
    }

    public function updateQuestion(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'type' => 'required|in:mcq,true_false',
            'answer' => 'required|string',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->questionService->update([
            'id' => $id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'options' => $request->type === 'mcq' ? ($request->options ?? null) : null,
            'answer' => $request->answer,
        ]);

        return back()->with('success', 'تم تحديث السؤال بنجاح.');
    }

    public function deleteQuestion($id)
    {
        $this->questionService->delete($id);

        return back()->with('success', 'تم حذف السؤال بنجاح.');
    }
}
