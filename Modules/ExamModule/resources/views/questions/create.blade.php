@extends('layoutmodule::admin.main')

@section('title', 'الأسئلة - ' . $exam->title)

@section('content')
<div class="container-fluid">

    @include('layoutmodule::admin.flash')

    <h3 class="mb-4">الأسئلة الخاصة بالامتحان: <span class="text-primary">{{ $exam->title }}</span></h3>

    <div class="card shadow-sm p-4 mb-4">
        <div class="card-body">
            <h4 class="mb-4 text-success">إضافة أسئلة جديدة</h4>
            <form method="POST" action="{{ route('question.store') }}">
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
                            $answer = old("questions.$index.answer", $question['answer']['correct_answer'] ?? '');
                            $options = old("questions.$index.options", $question['options'] ?? []);
                        @endphp

                        <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $questionId }}">
                        <div class="question-row border rounded p-3 mb-3 shadow-sm bg-light">
                            <div class="row align-items-end">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">نوع السؤال</label>
                                    <select name="questions[{{ $index }}][type]" class="form-control question-type">
                                        <option value="mcq" {{ $type === 'mcq' ? 'selected' : '' }}>اختيار من متعدد</option>
                                        <option value="true_false" {{ $type === 'true_false' ? 'selected' : '' }}>صح / خطأ</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">نص السؤال</label>
                                    <input type="text" name="questions[{{ $index }}][question_text]" class="form-control" value="{{ $questionText }}">
                                </div>
                                <div class="col-md-4 mb-3 answer-container">
                                    @if($type === 'mcq')
                                        <label class="form-label">الإجابة الصحيحة</label>
                                        <input type="text" name="questions[{{ $index }}][answer]" class="form-control" value="{{ $answer }}">
                                    @else
                                        <label class="form-label">صح / خطأ</label>
                                        <select name="questions[{{ $index }}][answer]" class="form-control">
                                            <option value="true" {{ $answer === 'true' ? 'selected' : '' }}>صح</option>
                                            <option value="false" {{ $answer === 'false' ? 'selected' : '' }}>خطأ</option>
                                        </select>
                                    @endif
                                </div>
                            </div>

                            @if($type === 'mcq')
                                <div class="row mt-2 options-container">
                                    @foreach(['A','B','C','D'] as $key)
                                        <div class="col-md-3 mb-2">
                                            <input type="text" name="questions[{{ $index }}][options][{{ $key }}]" class="form-control" placeholder="خيار {{ $key }}" value="{{ $options[$key] ?? '' }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" id="add-question" class="btn btn-outline-secondary fw-bold">+ إضافة سؤال آخر</button>
                    <button type="submit" class="btn btn-primary fw-bold">حفظ الأسئلة</button>
                </div>

            </form>
        </div>
    </div>

</div>

<style>
.question-row { transition: all 0.3s ease-in-out; }
.question-row:hover { background-color: #f8f9fa; transform: scale(1.01); }
.question-row label { font-weight: 600; }
.answer-container select, .answer-container input { min-height: 42px; }
#add-question { transition: all 0.2s ease; }
#add-question:hover { transform: translateY(-2px); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionIndex = {{ count(old('questions', $exam->questions)) }};

    const container = document.getElementById('questions-container');

    document.getElementById('add-question').addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('question-row','border','rounded','p-3','mb-3','shadow-sm','bg-light');

        newRow.innerHTML = `
        <div class="row align-items-end">
            <div class="col-md-4 mb-3">
                <label class="form-label">نوع السؤال</label>
                <select name="questions[${questionIndex}][type]" class="form-control question-type">
                    <option value="mcq">اختيار من متعدد</option>
                    <option value="true_false">صح / خطأ</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">نص السؤال</label>
                <input type="text" name="questions[${questionIndex}][question_text]" class="form-control">
            </div>
            <div class="col-md-4 mb-3 answer-container">
                <label class="form-label">الإجابة</label>
                <input type="text" name="questions[${questionIndex}][answer]" class="form-control">
            </div>
        </div>
        <div class="row mt-2 options-container">
            <div class="col-md-3 mb-2"><input type="text" name="questions[${questionIndex}][options][A]" class="form-control" placeholder="خيار A"></div>
            <div class="col-md-3 mb-2"><input type="text" name="questions[${questionIndex}][options][B]" class="form-control" placeholder="خيار B"></div>
            <div class="col-md-3 mb-2"><input type="text" name="questions[${questionIndex}][options][C]" class="form-control" placeholder="خيار C"></div>
            <div class="col-md-3 mb-2"><input type="text" name="questions[${questionIndex}][options][D]" class="form-control" placeholder="خيار D"></div>
        </div>
        `;

        container.appendChild(newRow);
        questionIndex++;
        attachTypeChangeListeners();
    });

    function attachTypeChangeListeners() {
        document.querySelectorAll('.question-type').forEach(select => {
            select.removeEventListener('change', handleTypeChange);
            select.addEventListener('change', handleTypeChange);
        });
    }

    function handleTypeChange() {
        const row = this.closest('.question-row');
        const answerContainer = row.querySelector('.answer-container');
        const optionsContainer = row.querySelector('.options-container');

        if(this.value === 'mcq'){
            optionsContainer.innerHTML = '';
            ['A','B','C','D'].forEach(key=>{
                optionsContainer.innerHTML += `<div class="col-md-3 mb-2"><input type="text" class="form-control" name="${this.name.replace('[type]','[options]['+key+']')}" placeholder="خيار ${key}"></div>`;
            });
            answerContainer.innerHTML = `<label class="form-label">الإجابة الصحيحة</label><input type="text" name="${this.name.replace('[type]','[answer]')}" class="form-control">`;
        } else {
            optionsContainer.innerHTML = '';
            answerContainer.innerHTML = `<label class="form-label">صح / خطأ</label><select name="${this.name.replace('[type]','[answer]')}" class="form-control"><option value="true">صح</option><option value="false">خطأ</option></select>`;
        }
    }

    attachTypeChangeListeners();
});
</script>
@endsection
