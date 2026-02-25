<?php

namespace Modules\StudentModule\Repository;

use Modules\StudentModule\app\Http\Models\Student;
use Prettus\Repository\Eloquent\BaseRepository;

class StudentRepository extends BaseRepository {
    function model() {
        return Student::class;
    }

    public function findByNationalId($nationalId) {
        return Student::where('national_id', $nationalId)->first();
    }

    function filter($request) {
        return Student::filter($request);
    }
}
