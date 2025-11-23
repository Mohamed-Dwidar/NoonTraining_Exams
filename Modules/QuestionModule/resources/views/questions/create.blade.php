@extends('layoutmodule::admin.main')

@section('title', 'الأسئلة - ' . $exam->title)

@section('content')
    <div class="container-fluid">

        @include('layoutmodule::admin.flash')

        <h3 class="mb-4">بنك الاسئله : <span class="text-primary">{{ $exam->title }}</span></h3>

        <div class="card shadow-sm p-4 mb-4">
            <div class="card-body p-3">
                <h4 class="mb-4 text-success">إضافة أسئلة جديدة</h4>

                <form method="POST" action="{{ route(Auth::getDefaultDriver() . '.exam.question.store') }}">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                    <div id="questions-container">
                        @php
                            $questions = old('questions', $exam->questions->toArray());
                        @endphp

                        @foreach ($questions as $index => $question)
                            @php
                                $questionId = $question['id'] ?? null;
                                $type = old("questions.$index.type", $question['type'] ?? 'mcq');
                                $questionText = old("questions.$index.question_text", $question['question_text'] ?? '');
                                $answer = old("questions.$index.answer", $question['answer']['correct_answer'] ?? $question['answer'] ?? '');
                                $options = old("questions.$index.options", $question['options'] ?? []);
                            @endphp

                            <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $questionId }}">

                            <div class="question-row card rounded p-3 mb-3 shadow-sm border-0">
                                <div class="card-body">

                                    <div class="row align-items-end">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">نوع السؤال</label>
                                            <select name="questions[{{ $index }}][type]" class="form-select question-type">
                                                <option value="mcq" {{ $type === 'mcq' ? 'selected' : '' }}>اختيار من متعدد</option>
                                                <option value="true_false" {{ $type === 'true_false' ? 'selected' : '' }}>صح / خطأ</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">نص السؤال</label>
                                            <input type="text" name="questions[{{ $index }}][question_text]" class="form-control"
                                                   value="{{ $questionText }}">
                                        </div>

                                        <div class="col-md-4 mb-3 answer-container">
                                            @if ($type === 'mcq')
                                                <label class="form-label fw-semibold">الإجابة الصحيحة</label>
                                                <input type="text" name="questions[{{ $index }}][answer]" class="form-control"
                                                       value="{{ $answer }}">
                                            @else
                                                <label class="form-label fw-semibold">صح / خطأ</label>
                                                <select name="questions[{{ $index }}][answer]" class="form-select">
                                                    <option value="true" {{ $answer === 'true' ? 'selected' : '' }}>صح</option>
                                                    <option value="false" {{ $answer === 'false' ? 'selected' : '' }}>خطأ</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($type === 'mcq')
                                        <div class="row mt-3 options-container">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold mb-2">خيارات الإجابة</label>
                                            </div>

                                            @foreach (['A','B','C','D'] as $key)
                                                <div class="col-md-3 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-end-0">{{ $key }}</span>
                                                        <input type="text"
                                                               name="questions[{{ $index }}][options][{{ $key }}]"
                                                               class="form-control border-start-0"
                                                               value="{{ $options[$key] ?? '' }}"
                                                               placeholder="نص الخيار">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="options-container"></div>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <button type="button" id="add-question" class="btn btn-outline-primary fw-bold">
                            <i class="fas fa-plus ms-2"></i> إضافة سؤال آخر
                        </button>

                        <button type="submit" class="btn btn-success fw-bold px-4">
                            <i class="fas fa-save ms-2"></i> حفظ الأسئلة
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

<style>
    .question-row {
        background: #f8f9fc;
        border-left: 4px solid #4e73df;
        border-radius: 12px;
        transition: .25s ease;
    }

    .question-row:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 12px;
    }

    .options-container {
        background: #ffffff;
        padding: 15px;
        border-radius: 10px;
    }

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let questionIndex = {{ count(old('questions', $exam->questions)) }};
    const container = document.getElementById('questions-container');

    document.getElementById('add-question').addEventListener('click', function () {

        const html = `
        <div class="question-row card rounded p-3 mb-3 shadow-sm border-0">
            <div class="card-body">

                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">نوع السؤال</label>
                        <select name="questions[${questionIndex}][type]" class="form-select question-type">
                            <option value="mcq">اختيار من متعدد</option>
                            <option value="true_false">صح / خطأ</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">نص السؤال</label>
                        <input type="text" name="questions[${questionIndex}][question_text]" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3 answer-container">
                        <label class="form-label fw-semibold">الإجابة الصحيحة</label>
                        <input type="text" name="questions[${questionIndex}][answer]" class="form-control">
                    </div>
                </div>

                <div class="row mt-3 options-container">
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2">خيارات الإجابة</label>
                    </div>

                    ${['A','B','C','D'].map(key => `
                        <div class="col-md-3 mb-2">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">${key}</span>
                                <input type="text" name="questions[${questionIndex}][options][${key}]"
                                       class="form-control border-start-0" placeholder="نص الخيار">
                            </div>
                        </div>
                    `).join('')}
                </div>

            </div>
        </div>
        `;

        container.insertAdjacentHTML('beforeend', html);
        questionIndex++;
        attachTypeListener();
    });

    function attachTypeListener() {
        document.querySelectorAll('.question-type').forEach(select => {
            select.onchange = function () {
                const row = this.closest('.question-row');
                const answer = row.querySelector('.answer-container');
                const options = row.querySelector('.options-container');

                if (this.value === 'mcq') {

                    answer.innerHTML = `
                        <label class="form-label fw-semibold">الإجابة الصحيحة</label>
                        <input type="text" name="${this.name.replace('[type]', '[answer]')}" class="form-control">
                    `;

                    options.innerHTML = `
                        <div class="col-12">
                            <label class="form-label fw-semibold mb-2">خيارات الإجابة</label>
                        </div>
                        ${['A','B','C','D'].map(key => `
                            <div class="col-md-3 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">${key}</span>
                                    <input type="text"
                                           class="form-control border-start-0"
                                           name="${this.name.replace('[type]', '[options]['+key+']')}"
                                           placeholder="نص الخيار">
                                </div>
                            </div>
                        `).join('')}
                    `;

                } else {

                    options.innerHTML = ``;

                    answer.innerHTML = `
                        <label class="form-label fw-semibold">صح / خطأ</label>
                        <select name="${this.name.replace('[type]', '[answer]')}" class="form-select">
                            <option value="true">صح</option>
                            <option value="false">خطأ</option>
                        </select>
                    `;
                }
            };
        });
    }

    attachTypeListener();

});
</script>

@endsection
