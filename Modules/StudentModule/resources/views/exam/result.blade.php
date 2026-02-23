@extends('layoutmodule::layouts.main')

@section('title')
    نتيجة الأختبار - {{ $exam->title }}
@endsection

@push('styles')
    <style>
        /* Exam Result Header */
        .exam-result-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        /* Stat Cards with Hover Effect */
        .stat-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .stat-card:hover {
            /* transform: translateY(-5px); */
            /* box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15) !important; */
        }

        /* Large Result Badge */
        .result-badge-large {
            font-size: 1rem;
            padding: .8rem 1rem;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin: 10px !important;
        }

        /* Score Circle Container */
        .score-circle {
            width: 280px;
            height: 280px;
            margin: 0 auto;
        }

        /* Info Box with Border Left */
        .info-box {
            background: #f8f9fa;
            border-left: 5px solid;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            /* box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important; */
            /* transform: translateX(-3px); */
        }

        /* Progress Bar Animation */
        .progress {
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Card Headers */
        .card-header {
            border-bottom: none !important;
        }

        /* Responsive Font Sizes */
        @media (max-width: 768px) {
            .exam-result-header {
                padding: 2rem 1.5rem;
            }

            .score-circle {
                width: 220px;
                height: 220px;
            }

            .display-4 {
                font-size: 2.5rem;
            }

            .fs-1 {
                font-size: 1.5rem !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper container-fluid">
        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Back Button -->
                    <div class="mb-4 text-start">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-lg px-1 py-1 mb-2">
                            <i class="fa fa-arrow-right me-2"></i>
                            <span class="fs-5">العودة إلى الاختبارات</span>
                        </a>
                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col-lg-11 mx-auto">
                    @if (!$studentExam)
                        <!-- No Attempt Card -->
                        <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                            <div class="card-body text-center py-5 px-4">
                                <div class="mb-4">
                                    <i class="fa fa-exclamation-triangle text-warning" style="font-size: 6rem;"></i>
                                </div>
                                <h2 class="display-5 fw-bold mb-4">لم تتم إجراء هذا الاختبار بعد</h2>
                                <p class="text-muted mb-5 fs-4">يبدو أنك لم تبدأ هذا الاختبار بعد. انقر على الزر أدناه
                                    للبدء
                                    في الاختبار.</p>
                                <a href="{{ route('student.exam.start', $exam->id) }}"
                                    class="btn btn-success btn-lg px-5 py-4 fs-4">
                                    <i class="fa fa-play-circle me-3"></i> بدء الاختبار الآن
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="row g-4 mb-5">
                            <div class="col-lg-6">
                                <div class="info-box border-success ">
                                    <h1 class="fw-bold">
                                        <span class="text-success">{{ $exam->title }}</span>
                                    </h1>
                                    <br />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="info-box border-success">
                                    <div class="row align-items-center">
                                        {{-- <i class="fa fa-check-circle text-success me-3" style="font-size: 3rem;"></i> --}}
                                        <div class="col-lg-8">
                                            <p class="text-muted mb-1">الدرجة المحققة</p>
                                            <h3 class="mb-0 fw-bold text-success">
                                                {{ $studentExam->score }} / {{ $exam->total_grade }}
                                                &nbsp;&nbsp;
                                                ({{ number_format($studentExam->score, 1) }}%)
                                            </h3>
                                        </div>
                                        <div class="col-lg-4">
                                            <br>
                                            <span
                                                class="result-badge-large badge @if ($studentExam->score >= $exam->success_grade) bg-success @else bg-danger @endif shadow mt-3">
                                                <i
                                                    class="fa fa-@if ($studentExam->score >= $exam->success_grade) check-circle @else times-circle @endif me-2"></i>
                                                {{ $studentExam->score >= $exam->success_grade ? 'ناجح ✓' : 'راسب ✗' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Additional Details -->
                        <div class="row g-4 mb-5">
                            <div class="col-lg-3">
                                <div class="info-box border-success">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-check-circle text-success me-3" style="font-size: 3rem;"></i>
                                        <div>
                                            <p class="text-muted mb-1 fs-6">درجة النجاح المطلوبة</p>
                                            <h3 class="mb-0 fw-bold text-success">{{ $exam->success_grade }}%</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="info-box border-primary">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-question-circle text-primary me-3" style="font-size: 3rem;"></i>
                                        <div>
                                            <p class="text-muted mb-1 fs-6">عدد الأسئلة</p>
                                            <h3 class="mb-0 fw-bold text-primary">{{ $exam->total_questions }} سؤال
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="info-box border-info">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-check-square-o text-info me-3" style="font-size: 3rem;"></i>
                                        <div>
                                            <p class="text-muted mb-1 fs-6">عدد الأسئلة الصحيحة</p>
                                            <h3 class="mb-0 fw-bold text-info">
                                                {{ $studentExam->studentExamAnswers->where('is_correct', true)->count() }}
                                                سؤال
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="info-box border-primary">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-calendar text-secondary me-3" style="font-size: 3rem;"></i>
                                        <div>
                                            <p class="text-muted mb-1 fs-6">تاريخ الاختبار</p>
                                            <h3 class="mb-0 fw-bold text-secondary">
                                                {{ $studentExam->started_at->format('Y-m-d H:i') }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
