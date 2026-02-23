<?php

namespace Modules\StudentModule\App\Http\Requests;

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
            'gender'       => 'nullable|string|in:male,female'
        ];
    }
}
