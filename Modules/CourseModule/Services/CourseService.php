<?php

namespace Modules\CourseModule\Services;

use App\Helpers\UploaderHelper;
use Modules\CourseModule\Repository\CourseRegRepository;
use Modules\CourseModule\Repository\CourseRepository;

class CourseService
{
    private $courseRepository;
    private $courseRegRepository;
    use UploaderHelper;

    public function __construct(CourseRepository $courseRepository, CourseRegRepository $courseRegRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->courseRegRepository = $courseRegRepository;
    }

    public function create($data)
    {
        $course_data = [
            'branch_id' => $data->branch_id,
            'name' => $data->name,
            'group_nu' => $data->group_nu,
            'course_org_nu' => $data->course_org_nu,
            'start_at' => $data->start_at,
            'end_at' => $data->end_at,
            'price' => $data->price,
            'exam_fees' => $data->exam_fees,
            'is_available' => 1,
        ];
        return $this->courseRepository->create($course_data);
    }

    public function update($data)
    {
        $course_data = [
            //'id' => $data->id,
            // 'branch_id' => $data->branch_id,
            'name' => $data->name,
            'group_nu' => $data->group_nu,
            'course_org_nu' => $data->course_org_nu,
            'start_at' => $data->start_at,
            'end_at' => $data->end_at,
            'price' => $data->price,
            'exam_fees' => $data->exam_fees,
            'is_available' => 1,
        ];

        $course = $this->courseRepository->update($course_data, $data->id);

        //update main price for all course_regs
        $this->courseRegRepository->updateWhere(['course_id'=>$data->id], ['main_price' => $data->price]);

        return $course;
    }

    public function findAll()
    {
        return $this->courseRepository
            ->orderBy('name', 'asc')
            ->orderBy('group_nu', 'asc')
            ->orderBy('course_org_nu', 'asc')
            ->get();
    }

    public function findAllWithFilter(array $request)
    {
        return $this->courseRepository->filter($request);
    }

    public function findOne($id)
    {
        return $this->courseRepository->find($id);
    }

    public function deleteOne($id)
    {
        $old_data = $this->courseRepository->find($id);
        if (!empty($old_data)) {
            if ($this->courseRepository->delete($id)) {
                $this->courseRegRepository->deleteWhere(['course_id' => $id]);
            }
        }
    }

    public function deleteMany($arr_ids)
    {
        if (!empty($arr_ids)) {
            foreach ($arr_ids as $id) {
                return $this->courseRepository->delete($id);
            }
        }
    }
}
