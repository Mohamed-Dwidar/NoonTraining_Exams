<?php

namespace Modules\ExamModule\Repository;

use Modules\ExamModule\app\Http\Models\Exam;
use Prettus\Repository\Eloquent\BaseRepository;

class ExamRepository extends BaseRepository
{
    function model()
    {
        return Exam::class;
    }

}
