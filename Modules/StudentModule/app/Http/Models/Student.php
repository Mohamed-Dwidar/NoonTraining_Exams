<?php

namespace Modules\StudentModule\App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\ExamModule\app\Http\Models\Exam;
use Modules\QuestionModule\app\Http\Models\Category;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'national_id',
        'gender',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'student_exams', 'student_id', 'exam_id');
    }

    public function studentExams()
    {
        return $this->hasMany(StudentExam::class, 'student_id');
    }

     public function scopeFilter($query, $request = []){

        if (isset($request['name']) && $request['name'] != '') {
            $query->where('name', 'LIKE', '%' . $request['name'] . '%');
        }

        if (isset($request['email']) && $request['email'] != '') {
            $query->where('email', 'LIKE', '%' . $request['email'] . '%');
        }

        if (isset($request['phone']) && $request['phone'] != '') {
            $query->where('phone', 'LIKE', '%' . $request['phone'] . '%');
        }

        if (isset($request['national_id']) && $request['national_id'] != '') {
            $query->where('national_id', 'LIKE', '%' . $request['national_id'] . '%');
        }

        if (isset($request['search']) && $request['search'] != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request['search'] . '%')
                    ->orWhere('email', 'LIKE', '%' . $request['search'] . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request['search'] . '%')
                    ->orWhere('national_id', 'LIKE', '%' . $request['search'] . '%');
            });
        }

        return $query;
     }
}
