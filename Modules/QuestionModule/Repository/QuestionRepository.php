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

    public function getRandomByType($categoryId, $type, $limit) {
        return Question::where('category_id', $categoryId)
            ->where('type', $type)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

}
