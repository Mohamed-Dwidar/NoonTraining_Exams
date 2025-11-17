<?php

namespace Modules\ExamModule\Repository;

use Modules\ExamModule\app\Http\Models\Answer;
use Prettus\Repository\Eloquent\BaseRepository;

class AnswerRepository extends BaseRepository
{
    function model()
    {
        return Answer::class;
    }

    public function findByQuestionId($questionId)
    {
        return Answer::where('question_id', $questionId)->first();
    }

}
