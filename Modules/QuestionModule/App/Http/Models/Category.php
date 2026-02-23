<?php

namespace Modules\QuestionModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\QuestionModule\app\Http\Models\Question;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function exams()
    {
        return $this->hasMany(Question::class);
    }
}
