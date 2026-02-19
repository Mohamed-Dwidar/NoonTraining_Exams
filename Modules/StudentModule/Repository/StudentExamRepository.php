<?php

namespace Modules\StudentModule\Repository;

use Modules\StudentModule\app\Http\Models\StudentExam;
use Prettus\Repository\Eloquent\BaseRepository;

class StudentExamRepository extends BaseRepository {
    function model() {
        return StudentExam::class;
    }
}
