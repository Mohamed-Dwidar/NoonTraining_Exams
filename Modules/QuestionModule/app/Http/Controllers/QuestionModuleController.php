<?php

namespace Modules\QuestionModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\QuestionModule\Services\QuestionService;

class QuestionModuleController extends Controller
{
    private $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    // ================= Questions =================
    public function indexQuestions()
    {
        $questions = $this->questionService->findAll();
        return view('questionmodule::admin.questions.index', compact('questions'));
    }

    public function createQuestion()
    {
        $categories = Category::all();
        return view('questionmodule::admin.questions.create', compact('categories'));
    }

    public function storeQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'question_text' => 'required|string',
            'type' => 'required|in:mcq,true_false',
            'answer' => 'required|string',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->questionService->create([
            'category_id' => $request->category_id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'options' => $request->type === 'mcq' ? ($request->options ?? null) : null,
            'answer' => $request->answer,
        ]);

        return redirect()->route(Auth::getDefaultDriver() . '.question.index')
            ->with('success', 'تم إنشاء السؤال بنجاح.');
    }

    public function editQuestion($id)
    {
        $question = $this->questionService->findOne($id);
        $categories = Category::all();
        return view('questionmodule::admin.questions.edit', compact('question', 'categories'));
    }

    public function updateQuestion(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
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
            'category_id' => $request->category_id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'options' => $request->type === 'mcq' ? ($request->options ?? null) : null,
            'answer' => $request->answer,
        ]);

        return redirect()->route(Auth::getDefaultDriver() . '.question.index')
            ->with('success', 'تم تحديث السؤال بنجاح.');
    }

    public function destroyQuestion($id)
    {
        $this->questionService->delete($id);

        return redirect()->route(Auth::getDefaultDriver() . '.question.index')
            ->with('success', 'تم حذف السؤال بنجاح.');
    }
}
