<?php

namespace Modules\CourseModule\app\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\CourseModule\Services\CourseRegPaymentService;
use Modules\CourseModule\Services\CourseRegService;
use Modules\CourseModule\Services\CourseRegStatusService;
use Modules\CourseModule\Services\CourseService;
use Modules\BranchModule\Services\BranchService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CourseModule\Exports\CourseExport;

class CourseAdminController extends Controller
{
    private $courseService;
    private $branchService;
    private $courseRegService;
    private $courseRegPaymentService;
    private $courseRegStatusService;

    public function __construct(
        CourseService $courseService,
        BranchService $branchService,
        CourseRegService $courseRegService,
        CourseRegPaymentService $courseRegPaymentService,
        CourseRegStatusService $courseRegStatusService
    ) {
        $this->courseService = $courseService;
        $this->branchService = $branchService;
        $this->courseRegService = $courseRegService;
        $this->courseRegPaymentService = $courseRegPaymentService;
        $this->courseRegStatusService = $courseRegStatusService;
    }

    public function index(Request $request)
    {
        if ($request->brnch)
            $request['branch'] = $request->brnch;

        $branches = $this->branchService->findAll();
        $courses = $this->courseService->findAllWithFilter($request->all())->paginate(20);
        return view('coursemodule::admin.index', compact('courses', 'branches'));
    }

    public function create()
    {
        $branches = $this->branchService->findAll();
        return view('coursemodule::admin.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'branch_id' => 'required',
                'name' => 'required',
                'group_nu' => 'required',
                'course_org_nu' => 'required',
                'start_at' => 'required|date',
                'end_at' => 'required|date',
                'price' => 'required|numeric|between:0,9999999999.99',
                'exam_fees' => 'required|numeric|between:0,9999999999.99',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->courseService->create($request);

        return redirect()->route(Auth::getDefaultDriver() . '.courses')
            ->with('success', 'تم الاضافه بنجاح.');
    }

    public function show(Request $request)
    {
        $course_id = $request->id;
        $request['course_id'] = $request->id;
        $courses_regs = $this->courseRegService->findAllWithFilter($request->all())->get();

        $statuses = $this->courseRegStatusService->findAll();
        $course = $this->courseService->findOne($course_id);

        if ($request->export == 'yes') {
            return Excel::download(new CourseExport($course, $courses_regs), 'تقرير ' . $course->name . '.xlsx');
        }

        return view('coursemodule::admin.show', compact('courses_regs', 'course', 'statuses'));
    }

    public function edit($id)
    {
        $branches = $this->branchService->findAll();
        $course = $this->courseService->findOne($id);
        return view('coursemodule::admin.edit', compact('course', 'branches'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'branch_id' => 'required',
                'name' => 'required',
                'group_nu' => 'required',
                'course_org_nu' => 'required',
                'start_at' => 'required|date',
                'end_at' => 'required|date',
                'price' => 'required|numeric|between:0,9999999999.99',
                'exam_fees' => 'required|numeric|between:0,9999999999.99',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->courseService->update($request);

        return redirect()->route(Auth::getDefaultDriver() . '.courses.show', $request->id)
            ->with('success', 'تم التعديل بنجاح.');
    }

    public function destroy($id)
    {
        $this->courseService->deleteOne($id);

        return redirect()->route(Auth::getDefaultDriver() . '.courses')
            ->with('success', 'حذف الدورة بنجاح.');
    }

    public function indexArchive()
    {
        $courses = $this->courseService->findAll();
        return view('coursemodule::admin.archive_index', compact('courses'));
    }

    public function assignStudentToCourse(Request $request)
    {
        $request['status_id'] = 1;

        if ($this->courseRegService->getStudentRegDetails($request->student_id, $request->course_id) != null)
            return back()->withErrors('عفوا... تم التسجيل في هذه الدورة من قبل')->withInput();

        $this->courseRegService->registerStudentToCourse($request);

        return redirect()->route(Auth::getDefaultDriver() . '.students.view', $request->student_id)
            ->with('success', 'تم الاضافه بنجاح ...');
    }

    public function regAction(Request $request)
    {
        $validator = Validator::make($request->all(), ['id' => 'required']);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->courseRegService->takeRegAction($request->all());

        return back()->with('success', 'تمت العمليه بنجاح.');
    }

    public function payAction(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'pay_method' => 'required',
                'amount' => 'required|numeric',
                'paid_at' => 'required|date',
                'pay_type' => 'required'
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->courseRegPaymentService->takePayAction($request->all());
        $this->courseRegService->checkAndUpdateRegStatus($request->id);

        return back()->with('success', 'تمت العمليه بنجاح.');
    }

    public function updatePaymentType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reg_id' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->courseRegService->updatePaymentType($request);

        return back()->with('success', 'تمت العمليه بنجاح.');
    }

    public function updateRegStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reg_id' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->courseRegService->updateRegStatus($request);

        return back()->with('success', 'تمت العمليه بنجاح.');
    }

    public function receiptAction(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'receipttitle' => 'required'
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!$request->hasFile('receiptfile'))
            return back()->withErrors('يجب اختيار صورة الإيصال')->withInput();

        $this->courseRegReceipteService->takeReceiptAction($request);

        return back()->with('success', 'تمت العمليه بنجاح.');
    }

    public function destroyReg($id)
    {
        $course_reg = $this->courseRegService->findOne($id);

        if (Auth::guard('user')->user()->branch_id != $course_reg->course->branch_id)
            return back()->withErrors('الدورة غير موجود في قائمة الدورات');

        $this->courseRegService->deleteOne($id);

        return back()->with('success', 'تم حذف الموعد بنجاح.');
    }

    public function setCertDelivered($id)
    {
        $this->courseRegService->updateCertDelivered($id, 1);
        $this->courseRegService->checkAndUpdateRegStatus($id);

        return back()->with('success', 'تم استلام الشهاده بنجاح.');
    }

    public function setCertNotDelivered($id)
    {
        $this->courseRegService->updateCertDelivered($id, 0);
        $this->courseRegService->checkAndUpdateRegStatus($id);

        return back()->with('success', 'تم تعديل عدم استلام الشهاده بنجاح.');
    }

    public function ChangePriceForOneStudent(Request $request)
    {
        $course_reg = $this->courseRegService->updatePriceForOneStudent($request);
        $this->courseRegService->checkAndUpdateRegStatus($request->reg_id);

        return response()->json([
            'success' => "true",
            'new_price' => $course_reg->student_price,
            'id' => $course_reg->id,
        ]);
    }

    public function UpdateDiscountForOneStudent(Request $request)
    {
        $course_reg = $this->courseRegService->updateDiscountForOneStudent($request);
        $this->courseRegService->checkAndUpdateRegStatus($request->reg_id);

        return response()->json([
            'success' => "true",
            'new_price' => $course_reg->price,
            'new_discount' => $course_reg->discount_amount,
            'id' => $course_reg->id,
        ]);
    }

    public function UpdateRegBy(Request $request)
    {
        $course_reg = $this->courseRegService->updateRegBy($request);

        return response()->json([
            'success' => "true",
            'new_reg_by' => $course_reg->reg_by,
            'id' => $course_reg->id,
        ]);
    }

    public function ChangeExamPriceForOneStudent(Request $request)
    {
        $course_reg = $this->courseRegService->updateExamPriceForOneStudent($request);
        $this->courseRegService->checkAndUpdateRegStatus($request->reg_id);

        return response()->json([
            'success' => "true",
            'new_exam_price' => $course_reg->exam_fees,
            'id' => $course_reg->id,
        ]);
    }
}
