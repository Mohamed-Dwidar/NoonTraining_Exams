<?php

namespace Modules\CourseModule\Services;

use App\Helpers\UploaderHelper;
use Illuminate\Support\Facades\File;
use Modules\CourseModule\Repository\CourseRegRepository;
use Modules\CourseModule\Repository\CourseRepository;

class CourseRegService
{
    private $courseRepository;
    private $courseRegRepository;
    use UploaderHelper;

    public function __construct(CourseRepository $courseRepository, CourseRegRepository $courseRegRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->courseRegRepository = $courseRegRepository;
    }

    public function registerStudentToCourse($data)
    {
        $course_info = $this->courseRepository->find($data->course_id);
        $new_price = $data->new_price;

        $course_data = [
            'course_id' => $course_info->id,
            'student_id' => $data->student_id,
            'status_id' => $data->status_id != null ? $data->status_id : 1,
            'main_price' => $course_info->price,
            'price' => $new_price,
            'exam_fees' => $course_info->exam_fees,
            'need_work_agree' => key_exists('agree', $data->all()) ? $data->agree : 0,
            'registered_by' => $data->registered_by,
        ];
        return $this->courseRegRepository->create($course_data);
    }

    public function getStudentRegDetails($studen_id, $course_id)
    {
        return $this->courseRegRepository->findWhere([
            'student_id' => $studen_id,
            'course_id' => $course_id
        ])->first();
    }

    public function findAllWithFilter(array $request)
    {
        return $this->courseRegRepository->filter($request);
    }

    public function findAllNeedAction($arr_actions = [])
    {
        $actions = [];
        ///Sort///
        $arr_sorts = ['cdate_az', 'cdate_za', 'date_az', 'date_za'];
        if (!empty($arr_actions['sort'])) {
            if (in_array($arr_actions['sort'], $arr_sorts)) {
                if ($arr_actions['sort'] == 'cdate_az') {
                    $sort_by['courses.start_at'] = 'asc';
                } elseif ($arr_actions['sort'] == 'cdate_za') {
                    $sort_by['courses.start_at'] = 'desc';
                } elseif ($arr_actions['sort'] == 'date_az') {
                    $sort_by['course_regs.created_at'] = 'asc';
                } elseif ($arr_actions['sort'] == 'date_za') {
                    $sort_by['course_regs.created_at'] = 'desc';
                }
                $actions['order_by'] = $sort_by;
            }
        }
        /////////

        ///Search///
        if (!empty($arr_actions['search'])) {
            $actions['search_by']['students.name'] = $arr_actions['search'];
            $actions['search_by']['students.email'] = $arr_actions['search'];
            $actions['search_by']['students.id_nu'] = $arr_actions['search'];
            $actions['search_by']['students.phone'] = $arr_actions['search'];
            $actions['search_by']['students.mobile'] = $arr_actions['search'];
        }
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
            $actions['filter']['courses.id'] = $arr_actions['filter_crs'];
        }
        ///////////////

        ///Filter Place///
        if (!empty($arr_actions['filter_plc'])) {
            $actions['filter']['courses.is_online'] = $arr_actions['filter_plc'] == 1 ? 1 : 0;
        }
        ///////////////

        ///Filter Status///
        if (!empty($arr_actions['filter_sts'])) {
            if (in_array($arr_actions['filter_sts'], [11, 12, 13, 14])) {
                if ($arr_actions['filter_sts'] == 11) {
                    $actions['filter']['course_regs.is_paid'] = 0;
                } elseif ($arr_actions['filter_sts'] == 12) {
                    $actions['filter']['course_regs.is_paid'] = 1;
                } elseif ($arr_actions['filter_sts'] == 13) {
                    $actions['filter']['course_regs.is_recive_cert'] = 1;
                } elseif ($arr_actions['filter_sts'] == 14) {
                    $actions['filter']['course_regs.is_recive_cert'] = 0;
                }
            } else {
                $actions['filter']['course_regs.status_id'] = $arr_actions['filter_sts'];
            }
        } else {
            $actions['filter']['course_regs.status_id'] = [1, 3];
        }
        ///////////////


