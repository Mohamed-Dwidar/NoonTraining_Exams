<?php

namespace Modules\CourseModule\Repository;

use Modules\CourseModule\app\Http\Models\Course;
use Prettus\Repository\Eloquent\BaseRepository;


class CourseRepository  extends BaseRepository
{
    function model()
    {
        return Course::class;
    }

    public function filter(array $request)
    {
        return Course::filter($request);
    }

    function findByType($id)
    {
        return Course::where('branch_id', $id)->get();
    }

    function findIDsByType($id)
    {
        if ($id == 0) {
            return Course::get()->pluck('id');
        } else {
            return Course::where('branch_id', $id)->get()->pluck('id');
        }
    }

    function getByIds($ids)
    {
        return Course::whereIN('id', $ids)->get();
    }
}
