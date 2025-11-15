<?php

namespace Modules\CourseModule\Repository;

use Modules\CourseModule\app\Http\Models\CourseRegStatus;
use Prettus\Repository\Eloquent\BaseRepository;
 

class CourseRegStatusRepository extends BaseRepository
{
    function model()
    {
        return CourseRegStatus::class;
    }
}
