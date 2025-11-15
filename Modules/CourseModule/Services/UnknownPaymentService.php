<?php

namespace Modules\CourseModule\Services;

use Modules\CourseModule\Repository\CourseRegPaymentRepository;
use Modules\CourseModule\Repository\CourseRegRepository;
use Modules\CourseModule\Repository\UnknownPaymentRepository;

class UnknownPaymentService
{
    private $unknownPaymentRepository;
    private $courseRegPaymentRepository;
    private $courseRegRepository;

    public function __construct(UnknownPaymentRepository $unknownPaymentRepository, CourseRegPaymentRepository $courseRegPaymentRepository, CourseRegRepository $courseRegRepository)
    {
        $this->unknownPaymentRepository = $unknownPaymentRepository;
        $this->courseRegPaymentRepository = $courseRegPaymentRepository;
        $this->courseRegRepository = $courseRegRepository;
    }

    public function create($data)
    {
        //$arr_date  = explode('-', $data['paid_at']);
        $course_data = [
            'transferor_name' => $data->transferor_name,
            'amount' => $data->amount,
            'paid_at' => $data->paid_at, //$arr_date[2] . '-' . $arr_date[1] . '-' . $arr_date[0],
            'pay_method' => $data->pay_method
        ];
        return $this->unknownPaymentRepository->create($course_data);
    }

    public function update($data)
    {
        //$arr_date  = explode('-', $data['paid_at']);
        
        $course_data = [
            'transferor_name' => $data->transferor_name,
            'amount' => $data->amount,
            'paid_at' => $data->paid_at, //$arr_date[2] . '-' . $arr_date[1] . '-' . $arr_date[0],
            'pay_method' => $data->pay_method
        ];

        //dd($course_data);
        return $this->unknownPaymentRepository->update($course_data, $data->id);
    }

    public function assignPayment($data)
    {
        $unknown_payment = $this->unknownPaymentRepository->find($data['id']);
        // $reg = $this->courseRegRepository->findWhere(['id' => $data['reg_id'], 'student_id' => $data['student']]);
        $reg = $this->courseRegRepository->find($data['reg_id']);
        //dd($data, $reg->toArray());
        //$arr_date  = explode('-', $data['paid_at']);


        //////Create reg payment/////
        $reg_data = [
            'course_reg_id' => $reg->id,
            'student_id' => $reg->student_id,
            'pay_method' => $unknown_payment->pay_method,
            'amount' => $unknown_payment->amount,
            // 'paid_at' => $arr_date[2] . '-' . $arr_date[1] . '-' . $arr_date[0],
            'paid_at' => $unknown_payment->paid_at,
        ];
        $course_reg_payment = $this->courseRegPaymentRepository->create($reg_data);
        //////////////////////

        //////Update is_paid/////
        $reg = $this->courseRegRepository->find($data['reg_id']);
        $reg->update(
            [
                'is_course_paid' => ($reg->coursePaidAmount >= $reg->price) ? 1 : 0,
                'is_exam_paid' => ($reg->examPaidAmount >= $reg->course->exam_fees) ? 1 : 0,
            ]
        );
        //////////////////////


        // //////Update unknown_payment/////
        $unknown_payment_data = [
            'course_reg_id' => $reg->id,
            'student_id' => $reg->student_id,
        ];
        $unknown_payment->update($unknown_payment_data);

        return $course_reg_payment;
    }

    public function findAll()
    {
        return $this->unknownPaymentRepository->get();
    }

    public function findOne($id)
    {
        return $this->unknownPaymentRepository->find($id);
    }

    public function findAllWithFilter()
    {
        return $this->unknownPaymentRepository->filter();
    }


    public function deleteOne($id)
    {
        $old_data = $this->unknownPaymentRepository->find($id);
        if (!empty($old_data)) {
            $this->unknownPaymentRepository->delete($id);
        }
    }
}
