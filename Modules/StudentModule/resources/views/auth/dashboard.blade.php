@extends('layoutmodule::admin.main')

@section('title')
لوحة تحكم الطالب  
@endsection

@section('content')

    <div class="content-wrapper container-fluid">


        @include('layoutmodule::admin.flash')

        <div class="content-body">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card p-3">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fa fa-list"></i> الامتحانات المرتبطة بالطالب</h4>
                        </div>

                        <div class="card-body">
                            @if ($exams->isEmpty())
                                <p class="text-center text-muted">لا توجد امتحانات مرتبطة بهذا الطالب.</p>
                            @else
                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>اسم الامتحان</th>
                                            <th>الحالة</th>
                                            <th>النتيجة</th>
                                            <th>التحكم</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($exams as $exam)
                                            @php
                                                $attempt = $exam->attempt;
                                            @endphp

                                            <tr>
                                                <td>{{ $exam->title }}</td>

                                                <td>
                                                    @if (!$attempt)
                                                        <span class="badge bg-warning">لم يبدأ بعد</span>
                                                    @elseif($attempt && !$attempt->is_finished)
                                                        <span class="badge bg-info">جاري الامتحان</span>
                                                    @else
                                                        <span class="badge bg-success">تم التسليم</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($attempt && $attempt->is_finished)
                                                        <span class="badge bg-primary">
                                                            {{ $attempt->score }} / {{ $exam->total_grade }}
                                                        </span>
                                                    @else
                                                        —
                                                    @endif
                                                </td>

                                                <td>
                                                    {{-- @if (!$attempt)
                                                        <a href="{{ route('student.startExam', $exam->id) }}"
                                                            class="btn btn-success btn-sm">
                                                            بدء الامتحان
                                                        </a>
                                                    @elseif($attempt && $attempt->is_finished)
                                                        <a class="btn btn-secondary btn-sm disabled">تم الحل</a>
                                                    @else
                                                        <a href="{{ route('student.continueExam', $exam->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            متابعة الامتحان
                                                        </a>
                                                    @endif --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
