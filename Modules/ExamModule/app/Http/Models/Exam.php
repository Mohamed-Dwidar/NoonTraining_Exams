<?php

namespace Modules\ExamModule\app\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\UserModule\app\Http\Models\User;
use Modules\ExamModule\app\Http\Models\Question;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'duration_minutes',
        'total_questions',
        'mcq_count',
        'true_false_count',
        'success_grade',
        'total_grade',
        'created_by',
    ];


    // Exam creator (Admin/User)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // // If you will store questions in a table like exam_questions
    public function questions()
    {
        return $this->hasMany(Question::class, 'exam_id');
    }

    // // If you will store submissions/attempts
    // public function attempts()
    // {
    //     return $this->hasMany(ExamAttempt::class, 'exam_id');
    // }

    // // If you want to store results
    // public function results()
    // {
    //     return $this->hasMany(ExamResult::class, 'exam_id');
    // }
}
