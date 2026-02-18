<?php

namespace Modules\QuestionModule\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\QuestionModule\Services\CategoryService;

class CategoryController extends Controller {
    private $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index() {
        $categories = $this->categoryService->findAll();
        return view('questionmodule::categories.index', compact('categories'));
    }

    public function create() {
        return view('questionmodule::categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'حقل الاسم مطلوب',
            'name.string' => 'حقل الاسم يجب أن يكون نصًا',
            'description.string' => 'حقل الوصف يجب أن يكون نصًا',
        ]);

        $this->categoryService->create($request);

        return redirect()->route(Auth::getDefaultDriver() . '.categories.index')->with('success', 'تم إنشاء التصنيف بنجاح');
    }

    public function edit($id) {
        $category = $this->categoryService->find($id);
        return view('questionmodule::categories.edit', compact('category'));
    }

    public function update(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'حقل الاسم مطلوب',
            'name.string' => 'حقل الاسم يجب أن يكون نصًا',
            'description.string' => 'حقل الوصف يجب أن يكون نصًا',
        ]);

        $this->categoryService->update($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.categories.index')->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(Category $category) {
        $this->categoryService->delete($category->id);
        return redirect()->route(Auth::getDefaultDriver() . '.categories.index')->with('success', 'تم حذف التصنيف');
    }
}
