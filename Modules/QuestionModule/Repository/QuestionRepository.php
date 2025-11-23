<?php

namespace Modules\QuestionModule\Repository;

use Modules\QuestionModule\app\Http\Models\Question;
use Prettus\Repository\Eloquent\BaseRepository;

class QuestionRepository extends BaseRepository
{
    function model()
    {
        return Question::class;
    }

}
