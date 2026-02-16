@extends('layoutmodule::admin.main')

@section('title')
    إضافة أسئلة جديدة
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-plus-circle"></i>
                    بنك الأسئلة - إضافة أسئلة جديدة
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
                                        action="{{ route(Auth::getDefaultDriver() . '.question.store') }}"
                                        enctype="multipart/form-data" id="questions-form">
                                        @csrf

                                        <div id="questions-container"></div>

                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" id="add-question" class="btn btn-outline-primary fw-bold">
                                                    <i class="fa fa-plus ms-2"></i>
                                                    إضافة سؤال جديد
                                                </button>
                                                <span class="text-muted ms-3" id="questions-count">عدد الأسئلة: 0</span>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                                <i class="fa fa-save ms-2"></i> حفظ الأسئلة
                                            </button>
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

<style>
    .question-row {
        background: #f8f9fc;
        border-left: 4px solid #4e73df;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
        transition: 0.25s ease;
        position: relative;
    }
    .question-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 12px;
    }
    .options-container {
        background: #ffffff;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #e3e6f0;
        margin-top: 10px;
    }
    .remove-question {
        border-radius: 6px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .is-invalid { border-color: #e74a3b; }
    .invalid-feedback { display: none; font-size: 0.875rem; }
    .was-validated .form-control:invalid ~ .invalid-feedback,
    .was-validated .form-select:invalid ~ .invalid-feedback { display: block; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionIndex = 0;
    const container = document.getElementById('questions-container');
    const questionsCount = document.getElementById('questions-count');

    function updateQuestionsCount() {
        questionsCount.textContent = `عدد الأسئلة: ${container.querySelectorAll('.question-row').length}`;
    }

    function addQuestion() {
        const html = `
        <div class="question-row" data-index="${questionIndex}">
            <div class="row align-items-end">
                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">الفئة <span class="text-danger">*</span></label>
                    <select name="questions[${questionIndex}][category_id]" class="form-select" required>
                        <option value="">اختر الفئة</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">يرجى اختيار الفئة</div>
                </div>

                <div class="col-md-2 mb-2">
                    <label class="form-label fw-semibold">نوع السؤال<span class="text-danger">*</span></label>
                    <select name="questions[${questionIndex}][type]" class="form-select question-type">
                        <option value="mcq">اختيار من متعدد</option>
                        <option value="true_false">صح / خطأ</option>
                    </select>
                </div>

                <div class="col-md-4 mb-2">
                    <label class="form-label fw-semibold">نص السؤال <span class="text-danger">*</span></label>
                    <input type="text" name="questions[${questionIndex}][question_text]" class="form-control question-text" required>
                    <div class="invalid-feedback">يرجى إدخال نص السؤال</div>
                </div>

                <div class="col-md-2 mb-2 answer-container">
                    <label class="form-label fw-semibold">الإجابة الصحيحة <span class="text-danger">*</span></label>
                    <select name="questions[${questionIndex}][answer]" class="form-select" required>
                        <option value="">اختر الإجابة</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                    <div class="invalid-feedback">يرجى اختيار الإجابة الصحيحة</div>
                </div>

                <div class="col-md-1 mb-3">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-question" title="حذف السؤال">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="row mt-1 options-container">
                <div class="col-12">
                    <label class="form-label fw-semibold mb-2">خيارات الإجابة <span class="text-danger">*</span></label>
                </div>
                ${['A','B','C','D'].map(key => `
                    <div class="col-md-3 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">${key}</span>
                            <input type="text" name="questions[${questionIndex}][options][${key}]" class="form-control border-start-0 option-input" placeholder="نص الخيار" required>
                            <div class="invalid-feedback">يرجى إدخال نص الخيار</div>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        attachEventListeners();
        questionIndex++;
        updateQuestionsCount();
    }

    function handleTypeChange(select) {
        const row = select.closest('.question-row');
        const answerContainer = row.querySelector('.answer-container');
        const optionsContainer = row.querySelector('.options-container');

        if (select.value === 'mcq') {
            answerContainer.innerHTML = `
                <label class="form-label fw-semibold">الإجابة الصحيحة <span class="text-danger">*</span></label>
                <select name="${select.name.replace('[type]', '[answer]')}" class="form-select" required>
                    <option value="">اختر الإجابة</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
                <div class="invalid-feedback">يرجى اختيار الإجابة الصحيحة</div>
            `;
            optionsContainer.innerHTML = `
                <div class="col-12">
                    <label class="form-label fw-semibold mb-2">خيارات الإجابة <span class="text-danger">*</span></label>
                </div>
                ${['A','B','C','D'].map(key => `
                    <div class="col-md-3 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">${key}</span>
                            <input type="text" name="${select.name.replace('[type]', '[options]['+key+']')}" class="form-control border-start-0 option-input" placeholder="نص الخيار" required>
                            <div class="invalid-feedback">يرجى إدخال نص الخيار</div>
                        </div>
                    </div>
                `).join('')}
            `;
        } else {
            answerContainer.innerHTML = `
                <label class="form-label fw-semibold">صح / خطأ <span class="text-danger">*</span></label>
                <select name="${select.name.replace('[type]', '[answer]')}" class="form-select" required>
                    <option value="true">صح</option>
                    <option value="false">خطأ</option>
                </select>
                <div class="invalid-feedback">يرجى اختيار الإجابة الصحيحة</div>
            `;
            optionsContainer.innerHTML = '';
        }
    }

    function removeQuestion(button) {
        const row = button.closest('.question-row');
        row.remove();
        updateQuestionsCount();
    }

    function attachEventListeners() {
        document.querySelectorAll('.question-type').forEach(select => {
            select.removeEventListener('change', typeChangeHandler);
            select.addEventListener('change', typeChangeHandler);
        });
        document.querySelectorAll('.remove-question').forEach(button => {
            button.removeEventListener('click', removeQuestionHandler);
            button.addEventListener('click', removeQuestionHandler);
        });
    }

    function typeChangeHandler() { handleTypeChange(this); }
    function removeQuestionHandler() { removeQuestion(this); }

    document.getElementById('add-question').addEventListener('click', addQuestion);

    document.getElementById('questions-form').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
});
</script>
@endsection
