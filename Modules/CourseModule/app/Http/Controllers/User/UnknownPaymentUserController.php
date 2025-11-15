<?php

namespace Modules\CourseModule\app\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\CourseModule\app\Http\Models\Course;
use Modules\CourseModule\app\Http\Models\CourseReg;
use Modules\CourseModule\Services\CourseRegPaymentService;
use Modules\CourseModule\Services\CourseRegService;
use Modules\CourseModule\Services\CourseService;
use Modules\CourseModule\Services\UnknownPaymentService;

class UnknownPaymentUserController extends Controller
{
    private $unknownPaymentService;
    private $courseRegPaymentService;
    private $courseRegService;
    private $courseService;

    public function __construct(UnknownPaymentService $unknownPaymentService, CourseRegService $courseRegService, CourseRegPaymentService $courseRegPaymentService, CourseService $courseService)
    {
        $this->unknownPaymentService = $unknownPaymentService;
        $this->courseRegPaymentService = $courseRegPaymentService;
        $this->courseRegService = $courseRegService;
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $unknown_payments = $this->unknownPaymentService->findAllWithFilter();
        return view('coursemodule::admin.unknown_payment.index', compact('unknown_payments'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('coursemodule::admin.unknown_payment.create');
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
                'transferor_name'  => 'required',
                'amount' => 'required|numeric',
                'paid_at' => 'required',
                'pay_method' => 'required'
            ],
            [
                'transferor_name.required' => 'يجب ادخال اسم المحول',
                'amount.required' => 'يجب ادخال قيمة الدفعة',
                'amount.numeric' => 'يجب ادخال قيمة الدفعة بطريقة صحيحة و ان تكون الارقام باللغة الانجليزية',
                'paid_at.required' => 'يجب ادخال تاريخ الدفع',
                'pay_method.required' => 'يجب اختيار طرية الدفع'
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $this->unknownPaymentService->create($request);

        return redirect()->route(Auth::getDefaultDriver() . '.unknown_payment.list')
            ->with('success', 'تم الاضافه بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $unknown_payment = $this->unknownPaymentService->findOne($id);
        return view('coursemodule::admin.unknown_payment.edit', compact('unknown_payment'));
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
                'id' => 'required',
                'transferor_name'  => 'required',
                'pay_method' => 'required',
                'amount' => 'required|numeric',
                'paid_at' => 'required',
                'pay_method' => 'required'
            ],
            [
                'pay_method.required' => 'يجب اختيار نوع الدفع',
                'transferor_name.required' => 'يجب ادخال اسم المحول',
                'amount.required' => 'يجب ادخال قيمة الدفعة',
                'amount.numeric' => 'يجب ادخال قيمة الدفعة بطريقة صحيحة و ان تكون الارقام باللغة الانجليزية',
                'paid_at.required' => 'يجب ادخال تاريخ الدفع',
                'pay_method.required' => 'يجب اختيار طرية الدفع'
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $this->unknownPaymentService->update($request);

        return redirect()->route(Auth::getDefaultDriver() . '.unknown_payment.list')
            ->with('success', 'تم التعديل بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->unknownPaymentService->deleteOne($id);
        return redirect()->route(Auth::getDefaultDriver() . '.unknown_payment.list')
            ->with('success', 'حذف الدورة بنجاح.');
    }

    public function assignPayment(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'reg_id' => 'required'
            ],
            [
                'reg_id.required' => 'يجب اختيار الحجز'
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $this->unknownPaymentService->assignPayment($request->all());

        $this->courseRegService->checkAndUpdateRegStatus($request->reg_id);

        return redirect()->route(Auth::getDefaultDriver() . '.unknown_payment.list')->with('success', 'تمت العملية بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function assignToReg($id)
    {
        $unknown_payment = $this->unknownPaymentService->findOne($id);
        $courses = $this->courseService->findAll()->where('branch_id', Auth::guard('user')->user()->branch_id);
        return view('coursemodule::admin.unknown_payment.assignToReg', compact('unknown_payment', 'courses'));
    }

    public function getStudentsForCourse($courseId)
    {
        $course_regs = $this->courseRegService->findWhere(['course_id' => $courseId]);
        $studentsWithRegId = $course_regs->map(function ($course_reg) {
            return [
                'student_id' => $course_reg->student->id,
                'student_name' => $course_reg->student->name,
                'reg_id' => $course_reg->id
            ];
        });

        return response()->json($studentsWithRegId);
    }
}
