<?php

namespace Modules\StudentModule\Repository;

use Modules\StudentModule\app\Http\Models\StudentExamAnswer;
use Prettus\Repository\Eloquent\BaseRepository;

class StudentExamAnswerRepository extends BaseRepository
{
    function model()
    {
        return StudentExamAnswer::class;
    }

}
