<?php

namespace Modules\ExamModule\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ExamModule\Services\ExamService;

class ExamModuleController extends Controller
{
    private $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
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
     * Show create exam form
     */
    public function create()
    {
        return view('exammodule::admin.create');
    }

    /**
     * Store a new exam
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration_minutes' => 'required|integer|min:1',
            'total_questions' => 'required|integer|min:1',
            'mcq_count' => 'nullable|integer|min:0',
            'true_false_count' => 'nullable|integer|min:0',
            'success_grade' => 'required|numeric|min:0',
            'total_grade' => 'required|numeric|min:0',
        ], [
            'title.required' => 'عنوان الامتحان مطلوب',
            'start_date.required' => 'تاريخ البداية مطلوب',
            'end_date.required' => 'تاريخ النهاية مطلوب',
            'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو مساوي لتاريخ البداية',
            'duration_minutes.required' => 'مدة الامتحان مطلوبة',
            'total_questions.required' => 'عدد الأسئلة مطلوب',
            'success_grade.required' => 'درجة النجاح مطلوبة',
            'total_grade.required' => 'الدرجة الكلية مطلوبة',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->examService->create($request->all());

        return redirect()->route('admin.exam.index')
            ->with('success', 'تم حفظ الامتحان بنجاح.');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $exam = $this->examService->findOne($id);
        return view('exammodule::admin.edit', compact('exam'));
    }

    /**
     * Update an existing exam
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:exams,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration_minutes' => 'required|integer|min:1',
            'total_questions' => 'required|integer|min:1',
            'mcq_count' => 'nullable|integer|min:0',
            'true_false_count' => 'nullable|integer|min:0',
            'success_grade' => 'required|numeric|min:0',
            'total_grade' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->examService->update($request->all());

        return redirect()->route('admin.exam.index')
            ->with('success', 'تم تعديل الامتحان بنجاح.');
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
}
