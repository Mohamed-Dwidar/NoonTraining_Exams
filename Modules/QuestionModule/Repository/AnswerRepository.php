<?php

namespace Modules\QuestionModule\Repository;

use Modules\QuestionModule\app\Http\Models\Answer;
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
