<?php

namespace Modules\QuestionModule\Repository;

use Modules\QuestionModule\app\Http\Models\Category;
use Prettus\Repository\Eloquent\BaseRepository;

class CategoryRepository extends BaseRepository
{
    function model()
    {
        return Category::class;
    }

}
