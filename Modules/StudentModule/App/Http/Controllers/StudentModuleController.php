<?php

namespace Modules\StudentModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\QuestionModule\app\Http\Models\Category;
use Modules\StudentModule\app\Http\Models\Student;
use Modules\StudentModule\Services\StudentService;
use Modules\ExamModule\Services\ExamService;
use Modules\StudentModule\Services\StudentExamService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\StudentModule\App\Imports\StudentImport;
use Modules\TypingTestModule\Services\TypingTestService;
use Modules\TypingTestModule\Services\TypingTestSettingService;

class StudentModuleController extends Controller {
    private $studentService;
    private $examService;
    private $studentExamService;
    private $typingTestService;
    private $typingSettingService;

    public function __construct(StudentService $studentService, ExamService $examService, StudentExamService $studentExamService, TypingTestService $typingTestService, TypingTestSettingService $typingSettingService) {
        $this->studentService = $studentService;
        $this->examService = $examService;
        $this->studentExamService = $studentExamService;
        $this->typingTestService = $typingTestService;
        $this->typingSettingService = $typingSettingService;
    }

    /**
     * Display the intro page for students
     */
    public function intro() {
        return view('studentmodule::intro');
    }

    public function dashboard() {
        $student = Auth::guard('student')->user();

        // Attempts whose time ran out while the student was away are graded first
        foreach ($student->studentExams as $studentExam) {
            $this->studentExamService->completeIfExpired($studentExam);
        }
        $this->typingTestService->completeExpiredForStudent($student->id);

        $exams = $student->studentExams()->with('exam')->get();
        // Standalone typing tests are listed with the other exams (linked ones belong to their course row)
        $typingAttempts = $this->typingTestService->standaloneForStudent($student->id);
        // Typing part of linked courses, keyed by student_exam_id (course counts as started once it's opened)
        $linkedTyping = $this->typingTestService->linkedForStudent($student->id)->keyBy('student_exam_id');
        $typingSettings = $this->typingSettingService->get();
        return view('studentmodule::auth.dashboard', compact('exams', 'typingAttempts', 'typingSettings', 'linkedTyping'));
    }

    public function index(Request $request) {
        $students = $this->studentService->filter($request->all())->paginate(25);
        return view('studentmodule::students.index', compact('students'));
    }

    public function create() {
        $categories = Category::all();
        return view('studentmodule::students.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email',
            'phone'        => 'required|string|max:20|unique:students,phone',
            'national_id'  => 'required|digits:10|unique:students,national_id',
            'gender'       => 'nullable|in:male,female',
            'password'     => 'required|string|min:6',
        ], [
            'name.required' => 'حقل الاسم مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'حقل الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'national_id.required' => 'رقم الهوية مطلوب',
            'national_id.digits' => 'رقم الهوية يجب ان يكون ١٠ رقم',
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
            'email'        => 'nullable|email',
            'phone'        => 'required|string|max:20|unique:students,phone,' . $request->id,
            'national_id'  => 'required|digits:10|unique:students,national_id,' . $request->id,
            'gender'       => 'nullable|in:male,female',
            'password'     => 'nullable|string|min:6',
        ], [
            'name.required' => 'حقل الاسم مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'حقل الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'national_id.required' => 'رقم الهوية مطلوب',
            'national_id.digits' => 'رقم الهوية يجب ان يكون ١٠ رقم',
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

    public function importStudents(Request $request) {
        Excel::import(new StudentImport($this->studentService), $request->file('file'));
        return back()->with('success', 'تم الاستيراد بنجاح!');
    }

    public function showExams($id) {
        $student = $this->studentService->find($id);
        // dd($student->exams->toArray());
        $exams = $this->examService->findAll();
        $typingAttempts = $this->typingTestService->standaloneForStudent($student->id);
        return view('studentmodule::exam.showExam', compact('student', 'exams', 'typingAttempts'));
    }

    public function assignExam(Request $request) {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id'    => 'required|array',
            'exam_id.*'  => 'exists:exams,id'
        ]);

        $this->studentExamService->assignExamToStudent(
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

        $result = $this->studentExamService->assignOneExamToStudent(
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
            'student_exam_id' => 'required|exists:student_exams,id'
        ]);

        $studentExam = $this->studentExamService->getStudentExam($request->student_exam_id);

        if (!$studentExam) {
            return response()->json([
                'success' => false,
                'message' => 'الاختبار غير موجود'
            ], 404);
        }

        // Only allow unassignment if exam hasn't been taken yet (including its linked typing test)
        if ($studentExam->status !== 'not_started' || $this->typingTestService->hasStartedForStudentExam($studentExam->id)) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء تعيين اختبار تم إجراؤه بالفعل'
            ], 400);
        }

        $this->studentExamService->unassignExam($request->student_exam_id);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء تعيين الاختبار بنجاح'
        ]);
    }
}
