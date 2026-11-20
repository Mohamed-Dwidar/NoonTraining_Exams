@extends('layoutmodule::layouts.main')

@section('title')
    نتيجة أختبار سرعة الكتابة
@endsection

@push('styles')
    <style>
        /* The theme (Bootstrap 4 alpha) has no .badge styles: a block-level result box */
        .tt-result {
            display: inline-block;
            margin: 1rem 0 .5rem;
            padding: .5rem 2rem;
            border-radius: 8px;
            font-size: 1.6rem;
            font-weight: bold;
            line-height: 1.6;
            color: #fff;
        }
        .tt-center { text-align: center; }
        .tt-result-pass { background: #28a745; }
        .tt-result-fail { background: #dc3545; }
    </style>
@endpush

@section('content')
    <div class="content-wrapper container-fluid">
        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="mb-2">
                <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                    <i class="fa fa-arrow-right"></i> العودة
                </a>
            </div>

            <div class="card p-3">
                <div class="card-body">
                    <div class="tt-center mb-3">
                        <h3 class="mb-1"><i class="fa fa-keyboard-o"></i> نتيجة أختبار سرعة الكتابة</h3>
                        @if ($studentExam)
                            <p class="text-muted mb-1">الجزء الأول من أختبار: <strong>{{ optional($studentExam->exam)->title }}</strong></p>
                        @endif

                        <div class="tt-result {{ $attempt->passed ? 'tt-result-pass' : 'tt-result-fail' }}">
                            <i class="fa {{ $attempt->passed ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $attempt->passed ? 'ناجح' : 'راسب' }}
                        </div>

                        <h4 class="mt-1">
                            الكلمات الصحيحة: <strong>{{ $attempt->correct_words }}</strong> من {{ $attempt->word_count }}
                        </h4>
                        <p class="text-muted mb-0">
                            درجة النجاح: {{ $attempt->success_grade }} كلمة صحيحة —
                            الكلمات المكتوبة: {{ $attempt->typed_words }} —
                            الكلمات الخاطئة: {{ $attempt->wrong_words }}
                        </p>
                    </div>

                    {{-- Linked to a course and the questions part hasn't started yet --}}
                    @if ($studentExam && $studentExam->status === 'not_started')
                        <div class="alert {{ $attempt->passed ? 'alert-success' : 'alert-warning' }} tt-center">
                            @if ($attempt->passed)
                                يمكنك الآن الانتقال إلى الجزء الثاني من الأختبار (الأسئلة).
                            @else
                                لم تجتز أختبار سرعة الكتابة. يمكنك إكمال الجزء الثاني من الأختبار (الأسئلة) أو الخروج.
                            @endif
                            <div class="mt-2">
                                <a href="{{ route('student.exam.start', ['studentExamId' => $studentExam->id, 'proceed' => 1]) }}" class="btn btn-success">
                                    <i class="fa fa-arrow-left"></i> إكمال إلى الأسئلة
                                </a>
                                @unless ($attempt->passed)
                                    <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                                        <i class="fa fa-sign-out"></i> الخروج من الأختبار
                                    </a>
                                @endunless
                            </div>
                        </div>
                    @elseif ($studentExam && $studentExam->status === 'completed')
                        <div class="tt-center mb-2">
                            <a href="{{ route('student.exam.result', $studentExam->id) }}" class="btn btn-info">عرض النتيجة الكاملة للأختبار</a>
                        </div>
                    @endif

                    <h5 class="mt-3">النص</h5>
                    @include('typingtestmodule::partials.highlighted_text', ['evaluation' => $evaluation])
                </div>
            </div>
        </div>
    </div>
@endsection
