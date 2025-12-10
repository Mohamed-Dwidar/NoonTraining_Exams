<?php

namespace App\Modules\StudentModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $studentId = $this->route('id') ?? $this->id;

        return [
            'name'         => 'required|string|max:255',
            'email'        => ['nullable','email',Rule::unique('students','email')->ignore($studentId)],
            'phone'        => 'nullable|string|max:20',
            'national_id'  => ['nullable','digits:14',Rule::unique('students','national_id')->ignore($studentId)],
            'birth_date'   => 'nullable|date',
            'gender'       => 'nullable|string|in:male,female',
            'category_id'  => 'nullable|exists:categories,id',
            'student_code' => ['nullable','string',Rule::unique('students','student_code')->ignore($studentId)],
        ];
    }
}
