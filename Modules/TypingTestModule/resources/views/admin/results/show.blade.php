@extends('layoutmodule::layouts.main')

@section('title')
    تفاصيل محاولة - أختبار سرعة الكتابة
@endsection

@section('content')
    @php $guard = Auth::getDefaultDriver(); @endphp
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-keyboard-o"></i> أختبار سرعة الكتابة —
                    <strong>{{ optional($attempt->student)->name }}</strong>
                </h3>
            </div>
        </div>

        @include('typingtestmodule::admin.partials.tabs')
        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-3 col-6"><strong>النوع:</strong>
                            {{ $attempt->isLinked() ? (optional(optional($attempt->studentExam)->exam)->title ?? 'دورة') : 'منفصل' }}
                        </div>
                        <div class="col-lg-3 col-6"><strong>الحالة:</strong>
                            @if ($attempt->status === 'completed')
                                {{ $attempt->passed ? 'ناجح' : 'راسب' }}
                            @elseif ($attempt->status === 'in_progress')
                                جارٍ
                            @else
                                لم يبدأ
                            @endif
                        </div>
                        <div class="col-lg-3 col-6"><strong>البداية:</strong> {{ optional($attempt->started_at)->format('Y-m-d H:i') ?? '-' }}</div>
                        <div class="col-lg-3 col-6"><strong>النهاية:</strong> {{ optional($attempt->completed_at)->format('Y-m-d H:i') ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 col-6"><strong>الكلمات الصحيحة:</strong> {{ $attempt->correct_words ?? '-' }} / {{ $attempt->word_count ?? '-' }}</div>
                        <div class="col-lg-3 col-6"><strong>الكلمات الخاطئة:</strong> {{ $attempt->wrong_words ?? '-' }}</div>
                        <div class="col-lg-3 col-6"><strong>الكلمات المكتوبة:</strong> {{ $attempt->typed_words }}</div>
                        <div class="col-lg-3 col-6"><strong>درجة النجاح:</strong> {{ $attempt->success_grade ?? '-' }} كلمة — <strong>المدة:</strong> {{ $attempt->duration_minutes ?? '-' }} دقيقة</div>
                    </div>

                    @if ($attempt->source_text)
                        <h5 class="mt-3">النص الأصلي</h5>
                        @include('typingtestmodule::partials.highlighted_text', ['evaluation' => $evaluation])

                        <h5 class="mt-3">ما كتبه الطالب</h5>
                        <div class="p-2 border rounded" style="white-space: pre-wrap; direction: rtl; font-size: 1.1rem;">{{ $attempt->typed_text ?: '-' }}</div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">رجوع</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
