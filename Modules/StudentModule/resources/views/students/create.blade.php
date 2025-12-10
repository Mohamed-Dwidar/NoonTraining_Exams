@extends('layoutmodule::admin.main')

@section('title', 'إضافة طالب')

@section('content')
<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3><i class="fa fa-user-graduate"></i> إضافة طالب جديد</h3>
        </div>
    </div>

    @include('layoutmodule::admin.flash')

    <div class="content-body">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card p-2">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-lg-12 col-12">

                                <form method="POST" action="{{ route(Auth::getDefaultDriver() . '.students.store') }}">
                                    @csrf

                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label class="form-label fw-bold">اسم الطالب</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-bold">البريد الإلكتروني</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label class="form-label fw-bold">رقم الهاتف</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-bold">الرقم السري</label>
                                            <input type="text" name="password" class="form-control" value="{{ old('password') }}">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-bold">الرقم القومي</label>
                                            <input type="text" name="national_id" class="form-control" value="{{ old('national_id') }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label class="form-label fw-bold">تاريخ الميلاد</label>
                                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-bold">النوع</label>
                                            <select name="gender" class="form-control">
                                                <option value="">اختر النوع</option>
                                                <option value="male">ذكر</option>
                                                <option value="female">أنثى</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label class="form-label fw-bold">التصنيف</label>
                                            <select name="category_id" class="form-control">
                                                <option value="">اختر التصنيف</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-bold">كود الطالب</label>
                                            <input type="text" name="student_code" class="form-control" value="{{ old('student_code') }}">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 mt-3">
                                        <button type="submit" class="btn btn-success px-4">حفظ</button>
                                        <a href="{{ route(Auth::getDefaultDriver() . '.students.index') }}" class="btn btn-secondary">إلغاء</a>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
