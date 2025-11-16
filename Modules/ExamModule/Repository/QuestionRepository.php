<?php

namespace Modules\ExamModule\Repository;

use Modules\ExamModule\app\Http\Models\Question;
use Prettus\Repository\Eloquent\BaseRepository;

class QuestionRepository extends BaseRepository
{
    function model()
    {
        return Question::class;
    }

}
