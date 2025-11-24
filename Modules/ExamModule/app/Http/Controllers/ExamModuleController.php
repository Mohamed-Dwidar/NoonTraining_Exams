<?php

namespace Modules\ExamModule\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\ExamModule\Services\ExamService;
use Modules\QuestionModule\app\Http\Models\Category;

class ExamModuleController extends Controller
{
    private $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    // ================= Exams =================
    public function index()
    {
        $exams = $this->examService->findAll();
        return view('exammodule::admin.index', compact('exams'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('exammodule::admin.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_questions'  => 'nullable|integer|min:1',
            'mcq_count'        => 'nullable|integer|min:0',
            'true_false_count' => 'nullable|integer|min:0',
            'success_grade'    => 'nullable|numeric|min:0',
            'total_grade'      => 'nullable|numeric|min:0',
        ]);

        $validator->after(function ($validator) use ($request) {

            $data = $request->all();

            if (!empty($data['start_date']) && !empty($data['end_date']) && empty($data['duration_minutes'])) {
                $validator->errors()->add('duration_minutes', 'يجب إدخال مدة الامتحان عند تحديد تاريخ بداية ونهاية.');
            }

            $mcq    = (int) ($data['mcq_count'] ?? 0);
            $tf     = (int) ($data['true_false_count'] ?? 0);
            $totalQ = (int) ($data['total_questions'] ?? 0);

            if (($mcq > 0 || $tf > 0) && empty($totalQ)) {
                $validator->errors()->add('total_questions', 'يجب إدخال إجمالي عدد الأسئلة عند إدخال توزيع الأسئلة.');
            }

            if ($totalQ > 0 && ($mcq + $tf) > $totalQ) {
                $validator->errors()->add('mcq_count', 'إجمالي توزيع الأسئلة أكبر من العدد الكلي.');
                $validator->errors()->add('true_false_count', 'إجمالي توزيع الأسئلة أكبر من العدد الكلي.');
            }

            $success = $data['success_grade'] ?? null;
            $totalG  = $data['total_grade'] ?? null;

            if (!is_null($success) && is_null($totalG)) {
                $validator->errors()->add('total_grade', 'يجب إدخال الدرجة الكلية عند تحديد درجة النجاح.');
            }

            if (!is_null($success) && !is_null($totalG) && $success > $totalG) {
                $validator->errors()->add('success_grade', 'درجة النجاح لا يمكن أن تتجاوز الدرجة الكلية للامتحان.');
            }
        });

        $validator->validate();

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $exam = $this->examService->create($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.exam.edit', $exam->id)
            ->with('success', 'تم إنشاء الامتحان بنجاح.');
    }

    public function edit($id)
    {
        $exam = $this->examService->findOne($id);
        $categories = Category::all();
        return view('exammodule::admin.edit', compact('exam', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_questions' => 'nullable|integer|min:0',
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
}
