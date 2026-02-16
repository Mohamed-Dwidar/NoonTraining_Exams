@extends('layoutmodule::admin.main')

@section('title')
    تعديل السؤال
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-edit"></i>
                    تعديل السؤال
                </h3>
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
                                        action="{{ route(Auth::getDefaultDriver() . '.question.update') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $question->id }}">

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="type">نوع السؤال
                                                    &nbsp; : &nbsp;
                                                    <b>{{ $question->type == 'mcq' ? 'اختيار من متعدد' : 'صح أو خطأ' }}</b>
                                                    <input type="hidden" name="type" value="{{ $question->type }}">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="category_id">الفئة</label>
                                                <div class="form-group">
                                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                                        id="category_id" name="category_id">
                                                        <option value="">اختر الفئة</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ old('category_id', $question->category_id) == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-5 col-sm-12 col-xs-12 col-12">
                                                <label for="question_text">نص السؤال</label>
                                                <div class="form-group">
                                                    <textarea class="form-control @error('question_text') is-invalid @enderror"
                                                        id="question_text" name="question_text" rows="4">{{ old('question_text', $question->question_text) }}</textarea>
                                                    @error('question_text')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                         @if($question->type == 'mcq')
                                        <div class="options-container" id="mcq-options">
                                            <div class="row">
                                                @foreach (['A', 'B', 'C', 'D'] as $o=>$option)
                                                    <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                                        <label for="option_{{ $option }}">الخيار {{ $option }}</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control @error('options.' . $option) is-invalid @enderror"
                                                                id="option_{{ $option }}" name="options[{{ $option }}]"
                                                                value="{{ old('options.' . $option, $question->options[$o] ?? '') }}">
                                                            @error('options.' . $option)
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                @if($question->type == 'mcq')
                                                <div id="mcq-answer">
                                                    <label for="answer_mcq">الإجابة الصحيحة</label>
                                                    <div class="form-group">
                                                        <select class="form-control @error('answer') is-invalid @enderror" id="answer_mcq" name="answer">
                                                            <option value="">اختر الإجابة الصحيحة</option>
                                                            @foreach (['A', 'B', 'C', 'D'] as $o=>$option)
                                                                <option value="{{ $option }}" {{ old('answer', $question->answer->correct_answer) == $question->options[$o] ? 'selected' : '' }}>الخيار {{ $option }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('answer')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                @elseif ($question->type == 'true_false')
                                                <div id="true-false-answer">
                                                    <label for="answer_tf">الإجابة الصحيحة</label>
                                                    <div class="form-group">
                                                        <select class="form-control @error('answer') is-invalid @enderror" id="answer_tf" name="answer">
                                                            <option value="">اختر الإجابة الصحيحة</option>
                                                            <option value="true" {{ old('answer', $question->answer->correct_answer) == 'true' ? 'selected' : '' }}>صح</option>
                                                            <option value="false" {{ old('answer', $question->answer->correct_answer) == 'false' ? 'selected' : '' }}>خطأ</option>
                                                        </select>
                                                        @error('answer')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">تحديث</button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const mcqOptions = document.getElementById('mcq-options');
            const mcqAnswer = document.getElementById('mcq-answer');
            const trueFalseAnswer = document.getElementById('true-false-answer');

            typeSelect.addEventListener('change', function() {
                if (this.value === 'mcq') {
                    mcqOptions.style.display = 'block';
                    mcqAnswer.style.display = 'block';
                    trueFalseAnswer.style.display = 'none';
                } else {
                    mcqOptions.style.display = 'none';
                    mcqAnswer.style.display = 'none';
                    trueFalseAnswer.style.display = 'block';
                }
            });
        });
    </script>
@endsection
