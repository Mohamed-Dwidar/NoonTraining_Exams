<?php

namespace Modules\StudentModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\StudentModule\Services\StudentService;

class StudentController extends Controller
{
    private $students;

    public function __construct(StudentService $studentService)
    {
        $this->students = $studentService;
    }


    public function index()
    {
        $students = $this->students->findAll();
        return view('admin.students.index', compact('students'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('admin.students.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|unique:students,email',
            'phone'        => 'required|string|max:20|unique:students,phone',
            'national_id'  => 'nullable|digits:14|unique:students,national_id',
            'birth_date'   => 'nullable|date',
            'gender'       => 'nullable|in:male,female',
            'category_id'  => 'nullable|exists:categories,id',
            'student_code' => 'nullable|string|unique:students,student_code',
            'password'     => 'required|string|min:6',
        ]);

        $this->students->create($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.students.index')
            ->with('success', 'تم إضافة الطالب بنجاح');
    }


    public function edit($id)
    {
        $student = $this->students->find($id);
        $categories = Category::all();

        return view('admin.students.edit', compact('student', 'categories'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'id'           => 'required|exists:students,id',
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|unique:students,email,' . $request->id,
            'phone'        => 'required|string|max:20|unique:students,phone,' . $request->id,
            'national_id'  => 'nullable|digits:14|unique:students,national_id,' . $request->id,
            'birth_date'   => 'nullable|date',
            'gender'       => 'nullable|in:male,female',
            'category_id'  => 'nullable|exists:categories,id',
            'student_code' => 'nullable|string|unique:students,student_code,' . $request->id,
            'password'     => 'nullable|string|min:6',
        ]);

        $this->students->update($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.students.index')
            ->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }


    public function delete($id)
    {
        $this->students->delete($id);

        return redirect()->route(Auth::getDefaultDriver() . '.students.index')
            ->with('success', 'تم حذف الطالب بنجاح');
    }
}
