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

                                    <form method="POST"
                                        action="{{ route(Auth::getDefaultDriver() . '.students.assignExam') }}">
                                        @csrf

                                        <input type="hidden" name="student_id" value="{{ $student->id }}">

                                        <label class="fw-bold">اختر الامتحانات</label>
                                        <select name="exam_id[]" class="form-control" multiple required>
                                            @foreach ($exams as $exam)
                                                <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="btn btn-primary mt-2">ربط الامتحان</button>
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
