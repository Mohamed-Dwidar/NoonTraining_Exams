<?php

namespace Modules\QuestionModule\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\QuestionModule\Services\QuestionService;

class QuestionModuleController extends Controller {
    private $questionService;

    public function __construct(QuestionService $questionService) {
        $this->questionService = $questionService;
    }

    // ================= Questions =================
    public function indexQuestions() {
        $categories = Category::all();
        $questions = $this->questionService->paginate(30);
        return view('questionmodule::questions.index', compact('questions', 'categories'));
    }

    public function createQuestion() {
        $categories = Category::all();
        $questions = $this->questionService->findAll();
        return view('questionmodule::questions.create', compact('categories', 'questions'));
    }

    public function storeQuestion(Request $request) {
        $validator = Validator::make($request->all(), [
            'questions' => 'required|array|min:1',
            'questions.*.category_id' => 'required|exists:categories,id',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:mcq,true_false',
            'questions.*.answer' => 'required|string',
            'questions.*.options' => 'required_if:questions.*.type,mcq|array',
            'questions.*.options.A' => 'required_if:questions.*.type,mcq|string',
            'questions.*.options.B' => 'required_if:questions.*.type,mcq|string',
            'questions.*.options.C' => 'required_if:questions.*.type,mcq|string',
            'questions.*.options.D' => 'required_if:questions.*.type,mcq|string',
        ], [
            'questions.required' => 'يجب تقديم أسئلة واحدة على الأقل.',
            'questions.*.category_id.required' => 'معرف الفئة مطلوب لكل سؤال.',
            'questions.*.category_id.exists' => 'الفئة المحددة غير موجودة لكل سؤال.',
            'questions.*.question_text.required' => 'نص السؤال مطلوب لكل سؤال.',
            'questions.*.type.required' => 'نوع السؤال مطلوب لكل سؤال.',
            'questions.*.type.in' => 'نوع السؤال غير صالح لكل سؤال. يجب أن يكون "mcq" أو "true_false".',
            'questions.*.answer.required' => 'الإجابة الصحيحة مطلوبة لكل سؤال.',
            'questions.*.options.A.required_if' => 'الخيار A مطلوب لأن نوع السؤال هو اختيار من متعدد لكل سؤال.',
            'questions.*.options.B.required_if' => 'الخيار B مطلوب لأن نوع السؤال هو اختيار من متعدد لكل سؤال.',
            'questions.*.options.C.required_if' => 'الخيار C مطلوب لأن نوع السؤال هو اختيار من متعدد لكل سؤال.',
            'questions.*.options.D.required_if' => 'الخيار D مطلوب لأن نوع السؤال هو اختيار من متعدد لكل سؤال.',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $createdCount = 0;
        foreach ($request->questions as $questionData) {
            $this->questionService->create([
                'category_id' => $questionData['category_id'],
                'question_text' => $questionData['question_text'],
                'type' => $questionData['type'],
                'options' => $questionData['type'] === 'mcq' ? ($questionData['options'] ?? null) : null,
                'answer' => $questionData['answer'],
            ]);
            $createdCount++;
        }

        return redirect()->route(Auth::getDefaultDriver() . '.questions.index')
            ->with('success', "تم إنشاء {$createdCount} أسئلة بنجاح.");
    }

    public function editQuestion($id) {
        $question = $this->questionService->find($id);
        // dd($question->toArray());
        $categories = Category::all();
        return view('questionmodule::questions.edit', compact('question', 'categories'));
    }

    public function updateQuestion(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:questions,id',
            'category_id' => 'required|exists:categories,id',
            'question_text' => 'required|string',
            'answer' => 'required|string',
            'questions.*.options' => 'required_if:questions.*.type,mcq|array',
            'options.A' => 'required_if:type,mcq|string',
            'options.B' => 'required_if:type,mcq|string',
            'options.C' => 'required_if:type,mcq|string',
            'options.D' => 'required_if:type,mcq|string',
        ], [
            'id.required' => 'معرف السؤال مطلوب.',
            'id.exists' => 'السؤال المحدد غير موجود.',
            'category_id.required' => 'الفئة مطلوبة.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',
            'question_text.required' => 'نص السؤال مطلوب.',
            'answer.required' => 'الإجابة الصحيحة مطلوبة.',
            'options.A.required_if' => 'الخيار A مطلوب لأن نوع السؤال هو اختيار من متعدد.',
            'options.B.required_if' => 'الخيار B مطلوب لأن نوع السؤال هو اختيار من متعدد.',
            'options.C.required_if' => 'الخيار C مطلوب لأن نوع السؤال هو اختيار من متعدد.',
            'options.D.required_if' => 'الخيار D مطلوب لأن نوع السؤال هو اختيار من متعدد.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $this->questionService->update([
            'id' => $request->id,
            'category_id' => $request->category_id,
            'question_text' => $request->question_text,
            'type' => $request['type'],
            'options' => $request->type === 'mcq' ? ($request->options ?? null) : null,
            'answer' => $request->answer,
        ]);

        return redirect()->route(Auth::getDefaultDriver() . '.questions.index')
            ->with('success', 'تم تحديث السؤال بنجاح.');
    }

    public function deleteQuestion($id) {
        $this->questionService->delete($id);

        return redirect()->route(Auth::getDefaultDriver() . '.questions.index')
            ->with('success', 'تم حذف السؤال بنجاح.');
    }
}
