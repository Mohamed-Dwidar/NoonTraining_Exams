<?php

namespace Modules\StudentModule\app\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\QuestionModule\app\Http\Models\Category;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'national_id',
        'birth_date',
        'gender',
        'category_id',
        'student_code',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function getFullInfoAttribute()
    {
        return $this->name . ' - ' . ($this->student_code ?? 'بدون كود');
    }
}
