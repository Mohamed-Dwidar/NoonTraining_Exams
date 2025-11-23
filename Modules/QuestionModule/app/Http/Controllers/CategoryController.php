<?php

namespace Modules\QuestionModule\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\QuestionModule\app\Http\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('questionmodule::categories.index', compact('categories'));
    }

    public function create()
    {
        return view('questionmodule::categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only('name', 'description'));

        return redirect()->route(Auth::getDefaultDriver() .'.categories.index')->with('success', 'تم إنشاء التصنيف بنجاح');
    }

    public function edit(Category $category)
    {
        return view('questionmodule::categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only('name', 'description'));

        return redirect()->route(Auth::getDefaultDriver() .'.categories.index')->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route(Auth::getDefaultDriver() .'.categories.index')->with('success', 'تم حذف التصنيف');
    }
}
