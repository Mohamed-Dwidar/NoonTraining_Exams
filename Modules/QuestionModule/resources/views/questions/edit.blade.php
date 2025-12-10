@extends('layoutmodule::admin.main')

@section('title', 'تعديل السؤال')

@section('content')
    <div class="container-fluid">

        @include('layoutmodule::admin.flash')

        <div class="row justify-content-center">
            <div class="col-lg-12 ">
                <div class="card p-3 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">
                            <i class="fas fa-edit ms-2"></i>
                            تعديل السؤال
                        </h4>
                    </div>
                    <div class="card-body p-4">

                        <form method="POST" action="{{ route(Auth::getDefaultDriver() . '.question.update') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $question->id }}">
                            <div class="row mb-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الفئة <span class="text-danger">*</span></label>
                                    <select name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror" required>
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

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">نوع السؤال <span
                                            class="text-danger">*</span></label>
                                    <select name="type"
                                        class="form-select question-type @error('type') is-invalid @enderror" required>
                                        <option value="mcq"
                                            {{ old('type', $question->type) == 'mcq' ? 'selected' : '' }}>اختيار من متعدد
                                        </option>
                                        <option value="true_false"
                                            {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>صح / خطأ
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">نص السؤال <span class="text-danger">*</span></label>
                                <textarea name="question_text" class="form-control @error('question_text') is-invalid @enderror" rows="4"
                                    placeholder="أدخل نص السؤال هنا..." required>{{ old('question_text', $question->question_text) }}</textarea>
                                @error('question_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3 options-container" id="mcq-options"
                                style="{{ $question->type == 'true_false' ? 'display: none;' : '' }}">
                                <label class="form-label fw-semibold">خيارات الإجابة <span
                                        class="text-danger">*</span></label>
                                <div class="row g-3">
                                    @foreach (['A', 'B', 'C', 'D'] as $option)
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text bg-light border-end-0 fw-bold">{{ $option }}</span>
                                                <input type="text" name="options[{{ $option }}]"
                                                    class="form-control border-start-0 @error('options.' . $option) is-invalid @enderror"
                                                    value="{{ old('options.' . $option, $question->options[$option] ?? '') }}"
                                                    placeholder="نص الخيار {{ $option }}">
                                                @error('options.' . $option)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <div id="mcq-answer" style="{{ $question->type == 'true_false' ? 'display: none;' : '' }}">
                                    <label class="form-label fw-semibold">الإجابة الصحيحة <span
                                            class="text-danger">*</span></label>
                                    <select name="answer" class="form-select @error('answer') is-invalid @enderror">
                                        <option value="">اختر الإجابة الصحيحة</option>
                                        @foreach (['A', 'B', 'C', 'D'] as $option)
                                            <option value="{{ $option }}"
                                                {{ old('answer', $question->answer) == $option ? 'selected' : '' }}>الخيار
                                                {{ $option }}</option>
                                        @endforeach
                                    </select>
                                    @error('answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="true-false-answer" style="{{ $question->type == 'mcq' ? 'display: none;' : '' }}">
                                    <label class="form-label fw-semibold">الإجابة الصحيحة <span
                                            class="text-danger">*</span></label>
                                    <select name="answer" class="form-select @error('answer') is-invalid @enderror">
                                        <option value="true"
                                            {{ old('answer', $question->answer) == 'true' ? 'selected' : '' }}>صح</option>
                                        <option value="false"
                                            {{ old('answer', $question->answer) == 'false' ? 'selected' : '' }}>خطأ
                                        </option>
                                    </select>
                                    @error('answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="d-flex justify-content-between pt-3 border-top">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning px-4">
                                        <i class="fas fa-save ms-2"></i> تحديث السؤال
                                    </button>
                                    <a href="{{ route(Auth::getDefaultDriver() . '.questions.index') }}"
                                        class="btn btn-outline-danger">
                                        <i class="fas fa-times ms-2"></i> إلغاء
                                    </a>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.querySelector('.question-type');
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
