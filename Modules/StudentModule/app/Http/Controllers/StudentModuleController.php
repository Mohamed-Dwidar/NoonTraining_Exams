<?php

namespace Modules\StudentModule\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\StudentModule\app\Http\Models\Student;
use Modules\StudentModule\Services\StudentService;
use Modules\ExamModule\Services\ExamService;
class StudentModuleController extends Controller {
    private $studentService;
    private $examService;

    public function __construct(StudentService $studentService,ExamService $examService) {
        $this->studentService = $studentService;
        $this->examService = $examService;
    }

    /**
     * Display the intro page for students
     */
    public function intro() {
        return view('studentmodule::intro');
    }

    public function dashboard() {
        $exams = Auth::guard('student')->user()->exams;
        return view('studentmodule::auth.dashboard', compact('exams'));
    }

    public function index() {
        $students = $this->studentService->findAll();
        return view('studentmodule::students.index', compact('students'));
    }

    public function create() {
        $categories = Category::all();
        return view('studentmodule::students.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|unique:students,email',
            'phone'        => 'required|string|max:20|unique:students,phone',
            'national_id'  => 'nullable|digits:10|unique:students,national_id',
            'gender'       => 'nullable|in:male,female',
            'password'     => 'required|string|min:6',
        ], [
            'name.required' => 'حقل الاسم مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'حقل الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'national_id.digits' => 'رقم الهوية أو الإقامة لا يتعدي ١٠ ارقام وباللغه الانجليزية',
            'national_id.unique' => 'رقم الهوية مستخدم بالفعل في هذا الفرع',
            'gender.in' => 'الجنس يجب أن يكون   "ذكر" أو "أنثي"',
            'password.required' => 'حقل كلمة المرور مطلوب',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل ٦ أحرف',
        ]);
        $this->studentService->create($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.students.index')
            ->with('success', 'تم إضافة الطالب بنجاح');
    }

    public function edit($id) {
        $student = $this->studentService->find($id);
        $categories = Category::all();

        return view('studentmodule::students.edit', compact('student', 'categories'));
    }

    public function update(Request $request) {
        $request->validate([
            'id'           => 'required|exists:students,id',
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|unique:students,email,' . $request->id,
            'phone'        => 'required|string|max:20|unique:students,phone,' . $request->id,
            'national_id'  => 'nullable|digits:10|unique:students,national_id,' . $request->id,
            'gender'       => 'nullable|in:male,female',
            'password'     => 'nullable|string|min:6',
        ], [
            'name.required' => 'حقل الاسم مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'حقل الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'national_id.digits' => 'رقم الهوية أو الإقامة لا يتعدي ١٠ ارقام وباللغه الانجليزية',
            'national_id.unique' => 'رقم الهوية مستخدم بالفعل',
            'gender.in' => 'الجنس يجب أن يكون   "ذكر" أو "أنثي"',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل ٦ أحرف',
        ]);

        $this->studentService->update($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.students.index')
            ->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    public function delete($id) {
        $this->studentService->delete($id);

        return redirect()->route(Auth::getDefaultDriver() . '.students.index')
            ->with('success', 'تم حذف الطالب بنجاح');
    }

    public function show($id) {
        $student = $this->studentService->find($id);
        return view('studentmodule::students.show', compact('student'));
    }

    public function showExams($id) {
        $student = $this->studentService->find($id);
        // dd($student->exams->toArray());
        $exams = $this->examService->findAll();
        return view('studentmodule::exam.showExam', compact('student', 'exams'));
    }

    public function assignExam(Request $request) {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id'    => 'required|array',
            'exam_id.*'  => 'exists:exams,id'
        ]);

        $this->studentService->assignExamToStudent(
            $request->student_id,
            $request->exam_id
        );

        return redirect()->back()->with('success', 'تم ربط الأختبار بالطالب بنجاح');
    }

    public function assignSingleExam(Request $request) {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id'    => 'required|exists:exams,id'
        ]);

        $student = $this->studentService->find($request->student_id);

        // Get currently assigned exam IDs
        $currentExamIds = $student->exams->pluck('id')->toArray();

        // Add the new exam ID (allow duplicates - user can take the exam multiple times)
        $currentExamIds[] = $request->exam_id;

        $result = $this->studentService->assignOneExamToStudent(
            $request->student_id,
            $request->exam_id
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تعيين الاختبار بنجاح',
            'student_exam_id' => $result['student_exam_id']
        ]);
    }

    public function unassignExam(Request $request) {
        $request->validate([
            'student_exam_id' => 'required|exists:student_exam,id'
        ]);

        $studentExam = $this->studentService->getStudentExam($request->student_exam_id);

        if (!$studentExam) {
            return response()->json([
                'success' => false,
                'message' => 'الاختبار غير موجود'
            ], 404);
        }

        // Only allow unassignment if exam hasn't been taken yet
        if ($studentExam->score !== null) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء تعيين اختبار تم إجراؤه بالفعل'
            ], 400);
        }

        $studentExam->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء تعيين الاختبار بنجاح'
        ]);
    }
}
