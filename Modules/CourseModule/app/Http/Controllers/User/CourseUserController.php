<?php

namespace Modules\CourseModule\app\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\BranchModule\Services\BranchService;
use Modules\CourseModule\Services\CourseRegPaymentService;
use Modules\CourseModule\Services\CourseRegService;
use Modules\CourseModule\Services\CourseRegStatusService;
use Modules\CourseModule\Services\CourseService;
use Modules\LogModule\Services\LogService;

class CourseUserController extends Controller
{
    private $courseService;
    private $branchService;
    private $courseRegService;
    private $courseRegPaymentService;
    private $courseRegStatusService;
    private $logService;

    public function __construct(CourseService $courseService, BranchService $branchService, CourseRegService $courseRegService, CourseRegPaymentService $courseRegPaymentService, CourseRegStatusService $courseRegStatusService, LogService $logService)
    {
        $this->courseService = $courseService;
        $this->branchService = $branchService;
        $this->courseRegService = $courseRegService;
        $this->courseRegPaymentService = $courseRegPaymentService;
        $this->courseRegStatusService = $courseRegStatusService;
        $this->logService = $logService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $branches = $this->branchService->findAll();
        $request['branch'] = Auth::guard('user')->user()->branch_id;
        $courses = $this->courseService->findAllWithFilter($request->all())->paginate(20);
        return view('coursemodule::admin.index', compact('courses', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $branches = $this->branchService->findAll();
        return view('coursemodule::admin.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'branch_id' => 'required',
                'name' => 'required',
                'group_nu' => 'required',
                'course_org_nu' => 'required',
                'start_at' => 'required|date',
                'end_at' => 'required|date',
                'price' => 'required|numeric|between:0,9999999999.99',
                'exam_fees' => 'required|numeric|between:0,9999999999.99',
            ],
            [
                'name.required' => 'يجب ادخال اسم الدورة',
                'group_nu.required' => 'يجب ادخال رقم الجروب',
                'course_org_nu.required' => 'يجب ادخال رقم الدورة في المؤسسة العامة',
                'start_at.required' => 'يجب تحديد تاريخ البدء',
                'end_at.required' => 'يجب تحديد تاريخ الانتهاء',
                'start_at.date' => 'يجب ادخال التاريخ بشكل صحيح',
                'end_at.date' => 'يجب ادخال التاريخ بشكل صحيح',
                'price.required' => 'يجب ادخال سعر الدورة',
                'price.numeric' => 'يجب ادخال سعر الدورة بطريقة صحيحة',
                'price.between' => 'يجب ادخال سعر الدورة بطريقة صحيحة',
                'exam_fees.required' => 'يجب ادخال رسوم الاختبار',
                'exam_fees.numeric' => 'يجب ادخال رسوم الاختبار بطريقة صحيحة',
                'exam_fees.between' => 'يجب ادخال رسوم الاختبار بطريقة صحيحة',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $request['branch_id'] = Auth::guard('user')->user()->branch_id;
        $this->courseService->create($request);

        //Add Log
        $action = 'إضافة دورة';
        $description = 'تم إضافة دورة جديدة : ' . $request->name;
        $this->logService->recordLog($action, $description, url()->current());
        //
        return redirect()->route(Auth::getDefaultDriver() . '.courses')
            ->with('success', 'تم الاضافه بنجاح.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Request $request)
    {
        $course_id = $request->id;
        $course = $this->courseService->findOne($course_id);

        if (Auth::guard('user')->user()->branch_id != $course->branch_id)
            return back()
                ->withErrors('الدورة غير موجود في قائمة الدورات');


        // dd($request->all());
        $request['course_id'] = $request->id;
        $courses_regs = $this->courseRegService->findAllWithFilter($request->all())->get();

        $statuses = $this->courseRegStatusService->findAll();

        if ($request->export == 'yes') {
            return Excel::download(new CourseExport($course, $courses_regs), 'تقرير ' . $course->name . '.xlsx');
        }
        // dd($courses_regs[0]->student->payments);

        return view('coursemodule::admin.show', compact('courses_regs', 'course', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $course = $this->courseService->findOne($id);
        // dd($course->toArray(),Auth::guard('user')->user()->branch_id , $course->branch_id);
        if (Auth::guard('user')->user()->branch_id != $course->branch_id)
            return back()
                ->withErrors('الدورة غير موجود في قائمة الدورات');

        $branches = $this->branchService->findAll();
        return view('coursemodule::admin.edit', compact('course', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'branch_id' => 'required',
                'name' => 'required',
                'group_nu' => 'required',
                'course_org_nu' => 'required',
                'start_at' => 'required|date',
                'end_at' => 'required|date',
                'price' => 'required|numeric|between:0,9999999999.99',
                'exam_fees' => 'required|numeric|between:0,9999999999.99',
            ],
            [
                // 'branch_id.required' => 'يجب تحديد الفرع',
                'name.required' => 'يجب ادخال اسم الدورة',
                'group_nu.required' => 'يجب ادخال رقم الجروب',
                'course_org_nu.required' => 'يجب ادخال رقم الدورة في المؤسسة العامة',
                'start_at.required' => 'يجب تحديد تاريخ البدء',
                'end_at.required' => 'يجب تحديد تاريخ الانتهاء',
                'start_at.date' => 'يجب ادخال التاريخ بشكل صحيح',
                'end_at.date' => 'يجب ادخال التاريخ بشكل صحيح',
                'price.required' => 'يجب ادخال سعر الدورة',
                'price.numeric' => 'يجب ادخال سعر الدورة بطريقة صحيحة',
                'price.between' => 'يجب ادخال سعر الدورة بطريقة صحيحة',
                'exam_fees.required' => 'يجب ادخال رسوم الاختبار',
                'price.numeric' => 'يجب ادخال رسوم الاختبار بطريقة صحيحة',
                'price.between' => 'يجب ادخال رسوم الاختبار بطريقة صحيحة',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $course = $this->courseService->findOne($request->id);
        if (Auth::guard('user')->user()->branch_id != $course->branch_id)
            return back()
                ->withErrors('الدورة غير موجود في قائمة الدورات');

        $course = $this->courseService->update($request);
        //Add Log
        $action = 'تعديل دورة';
        $description = 'تم تعديل بيانات الدورة ' . $course->name;
        $this->logService->recordLog($action, $description, url()->current());
        //

        return redirect()->route(Auth::getDefaultDriver() . '.courses.show', $request->id)
            ->with('success', 'تم التعديل بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $course = $this->courseService->findOne($id);
        if (Auth::guard('user')->user()->branch_id != $course->branch_id)
            return back()
                ->withErrors('الدورة غير موجود في قائمة الدورات');

        $this->courseService->deleteOne($id);
        //Add Log
        $action = 'حذف دورة';
        $description = 'تم حذف الدورة ' . $course->name;
        $this->logService->recordLog($action, $description, url()->current());
        //
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
        // $request['course_id'] = $request->course_id;
        $request['status_id'] = 1;

        if ($this->courseRegService->getStudentRegDetails($request->student_id, $request->course_id) != null)
            return back()
                ->withErrors('عفوا... تم التسجيل في هذه الدورة من قبل')
                ->withInput();
        // dd($request->all());

        $course_reg = $this->courseRegService->registerStudentToCourse($request);

        //Add Log
        $action = 'تسجيل في دورة';
        $description = 'تم تسجيل طالب في دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name;
        $this->logService->recordLog($action, $description, url()->current());
        //

        return redirect()->route(Auth::getDefaultDriver() . '.students.view', $request->student_id)
            ->with('success', 'تم الاضافه بنجاح ...');
    }
    public function regAction(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required'
            ]
        );
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $courseDate = $this->courseRegService->takeRegAction($request->all());
        //Add Log
        if ($request->action == 'accept') {
            $action = 'قبول';
            $description = 'تم قبول تسجيل طالب في دورة : ' . $courseDate->course->name . ' - ' . $courseDate->student->name;
        } elseif ($request->action == 'reject') {
            $action = 'رفض';
            $description = 'تم رفض تسجيل طالب في دورة : ' . $courseDate->course->name . ' - ' . $courseDate->student->name;
        } elseif ($request->action == 'waiting') {
            $action = 'إنتظار';
            $description = 'تم وضع تسجيل طالب في دورة في حالة إنتظار : ' . $courseDate->course->name . ' - ' . $courseDate->student->name;
        }
        $this->logService->recordLog($action, $description, url()->current());
        //
        return back()
            ->with('success', 'تمت العمليه بنجاح.');
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
            ],
            [
                'pay_method.required' => 'يجب اختيار نوع الدفع',
                'amount.required' => 'يجب ادخال قيمة الدفعة',
                'amount.numeric' => 'يجب ادخال قيمة الدفعة بطريقة صحيحة و ان تكون الارقام باللغة الانجليزية',
                'paid_at.required' => 'يجب ادخال تاريخ الدفع',
                'paid_at.date' => 'يجب ادخال التاريخ بشكل صحيح',
                'pay_type.required' => 'يجب اختيار طرية الدفع'
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $course_reg = $this->courseRegPaymentService->takePayAction($request->all());

        $this->courseRegService->checkAndUpdateRegStatus($request->id);
        //Add Log
        $action = 'دفع';
        $description = 'تم تسجيل دفعة مالية لدورة : ' . $course_reg->course->name . ' - مقابل : ' . $request->pay_type . ' - ' . $course_reg->student->name . ' - المبلغ : ' . $request->amount;
        $this->logService->recordLog($action, $description, url()->current());
        //
        return back()
            ->with('success', 'تمت العمليه بنجاح.');
    }

    public function updatePaymentType(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'reg_id' => 'required',
                'status' => 'required'
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $course_reg = $this->courseRegService->updatePaymentType($request);

        //Add Log
        if ($request->status == 'normal') {
            $status_text = 'افتراضي';
        } elseif ($request->status == 'free') {
            $status_text = 'الدورة مجانا';
        } elseif ($request->status == 'nopaying') {
            $status_text = 'ممتنع عن السداد';
        } elseif ($request->status == 'leave') {
            $status_text = 'مغادر';
        }
        $action = 'تعديل طريقة الدفع';
        $description = 'تم تعديل طريقة الدفع في دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name . ' - الي طريقة الدفع : ' . $status_text;
        $this->logService->recordLog($action, $description, url()->current());
        //
        return back()
            ->with('success', 'تمت العمليه بنجاح.');
    }

    public function updateRegStatus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'reg_id' => 'required',
                'status' => 'required'
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $course_reg = $this->courseRegService->updateRegStatus($request);
        //Add Log
        $action = 'تعديل حالة التسجيل';
        $description = 'تم تعديل حالة التسجيل في دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name . ' - الي حالة : ' . $course_reg->status->status;
        $this->logService->recordLog($action, $description, url()->current());
        //
        return back()
            ->with('success', 'تمت العمليه بنجاح.');
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
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$request->hasFile('receiptfile'))
            return back()
                ->withErrors('يجب اختيار صورة الإيصال')
                ->withInput();

        $this->courseRegReceipteService->takeReceiptAction($request);

        return back()
            ->with('success', 'تمت العمليه بنجاح.');
    }

    public function destroyReg($id)
    {
        $course_reg = $this->courseRegService->findOne($id);
        if (Auth::guard('user')->user()->branch_id != $course_reg->course->branch_id)
            return back()
                ->withErrors('الدورة غير موجود في قائمة الدورات');
        $this->courseRegService->deleteOne($id);
        //Add Log
        $action = 'حذف تسجيل في دورة';
        $description = 'تم حذف تسجيل في دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name;
        $this->logService->recordLog($action, $description, url()->current());
        //
        return back()
            ->with('success', 'تم حذف الموعد بنجاح.');
    }

    public function setCertDelivered($id)
    {
        $course_reg = $this->courseRegService->updateCertDelivered($id, 1);
        $this->courseRegService->checkAndUpdateRegStatus($id);

        //Add Log
        $action = 'استلام شهاده';
        $description = 'تم تسجيل استلام الشهاده في دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name;
        $this->logService->recordLog($action, $description, url()->current());
        //

        return back()
            ->with('success', 'تم استلام الشهاده بنجاح.');
    }

    public function setCertNotDelivered($id)
    {
        $course_reg = $this->courseRegService->updateCertDelivered($id, 0);
        $this->courseRegService->checkAndUpdateRegStatus($id);
        //Add Log
        $action = 'استلام شهاده';
        $description = 'تم تسجيل عدم استلام الشهاده في دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name;
        $this->logService->recordLog($action, $description, url()->current());
        //

        return back()
            ->with('success', 'تم تعديل عدم استلام الشهاده بنجاح.');
    }

    public function ChangePriceForOneStudent(Request $request)
    {
        $course_reg = $this->courseRegService->updatePriceForOneStudent($request);
        $this->courseRegService->checkAndUpdateRegStatus($request->reg_id);

        //Add Log
        $action = 'تعديل سعر';
        $description = 'تم تعديل سعر في دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name . ' - الي سعر :' .  $course_reg->student_price;
        $this->logService->recordLog($action, $description, url()->current());
        //
        return response()->json(
            array(
                'success' => "true",
                'new_price' => $course_reg->price,
                'id' => $course_reg->id,

            )
        );
    }

    public function UpdateDiscountForOneStudent(Request $request)
    {
        $course_reg = $this->courseRegService->updateDiscountForOneStudent($request);
        $this->courseRegService->checkAndUpdateRegStatus($request->reg_id);

        //Add Log
        $action = 'تعديل خصم';
        $description = 'تم تعديل خصم دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name . ' - الي خصم :' .  $course_reg->discount_amount;
        $this->logService->recordLog($action, $description, url()->current());
        //
        return response()->json(
            array(
                'success' => "true",
                'new_price' => $course_reg->price,
                'new_discount' => $course_reg->discount_amount,
                'id' => $course_reg->id,
            )
        );
    }

    public function UpdateRegBy(Request $request)
    {
        $course_reg = $this->courseRegService->updateRegBy($request);

        //Add Log
        $action = 'تعديل تم التسجيل عن طريق';
        $description = 'تم تعديل تم التسجيل عن طريق في دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name . ' - الي : ' .  $course_reg->registered_by;
        $this->logService->recordLog($action, $description, url()->current());
        //
        return response()->json(
            array(
                'success' => "true",
                'new_reg_by' => $course_reg->reg_by,
                'id' => $course_reg->id,

            )
        );
    }

    public function ChangeExamPriceForOneStudent(Request $request)
    {
        $course_reg = $this->courseRegService->updateExamPriceForOneStudent($request);
        $this->courseRegService->checkAndUpdateRegStatus($request->reg_id);

        //Add Log
        $action = 'تعديل رسوم اختبار';
        $description = 'تم تعديل رسوم الاختبار في دورة : ' . $course_reg->course->name . ' - ' . $course_reg->student->name . ' - الي رسوم اختبار :' .  $course_reg->exam_fees;
        $this->logService->recordLog($action, $description, url()->current());
        //
        return response()->json(
            array(
                'success' => "true",
                'new_exam_price' => $course_reg->exam_fees,
                'id' => $course_reg->id,

            )
        );
    }
}
