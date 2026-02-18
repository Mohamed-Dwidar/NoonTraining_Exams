@extends('layoutmodule::admin.main')

@section('title', 'أسئلة الأختبار - ' . $exam->title)

@section('content')
<div class="container-fluid">
    <h3 class="mb-3">أسئلة الأختبار: {{ $exam->title }}</h3>

    <div class="card shadow-sm p-3 mb-3">
        <div class="card-body">
            @foreach($exam->questions as $question)
                <div class="mb-3 border rounded p-3 bg-light shadow-sm">
                    <h5>{{ $loop->iteration }}. {{ $question->question_text }}</h5>
                    <p><strong>النوع:</strong> {{ $question->type === 'mcq' ? 'اختيار من متعدد' : 'صح / خطأ' }}</p>

                    @if($question->type === 'mcq')
                        <ul>
                            @foreach($question->options as $key => $option)
                                <li @if($key === $question->answer->correct_answer) class="text-success fw-bold" @endif>
                                    {{ $key }}: {{ $option }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p><strong>الإجابة:</strong> {{ $question->answer->correct_answer === 'true' ? 'صح' : 'خطأ' }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <a href="{{ route('admin.exam.index') }}" class="btn btn-secondary">رجوع</a>
</div>
@endsection
