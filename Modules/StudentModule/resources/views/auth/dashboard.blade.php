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
                            <h4 class="card-title"><i class="fa fa-list"></i> الأختبارات المرتبطة بالطالب</h4>
                        </div>

                        <div class="card-body">
                            @if ($exams->isEmpty())
                                <p class="text-center text-muted">لا توجد أختبارات مرتبطة بهذا الطالب.</p>
                            @else
                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>اسم الأختبار</th>
                                            <th>الحالة</th>
                                            <th>النتيجة</th>
                                            <th>التحكم</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($exams as $exam)
                                            @php
                                                $attempt = $exam->studentAttempt(auth()->guard('student')->id())->first();
                                            @endphp

                                            <tr>
                                                <td>{{ $exam->title }}</td>
                                                <td>
                                                    @if (!$attempt)

                                                        <span class="badge bg-secondary">غير مسجل</span>
                                                    @elseif($attempt && $attempt->status == 'not_started')
                                                        <span class="badge bg-warning">لم يبدأ بعد</span>
                                                    @elseif($attempt && $attempt->status == 'completed')
                                                        <span class="badge bg-success">تم التسليم</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($attempt && $attempt->score !== null)
                                                        <span class="badge @if($attempt->score >= $exam->success_grade) bg-success @else bg-danger @endif">
                                                            {{ number_format($attempt->score, 1) }}%
                                                        </span>
                                                        @if($attempt->score >= $exam->success_grade)
                                                            <small class="text-success d-block pt-1">(ناجح)</small>
                                                        @else
                                                            <small class="text-danger d-block pt-1">(راسب)</small>
                                                        @endif
                                                    @else
                                                        —
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($attempt->status == 'not_started')
                                                        <a href="{{ route('student.exam.start', $exam->id) }}"
                                                            class="btn btn-success btn-sm">
                                                            بدء الأختبار
                                                        </a>

                                                    @elseif($attempt->status == 'completed')
                                                        <a href="{{ route('student.exam.result', $exam->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            عرض النتيجة
                                                        </a>
                                                    @endif
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
