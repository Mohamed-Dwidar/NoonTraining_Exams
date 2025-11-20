@extends('layoutmodule::admin.main')

@section('title', 'إنشاء امتحان جديد')

@section('content')

<div class="container-fluid">
    @include('layoutmodule::admin.flash')

    <div class="card">
        <div class="card-body p-3">

            <form method="POST" action="{{ route('admin.exam.store') }}">
                @csrf

                <div class="row">
                    <div class="col-lg-6">
                        <label>عنوان الامتحان</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="col-lg-3">
                        <label>تاريخ البداية</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>

                    <div class="col-lg-3">
                        <label>تاريخ النهاية</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-3">
                        <label>المدة (بالدقائق)</label>
                        <input type="number" name="duration_minutes" class="form-control">
                    </div>

                    <div class="col-lg-3">
                        <label>عدد الأسئلة</label>
                        <input type="number" name="total_questions" class="form-control">
                    </div>

                    <div class="col-lg-3">
                        <label>درجة النجاح</label>
                        <input type="number" step="0.01" name="success_grade" class="form-control">
                    </div>

                    <div class="col-lg-3">
                        <label>الدرجة الكلية</label>
                        <input type="number" step="0.01" name="total_grade" class="form-control">
                    </div>
                </div>

                <button class="btn btn-primary mt-3">إنشاء الامتحان</button>

            </form>

        </div>
    </div>

</div>

@endsection
