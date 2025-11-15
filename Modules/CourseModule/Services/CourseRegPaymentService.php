<?php

namespace Modules\CourseModule\Services;

use Modules\CourseModule\Repository\CourseRegPaymentRepository;
use Modules\CourseModule\Repository\CourseRegRepository;

class CourseRegPaymentService
{
    private $courseRegPaymentRepository;
    private $courseRegRepository;

    public function __construct(CourseRegPaymentRepository $courseRegPaymentRepository, CourseRegRepository $courseRegRepository)
    {
        $this->courseRegPaymentRepository = $courseRegPaymentRepository;
        $this->courseRegRepository = $courseRegRepository;
    }

    public function findAll($arr_actions = [])
    {
        $actions = [];
        ///Sort///
        $arr_sorts = ['date_az', 'date_za', 'sname_az', 'sname_za', 'amount_az', 'amount_za'];
        if (!empty($arr_actions['sort'])) {
            if (in_array($arr_actions['sort'], $arr_sorts)) {
                if ($arr_actions['sort'] == 'date_az') {
                    $sort_by['course_reg_payments.paid_at'] = 'asc';
                } elseif ($arr_actions['sort'] == 'date_za') {
                    $sort_by['course_reg_payments.paid_at'] = 'desc';
                } elseif ($arr_actions['sort'] == 'sname_az') {
                    $sort_by['students.name'] = 'asc';
                } elseif ($arr_actions['sort'] == 'sname_za') {
                    $sort_by['students.name'] = 'desc';
                } elseif ($arr_actions['sort'] == 'amount_az') {
                    $sort_by['course_reg_payments.amount'] = 'asc';
                } elseif ($arr_actions['sort'] == 'amount_za') {
                    $sort_by['course_reg_payments.amount'] = 'desc';
                }

                $actions['order_by'] = $sort_by;
            }
        }
        /////////

        ///Search///
        // if (!empty($arr_actions['search'])) {
        //     $actions['search_by']['users.name'] = $arr_actions['search'];
        //     $actions['search_by']['users.email'] = $arr_actions['search'];
        //     $actions['search_by']['students.id_nu'] = $arr_actions['search'];
        //     $actions['search_by']['students.phone'] = $arr_actions['search'];
        //     $actions['search_by']['students.mobile'] = $arr_actions['search'];
        // }
        ///////////

        ///Filter Date Rage////
        if (!empty($arr_actions['filter_date_range_from']) || !empty($arr_actions['filter_date_range_to'])) {
            $from_arr = explode('-', $arr_actions['filter_date_range_from']);
            $to_arr = explode('-', $arr_actions['filter_date_range_to']);

            $actions['filter_date_range']['from'] = $from_arr[2] . '-' . $from_arr[1] . '-' .  $from_arr[0];
            $actions['filter_date_range']['to'] = $to_arr[2] . '-' . $to_arr[1] . '-' .  $to_arr[0];
        }
        ///////////////

        ///Filter Course///
        if (!empty($arr_actions['filter_crs'])) {
            $actions['filter']['course_dates.id'] = $arr_actions['filter_crs'];
        }
        ///////////////

        return $this->courseRegPaymentRepository->findAllWithActions($actions);
    }

    public function findOne($id)
    {
        return $this->courseRegPaymentRepository->find($id);
    }

    public function deleteOne($id)
    {
        $old_data = $this->courseRegPaymentRepository->find($id);
        if (!empty($old_data)) {
            $this->courseRegPaymentRepository->delete($id);
        }
    }

    public function deleteToReg($id)
    {
        $this->courseRegPaymentRepository->deleteWhere(['course_reg_id' => $id]);
    }

    public function takePayAction($data)
    {
        $reg = $this->courseRegRepository->find($data['id']);
        //$arr_date  = explode('-', $data['paid_at']);
        $date_data = [
            'course_reg_id' => $data['id'],
            'student_id' => $reg->student_id,
            'pay_type' => $data['pay_type'],
            'amount' => $data['amount'],
            'paid_at' => $data['paid_at'], //$arr_date[2] . '-' . $arr_date[1] . '-' . $arr_date[0],
            'pay_method' => $data['pay_method']
        ];
        $this->courseRegPaymentRepository->create($date_data);

        $reg = $this->courseRegRepository->find($data['id']);
        $reg->update(
            [
                'is_course_paid' => ($reg->coursePaidAmount >= $reg->price) ? 1 : 0,
                'is_exam_paid' => ($reg->examPaidAmount >= $reg->exam_fees) ? 1 : 0,
            ]
        );
        return $reg;
    }
}
