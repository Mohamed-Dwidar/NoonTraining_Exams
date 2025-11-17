<?php

namespace Modules\ExamModule\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ExamModule\Services\ExamService;
use Modules\ExamModule\Services\QuestionService;
use Illuminate\Validation\ValidationException;

class ExamModuleController extends Controller
{
    private $examService;
    private $questionService;

    public function __construct(ExamService $examService, QuestionService $questionService)
    {
        $this->examService = $examService;
        $this->questionService = $questionService;
    }

    /**
     * List all exams
     */
    public function index()
    {
        $exams = $this->examService->findAll();
        return view('exammodule::admin.index', compact('exams'));
    }

    /**
     * Show create form (can be empty exam)
     */
    public function create()
    {
        return view('exammodule::admin.create'); // same blade for create/edit
    }

    /**
     * Store a new exam (can be empty)
     */
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

        return redirect()->route('admin.exam.edit', $exam->id)
            ->with('success', 'تم إنشاء الامتحان بنجاح. يمكنك الآن إضافة الأسئلة.');
    }

    /**
     * Show edit form with questions
     */
    public function edit($id)
    {
        $exam = $this->examService->findOne($id); // includes questions & answers
        return view('exammodule::admin.edit', compact('exam'));
    }

    /**
     * Update exam info
     */
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

        return redirect()->route('admin.exam.edit', $id)
            ->with('success', 'تم تحديث بيانات الامتحان.');
    }

    /**
     * Delete an exam
     */
    public function destroy($id)
    {
        $this->examService->deleteOne($id);

        return redirect()->route('admin.exam.index')
            ->with('success', 'تم حذف الامتحان بنجاح.');
    }


    public function questions($id)
    {
        $exam = $this->examService->findOne($id);
        return view('exammodule::questions.create', compact('exam'));
    }

    public function showQuestions($examId)
    {
        $exam = $this->examService->findOne($examId); // includes questions & answers
        return view('exammodule::questions.show', compact('exam'));
    }


    /**
     * Store multiple questions at once
     */
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
                'id' => $question['id'] ?? null, // include ID for existing
                'question_text' => $question['question_text'],
                'type' => $question['type'],
                'options' => $question['type'] === 'mcq' ? ($question['options'] ?? null) : null,
                'answer' => $question['answer'],
            ];
        }

        $this->questionService->createMultiple($questionsData, $request->exam_id);

        return redirect()->route('exammodule::questions.show', $request->exam_id)
            ->with('success', 'تم حفظ جميع الأسئلة بنجاح.');
    }



    /**
     * Update a single question
     */
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

    /**
     * Delete a single question
     */
    public function deleteQuestion($id)
    {
        $this->questionService->delete($id);

        return back()->with('success', 'تم حذف السؤال بنجاح.');
    }
}