        // print_r($actions);
        // exit;
        return $this->courseRegRepository->findAllWithActions($actions);
    }

    public function findOne($id)
    {
        return $this->courseRegRepository->find($id);
    }

    public function deleteOne($id)
    {
        // $old_data = $this->courseRegRepository->find($id);
        // if (!empty($old_data)) {
        //     $this->courseRegRepository->delete($id);
        // }
        $this->courseRegRepository->removeReg($id);
    }

    public function deleteMany($arr_ids)
    {
        if (!empty($arr_ids)) {
            foreach ($arr_ids as $id) {
                return $this->courseRegRepository->delete($id);
            }
        }
    }

    public function takeRegAction($data)
    {
        $date_data = [];
        if ($data['action'] == 'accept') {
            $date_data['status_id'] = 2;
            if ($data['chngDate'] == 1)
                $date_data['course_id'] = $data['course_id'];
        } elseif ($data['action'] == 'reject') {
            $date_data['status_id'] = 4;
        } elseif ($data['action'] == 'waiting') {
            $date_data['status_id'] = 3;
        }
        return $this->courseRegRepository->update($date_data, $data['id']);
    }

    public function studentCourses($student_id)
    {
        return $this->courseRegRepository->findAllToStudent($student_id);
    }

    public function updateCertDelivered($id, $is_delivered)
    {
        return $this->courseRegRepository->update(['is_recive_cert' => $is_delivered], $id);
    }

    public function updatePriceForOneStudent($data)
    {
        $reg = $this->courseRegRepository->update(['student_price' => $data->new_price, 'price' => $data->new_price], $data->reg_id);
        $reg->update(
            [
                'is_course_paid' => ($reg->coursePaidAmount >= $reg->price) ? 1 : 0,
                'is_exam_paid' => ($reg->examPaidAmount >= $reg->course->exam_fees) ? 1 : 0
            ]
        );
        return $reg;
    }

    public function updateDiscountForOneStudent($data)
    {
        $regObj = $this->courseRegRepository->find($data->reg_id);
        //$new_price = round($data->student_price - ($data->student_price * ($data->new_discount / 100)));
        $new_price = $regObj->student_price - $data->new_discount;
        $reg = $this->courseRegRepository->update(['price' => $new_price], $data->reg_id);
        $reg->update(
            [
                'is_course_paid' => ($reg->coursePaidAmount >= $reg->price) ? 1 : 0,
                'is_exam_paid' => ($reg->examPaidAmount >= $reg->exam_fees) ? 1 : 0
            ]
        );
        return $reg;
    }

    public function updateExamPriceForOneStudent($data)
    {
        $reg = $this->courseRegRepository->update(['exam_fees' => $data->new_exam_price], $data->reg_id);
        $reg->update(
            [
                'is_exam_paid' => ($reg->examPaidAmount >= $reg->exam_fees) ? 1 : 0
            ]
        );
        return $reg;
    }

    public function updateRegStatus($data)
    {
        return $this->courseRegRepository->update(['status_id' => $data->status_id], $data->reg_id);
    }

    public function updateRegBy($data)
    {
        return $this->courseRegRepository->update(['registered_by' => $data->reg_by], $data->reg_id);
    }

    public function updatePaymentType($data)
    {
        if ($data->status == 'normal') {  //الدورة افتراضي
            $this->courseRegRepository->update(['is_free' => 0], $data->reg_id);
        }
        if ($data->status == 'free') {  //الدورة مجانا
            $this->courseRegRepository->update(['is_free' => 1], $data->reg_id);
        } elseif ($data->status == 'nopaying') {    //لا يرغب في السداد
            $this->courseRegRepository->update(['status_id' => 10, 'is_free' => 0], $data->reg_id);
        } elseif ($data->status == 'leave') {    //مغادر
            $this->courseRegRepository->update(['is_leave' => 1], $data->reg_id);
        }
        return $this->checkAndUpdateRegStatus($data->reg_id);
    }

    public function checkAndUpdateRegStatus($reg_id)
    {
        $regObj = $this->courseRegRepository->find($reg_id);
        //dd($regObj->coursePaidAmount , $regObj->price, $regObj->is_exam_paid , $regObj->toArray());
        if ($regObj->is_free == 0 && $regObj->status_id == 10) {     //لا يرغب في السداد
            return $regObj;
        } elseif ($regObj->is_free == 1 && $regObj->is_exam_paid == 0) {       //الدورة مجانا + لم يسدد رسوم الاختبار
            $status_id = 8;
        } elseif ($regObj->is_free == 1 && $regObj->is_exam_paid == 1) {      //الدورة مجانا + سدد رسوم الاختبار
            $status_id = 9;
        } elseif ($regObj->is_free == 0 && $regObj->coursePaidAmount == 0 && $regObj->is_exam_paid == 0) {       //لم يسدد الدورة أو الاختبار
            $status_id = 1;
        } elseif ($regObj->is_free == 0 && $regObj->coursePaidAmount == 0 && $regObj->is_exam_paid == 1) {       //سدد الاختبار فقط
            $status_id = 5;
        } elseif ($regObj->is_free == 0 && $regObj->coursePaidAmount >= $regObj->price && $regObj->is_exam_paid == 0) {       //سدد الدورة فقط
            $status_id = 4;
        } elseif ($regObj->is_free == 0 && $regObj->coursePaidAmount < $regObj->price && $regObj->is_exam_paid == 0) {       //يتم تقسيط الدورة + لم يسدد الاختبار
            $status_id = 2;
        } elseif ($regObj->is_free == 0 && $regObj->coursePaidAmount < $regObj->price && $regObj->is_exam_paid == 1) {       //يتم تقسيط الدورة + سدد الاختبار
            $status_id = 3;
        } elseif ($regObj->is_free == 0 && $regObj->coursePaidAmount >= $regObj->price && $regObj->is_exam_paid == 1 && $regObj->is_recive_cert == 0) {      //سدد الدورة + الاختبار + لم يستلم الشهادة
            $status_id = 6;
        } elseif ($regObj->is_free == 0 && $regObj->coursePaidAmount >= $regObj->price && $regObj->is_exam_paid == 1 && $regObj->is_recive_cert == 1) {      //سدد الدورة + الاختبار + استلم الشهادة
            $status_id = 7;
        }

        return $this->courseRegRepository->update(['status_id' => $status_id], $regObj->id);
    }

    public function getStudentsForCourse($courseId)
    {
        // Call the repository to get the course registrations with related students
        return $this->courseRegRepository->findWhereWithStudents(['course_id' => $courseId]);
    }

    public function findWhere($arr)
    {
        // Return the query builder instance to allow further query modifications
        return $this->courseRegRepository->findWhere($arr);
    }
}
