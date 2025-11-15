<?php

namespace Modules\CourseModule\Repository;

use Modules\CourseModule\app\Http\Models\CourseReg;
use Modules\CourseModule\app\Http\Models\CourseRegPayment;
use Modules\CourseModule\app\Http\Models\CourseRegReceipt;
use Prettus\Repository\Eloquent\BaseRepository;


class CourseRegRepository extends BaseRepository
{
    function model()
    {
        return CourseReg::class;
    }

    public function filter(array $request)
    {
        return CourseReg::filter($request);
    }

    public function findWhereWithStudents($arr)
    {
        // Return the students related to the specified course
        return CourseReg::where($arr)->with('student')->get()->pluck('student');
    }

    function findAllWithActions($arr_actions = [])
    {
        $query = CourseReg::select('course_regs.*');
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
            if (key_exists('search_by', $arr_actions)) {
                $where_arr = [];
                foreach ($arr_actions['search_by'] as $col => $search_by) {
                    $where_arr[] = 'UPPER(' . $col . ') LIKE "%' . strtoupper($search_by) . '%"';


                    //$query->whereRaw($col . ' = ' . $filter_by);


                    //$query->orWhereRaw('UPPER(' . $col . ') LIKE "%' . strtoupper($search_by) . '%"');
                }

                $query->whereRaw('(' . implode(' OR ', $where_arr) . ')');
            }
            ////////////

            //Fillter By Date Range///
            if (key_exists('filter_date_range', $arr_actions)) {
                if (key_exists('from', $arr_actions['filter_date_range']) && key_exists('to', $arr_actions['filter_date_range'])) {
                    $query->Where('course_regs.created_at', '>=', $arr_actions['filter_date_range']['from']);
                    $query->Where('course_regs.created_at', '<=', $arr_actions['filter_date_range']['to']);
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


            $query->join('students', 'course_regs.student_id', '=', 'students.id');
            $query->join('courses', 'course_regs.course_id', '=', 'courses.id');
        } else {
            $query->orderBy('course_regs.created_at', 'desc');
        }

        return $query->paginate(20);
    }

    function findAllToStudent($student_id)
    {
        $query = CourseReg::select('course_regs.*');
        $query->where('course_regs.student_id', $student_id);
        $query->orderBy('course_regs.created_at', 'DESC');
        return $query->get();
    }

    function removeReg($reg_id)
    {
        //remove reg
        CourseReg::where('id', $reg_id)->delete();

        //remove payments
        CourseRegPayment::where('course_reg_id', $reg_id)->delete();
    }

    function updateWhere($conditions, $values)
    {
        $query = CourseReg::where('id', '>', 1);
        foreach ($conditions as $col => $value) {
            $query->where($col, '=', $value);
        }
        return $query->update($values);
    }
}
