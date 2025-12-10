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

    <div class="card shadow-sm p-4">

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
                        <strong>النوع:</strong>
                        <span>{{ $student->gender === 'male' ? 'ذكر' : ($student->gender === 'female' ? 'أنثى' : '-') }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <strong>التصنيف:</strong>
                        <span>{{ $student->category->name ?? '-' }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <strong>الحالة:</strong>
                        <span class="{{ $student->is_active ? 'text-success' : 'text-danger' }}">
                            {{ $student->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <strong>تاريخ التسجيل:</strong>
                        <span>{{ $student->created_at->format('Y-m-d') }}</span>
                    </li>

                </ul>
            </div>

        </div>

        <div class="mt-4 text-end">
            <a href="{{ route(Auth::getDefaultDriver() . '.students.edit', $student->id) }}" class="btn btn-warning px-4">
                تعديل البيانات
            </a>
        </div>

    </div>
</div>
@endsection
