@extends('layoutmodule::admin.main')

@section('title')
    نتيجة الامتحان - {{ $exam->title }}
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        @include('layoutmodule::admin.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 mx-auto p-2">
                    <div class="card p-2">
                        <div class="p-2">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    نتيجة الامتحان: {{ $exam->title }}
                                </h4>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-sm mt-1">
                                    <i class="fa fa-arrow-right"></i> العودة
                                </a>
                            </div>

                            <div class="card-body">
                                @if (!$attempt)
                                    <div class="alert alert-warning text-center">
                                        <h5>لم تتم إجراء هذا الامتحان بعد</h5>
                                        <a href="{{ route('student.exam.start', $exam->id) }}" class="btn btn-success mt-2">
                                            بدء الامتحان
                                        </a>
                                    </div>
                                @else
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="card border-info shadow-sm p-1">
                                                <div class="card-body text-center">
                                                    <h5 class="text-info mb-1">
                                                        <i class="fa fa-percent"></i> النسبة المئوية
                                                    </h5>
                                                    <div class="display-4 fw-bold @if ($attempt->score >= $exam->success_grade) text-success @else text-danger @endif">
                                                        {{ number_format($attempt->score, 1) }}%
                                                    </div>
                                                    <small class="text-muted d-block mt-2">من أصل 100 درجة</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card border-primary shadow-sm h-100">
                                                <div class="card-body text-center">
                                                    <h5 class="text-primary mb-3">
                                                        <i class="fa fa-check-circle"></i> الحالة
                                                    </h5>
                                                    <div class="mt-2 pt-1">
                                                        @if ($attempt->score >= $exam->success_grade)
                                                            <span class="badge bg-success p-3 fs-6">
                                                                <i class="fa fa-check"></i> ناجح
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger p-3 fs-6">
                                                                <i class="fa fa-times"></i> راسب
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted d-block mt-3 pt-1">
                                                        <i class="fa fa-info-circle"></i> درجة النجاح: {{ $exam->success_grade }}%
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>تفاصيل الامتحان</h5>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th width="30%">تاريخ البدء</th>
                                                            <td>{{ $attempt->started_at ? $attempt->started_at->format('Y-m-d H:i:s') : '---' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>تاريخ الانتهاء</th>
                                                            <td>{{ $attempt->completed_at ? $attempt->completed_at->format('Y-m-d H:i:s') : '---' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>مدة الامتحان</th>
                                                            <td>{{ $exam->duration_minutes }} دقيقة</td>
                                                        </tr>
                                                        <tr>
                                                            <th>عدد الأسئلة</th>
                                                            <td>{{ $exam->total_questions }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>الدرجة الكلية</th>
                                                            <td>{{ $exam->total_grade }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>الحالة</th>
                                                            <td>
                                                                @if ($attempt->status == 'completed')
                                                                    <span class="badge bg-success">مكتمل</span>
                                                                @elseif($attempt->status == 'in_progress')
                                                                    <span class="badge bg-warning">قيد التنفيذ</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-secondary">{{ $attempt->status }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <h6 class="mb-2">مستوى الأداء</h6>
                                        <div class="progress" style="height: 30px;">
                                            <div class="progress-bar @if ($attempt->score >= $exam->success_grade) bg-success @else bg-danger @endif"
                                                role="progressbar" style="width: {{ $attempt->score }}%"
                                                aria-valuenow="{{ $attempt->score }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ number_format($attempt->score, 1) }}%
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small>0%</small>
                                            <small class="text-primary">درجة النجاح: {{ $exam->success_grade }}%</small>
                                            <small>100%</small>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                                            <i class="fa fa-home"></i> العودة للرئيسية
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
