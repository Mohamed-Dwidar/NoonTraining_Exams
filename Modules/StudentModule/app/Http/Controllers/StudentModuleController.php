<?php

namespace Modules\StudentModule\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ExamModule\app\Http\Models\Exam;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\StudentModule\app\Http\Models\Student;
use Modules\StudentModule\Services\StudentService;

class StudentModuleController extends Controller
{
    private $students;

    public function __construct(StudentService $studentService)
    {
        $this->students = $studentService;
    }

    /**
     * Display the intro page for students
     */
    public function intro()
    {
        return view('studentmodule::intro');
    }

    public function dashboard()
    {
        $exams = Auth::guard('student')->user()->exams;
        return view('studentmodule::auth.dashboard' , compact('exams'));
    }

    public function index()
    {
        $students = $this->students->findAll();
        return view('studentmodule::students.index', compact('students'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('studentmodule::students.create', compact('categories'));
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

        return view('studentmodule::students.edit', compact('student', 'categories'));
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

    public function show($id)
    {
        $student = Student::with('category')->findOrFail($id);
        return view('studentmodule::students.show', compact('student'));
    }

    public function assignExam(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id'    => 'required|array',
            'exam_id.*'  => 'exists:exams,id'
        ]);

        $this->students->assignExamToStudent(
            $request->student_id,
            $request->exam_id
        );

        return redirect()->back()->with('success', 'تم ربط الامتحان بالطالب بنجاح');
    }

     public function showExams($id)
    {
        $student = Student::with('category')->findOrFail($id);
        $exams = Exam::class::all();
        return view('studentmodule::exam.assignExam', compact('student', 'exams'));
    }
}
