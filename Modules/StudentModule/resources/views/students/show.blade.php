@extends('layoutmodule::admin.main')

@section('title', 'بيانات الطالب')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>الملف الشخصي للطالب</h3>
            <a href="{{ route(Auth::getDefaultDriver() . '.students.index') }}" class="btn btn-secondary">
                رجوع للقائمة
            </a>
        </div>

        <div class="card shadow-sm p-4 mt-2">
<div class="p-2">
            <div class="row">

                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">البيانات الأساسية</h5>

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>الاسم:</strong>
                            <span>{{ $student->name }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>الكود:</strong>
                            <span>{{ $student->student_code ?? '-' }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>البريد الإلكتروني:</strong>
                            <span>{{ $student->email ?? '-' }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>الهاتف:</strong>
                            <span>{{ $student->phone ?? '-' }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>الرقم القومي:</strong>
                            <span>{{ $student->national_id ?? '-' }}</span>
                        </li>
                    </ul>
                </div>


                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">معلومات إضافية</h5>

                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>تاريخ الميلاد:</strong>
                            <span>{{ $student->birth_date ?? '-' }}</span>
                        </li>



                        <li class="list-group-item d-flex justify-content-between">
                            <strong>تاريخ التسجيل:</strong>
                            <span>{{ $student->created_at->format('Y-m-d') }}</span>
                        </li>

                    </ul>
                </div>

            </div>

            <div class="card shadow-sm p-4 mt-4">
                <h5 class="fw-bold mb-3">الامتحانات المسندة للطالب</h5>

                @if ($student->exams->count() > 0)
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>اسم الامتحان</th>
                                <th>النتيجة</th>
                                <th>تاريخ الإسناد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student->exams as $exam)
                                <tr>
                                    <td>{{ $exam->title }}</td>

                                    <td>
                                        @if ($exam->pivot->score !== null)
                                            <span class="badge bg-success">{{ $exam->pivot->score }}</span>
                                        @else
                                            <span class="badge bg-secondary">لم يتم الحل بعد</span>
                                        @endif
                                    </td>

                                    <td>{{ $exam->pivot->created_at ? $exam->pivot->created_at->format('Y-m-d') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info text-center">
                        لا توجد امتحانات مسندة لهذا الطالب حتى الآن.
                    </div>
                @endif
            </div>


            <div class="mt-4 text-end">
                <a href="{{ route(Auth::getDefaultDriver() . '.students.edit', $student->id) }}"
                    class="btn btn-warning px-4">
                    تعديل البيانات
                </a>
            </div>
</div>
        </div>
    </div>
@endsection
