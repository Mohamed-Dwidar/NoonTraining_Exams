<?php

namespace Modules\CourseModule\Repository;

use Modules\CourseModule\app\Http\Models\CourseRegPayment;
use Prettus\Repository\Eloquent\BaseRepository;
 

class CourseRegPaymentRepository extends BaseRepository
{
    function model()
    {
        return CourseRegPayment::class;
    }

    function getTotalToReg($reg_id){
        return CourseRegPayment::where('course_reg_id',$reg_id)->sum('amount') ;
    }

    function findAllWithActions($arr_actions = [])
    {
        $query = CourseRegPayment::select('course_reg_payments.*');
        if (!empty($arr_actions)) {
            ///Filter///
            if (key_exists('filter', $arr_actions)) {
                foreach ($arr_actions['filter'] as $col => $filter_by) {
                    if (is_array($filter_by)) {
                        $query->WhereIN($col, $filter_by);
                    } else {
                        $query->Where($col, $filter_by);
                    }
                }
            }
            //////////

            //Search By///
            // if (key_exists('search_by', $arr_actions)) {
            //     $where_arr = [];
            //     foreach ($arr_actions['search_by'] as $col => $search_by) {
            //         $where_arr[] = 'UPPER(' . $col . ') LIKE "%' . strtoupper($search_by) . '%"';


            //         //$query->whereRaw($col . ' = ' . $filter_by);


            //         //$query->orWhereRaw('UPPER(' . $col . ') LIKE "%' . strtoupper($search_by) . '%"');
            //     }

            //     $query->whereRaw('(' . implode(' OR ', $where_arr) . ')');
            // }
            ////////////

            //Fillter By Date Range///
            if (key_exists('filter_date_range', $arr_actions)) {
                if (key_exists('from', $arr_actions['filter_date_range']) && key_exists('to', $arr_actions['filter_date_range'])) {
                    $query->Where('course_reg_payments.paid_at', '>=', $arr_actions['filter_date_range']['from']);
                    $query->Where('course_reg_payments.paid_at', '<=', $arr_actions['filter_date_range']['to']);
                }
            }
            ////////////

            //Order By///
            if (key_exists('order_by', $arr_actions)) {
                foreach ($arr_actions['order_by'] as $col => $order_by) {
                    $query->orderBy($col, $order_by);
                }
            }
            ////////////


            $query->join('students', 'course_reg_payments.student_id', '=', 'students.id');
            $query->join('course_regs', 'course_reg_payments.course_reg_id', '=', 'course_regs.id');
            $query->join('course', 'course_regs.course_id', '=', 'courses.id');
        } else {
            $query->orderBy('course_reg_payments.paid_at', 'desc');
        }

        return $query->paginate(20);
    }
}
