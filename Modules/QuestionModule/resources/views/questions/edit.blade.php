@extends('layoutmodule::admin.main')

@section('title')
    تعديل امتحان
@endsection

@section('content')
<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3><i class="fa fa-file"></i> تعديل امتحان</h3>
        </div>
    </div>

    @include('layoutmodule::admin.flash')

    <div class="content-body">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-lg-12 col-12">

                                <form class="card-form side-form" method="POST"
                                      action="{{ route('admin.exam.update') }}">
                                    @csrf

                                    <input type="hidden" name="id" value="{{ $exam->id }}">

                                    {{-- Title --}}
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                            <label for="title">عنوان الامتحان</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="title" name="title"
                                                       value="{{ old('title', $exam->title) }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-xs-12 col-12">
                                            <label for="description">الوصف</label>
                                            <div class="form-group">
                                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $exam->description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Dates --}}
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                            <label for="start_date">تاريخ البداية</label>
                                            <div class="form-group">
                                                <input type="date" class="form-control" id="start_date" name="start_date"
                                                       value="{{ old('start_date', $exam->start_date) }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                            <label for="end_date">تاريخ النهاية</label>
                                            <div class="form-group">
                                                <input type="date" class="form-control" id="end_date" name="end_date"
                                                       value="{{ old('end_date', $exam->end_date) }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Duration --}}
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                            <label for="duration_minutes">المدة (بالدقائق)</label>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="duration_minutes"
                                                       name="duration_minutes"
                                                       value="{{ old('duration_minutes', $exam->duration_minutes) }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Questions --}}
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                            <label for="total_questions">عدد الأسئلة</label>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="total_questions"
                                                       name="total_questions"
                                                       value="{{ old('total_questions', $exam->total_questions) }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                            <label for="mcq_count">عدد أسئلة الاختيار من متعدد</label>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="mcq_count"
                                                       name="mcq_count"
                                                       value="{{ old('mcq_count', $exam->mcq_count) }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                            <label for="true_false_count">عدد أسئلة صح/خطأ</label>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="true_false_count"
                                                       name="true_false_count"
                                                       value="{{ old('true_false_count', $exam->true_false_count) }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Grades --}}
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                            <label for="success_grade">درجة النجاح</label>
                                            <div class="form-group">
                                                <input type="number" step="0.01" class="form-control" id="success_grade"
                                                       name="success_grade"
                                                       value="{{ old('success_grade', $exam->success_grade) }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                            <label for="total_grade">الدرجة الكلية</label>
                                            <div class="form-group">
                                                <input type="number" step="0.01" class="form-control" id="total_grade"
                                                       name="total_grade"
                                                       value="{{ old('total_grade', $exam->total_grade) }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                    </div>

                                </form>
                            </div>
                            <div class="col-lg-1 col-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
