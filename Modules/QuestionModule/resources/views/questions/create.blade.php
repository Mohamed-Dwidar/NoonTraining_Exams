@extends('layoutmodule::admin.main')

@section('title', 'الأسئلة ')

@section('content')
    <div class="container-fluid">

        @include('layoutmodule::admin.flash')

        <h3 class="mb-4">بنك الاسئله : </h3>

        <div class="card shadow-sm p-4 mb-4">
            <div class="card-body p-3">
                <h4 class="mb-4 text-success">إضافة أسئلة جديدة</h4>

                <form method="POST" action="{{ route(Auth::getDefaultDriver() . '.question.store') }}" id="questions-form">
                    @csrf

                    <div id="questions-container">
                        @foreach ($questions as $index => $question)
                            @php
                                $questionId = $question['id'] ?? null;
                                $type = old("questions.$index.type", $question['type'] ?? 'mcq');
                                $questionText = old("questions.$index.question_text", $question['question_text'] ?? '');
                                $categoryId = old("questions.$index.category_id", $question['category_id'] ?? '');
                                $answer = old(
                                    "questions.$index.answer",
                                    $question['answer']['correct_answer'] ?? ($question['answer'] ?? ''),
                                );
                                $options = old("questions.$index.options", $question['options'] ?? []);
                            @endphp

                            <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $questionId }}">

                            <div class="question-row card rounded p-3 mb-3 shadow-sm border-0"
                                data-index="{{ $index }}">
                                <div class="card-body">
                                    <div class="row align-items-end">
                                        <!-- الفئة -->
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-semibold">الفئة <span class="text-danger">*</span></label>
                                            <select name="questions[{{ $index }}][category_id]" class="form-select" required>
                                                <option value="">اختر الفئة</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">يرجى اختيار الفئة</div>
                                        </div>

                                        <!-- نوع السؤال -->
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-semibold">نوع السؤال</label>
                                            <select name="questions[{{ $index }}][type]"
                                                class="form-select question-type">
                                                <option value="mcq" {{ $type === 'mcq' ? 'selected' : '' }}>اختيار من متعدد</option>
                                                <option value="true_false" {{ $type === 'true_false' ? 'selected' : '' }}>صح / خطأ</option>
                                            </select>
                                        </div>

                                        <!-- نص السؤال -->
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-semibold">نص السؤال <span class="text-danger">*</span></label>
                                            <input type="text" name="questions[{{ $index }}][question_text]"
                                                class="form-control question-text" value="{{ $questionText }}" required>
                                            <div class="invalid-feedback">يرجى إدخال نص السؤال</div>
                                        </div>

                                        <!-- الإجابة -->
                                        <div class="col-md-2 mb-3 answer-container">
                                            @if ($type === 'mcq')
                                                <label class="form-label fw-semibold">الإجابة الصحيحة <span class="text-danger">*</span></label>
                                                <select name="questions[{{ $index }}][answer]" class="form-select" required>
                                                    <option value="">اختر الإجابة</option>
                                                    <option value="A" {{ $answer === 'A' ? 'selected' : '' }}>A</option>
                                                    <option value="B" {{ $answer === 'B' ? 'selected' : '' }}>B</option>
                                                    <option value="C" {{ $answer === 'C' ? 'selected' : '' }}>C</option>
                                                    <option value="D" {{ $answer === 'D' ? 'selected' : '' }}>D</option>
                                                </select>
                                                <div class="invalid-feedback">يرجى اختيار الإجابة الصحيحة</div>
                                            @else
                                                <label class="form-label fw-semibold">صح / خطأ <span class="text-danger">*</span></label>
                                                <select name="questions[{{ $index }}][answer]" class="form-select" required>
                                                    <option value="true" {{ $answer === 'true' ? 'selected' : '' }}>صح</option>
                                                    <option value="false" {{ $answer === 'false' ? 'selected' : '' }}>خطأ</option>
                                                </select>
                                                <div class="invalid-feedback">يرجى اختيار الإجابة الصحيحة</div>
                                            @endif
                                        </div>

                                        <!-- زر الحذف -->
                                        <div class="col-md-1 mb-3">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-question"
                                                title="حذف السؤال">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- خيارات MCQ -->
                                    @if ($type === 'mcq')
                                        <div class="row mt-3 options-container">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold mb-2">خيارات الإجابة <span class="text-danger">*</span></label>
                                            </div>

                                            @foreach (['A', 'B', 'C', 'D'] as $key)
                                                <div class="col-md-3 mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light border-end-0">{{ $key }}</span>
                                                        <input type="text"
                                                            name="questions[{{ $index }}][options][{{ $key }}]"
                                                            class="form-control border-start-0 option-input"
                                                            value="{{ $options[$key] ?? '' }}" placeholder="نص الخيار"
                                                            required>
                                                        <div class="invalid-feedback">يرجى إدخال نص الخيار</div>
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
                        <div>
                            <button type="button" id="add-question" class="btn btn-outline-primary fw-bold">
                                <i class="fas fa-plus ms-2"></i> إضافة سؤال آخر
                            </button>
                            <span class="text-muted ms-3" id="questions-count">عدد الأسئلة: {{ count($questions) }}</span>
                        </div>

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
            position: relative;
        }

        .question-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 12px;
        }

        .options-container {
            background: #ffffff;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e3e6f0;
        }

        .remove-question {
            border-radius: 6px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .question-counter {
            background: #4e73df;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .is-invalid {
            border-color: #e74a3b;
        }

        .invalid-feedback {
            display: none;
            font-size: 0.875rem;
        }

        .was-validated .form-control:invalid~.invalid-feedback,
        .was-validated .form-select:invalid~.invalid-feedback {
            display: block;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questionIndex = {{ count(old('questions', $questions)) }};
            const container = document.getElementById('questions-container');
            const questionsCount = document.getElementById('questions-count');

            // تحديث عداد الأسئلة
            function updateQuestionsCount() {
                const count = document.querySelectorAll('.question-row').length;
                questionsCount.textContent = `عدد الأسئلة: ${count}`;
            }

            // إضافة سؤال جديد
            document.getElementById('add-question').addEventListener('click', function() {
                const html = `
                <div class="question-row card rounded p-3 mb-3 shadow-sm border-0" data-index="${questionIndex}">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <!-- الفئة -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">الفئة <span class="text-danger">*</span></label>
                                <select name="questions[${questionIndex}][category_id]" class="form-select" required>
                                    <option value="">اختر الفئة</option>
                                    ${Array.from(document.querySelectorAll('select[name^="questions"][name$="[category_id]"] option')).map(option => 
                                        option.outerHTML
                                    ).join('').replace(/selected/g, '')}
                                </select>
                                <div class="invalid-feedback">يرجى اختيار الفئة</div>
                            </div>

                            <!-- نوع السؤال -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">نوع السؤال</label>
                                <select name="questions[${questionIndex}][type]" class="form-select question-type">
                                    <option value="mcq">اختيار من متعدد</option>
                                    <option value="true_false">صح / خطأ</option>
                                </select>
                            </div>

                            <!-- نص السؤال -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">نص السؤال <span class="text-danger">*</span></label>
                                <input type="text" name="questions[${questionIndex}][question_text]" class="form-control question-text" required>
                                <div class="invalid-feedback">يرجى إدخال نص السؤال</div>
                            </div>

                            <!-- الإجابة -->
                            <div class="col-md-2 mb-3 answer-container">
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

                            <!-- زر الحذف -->
                            <div class="col-md-1 mb-3">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-question" title="حذف السؤال">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <!-- خيارات MCQ -->
                        <div class="row mt-3 options-container">
                            <div class="col-12">
                                <label class="form-label fw-semibold mb-2">خيارات الإجابة <span class="text-danger">*</span></label>
                            </div>

                            ${['A','B','C','D'].map(key => `
                                <div class="col-md-3 mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">${key}</span>
                                        <input type="text" 
                                               name="questions[${questionIndex}][options][${key}]"
                                               class="form-control border-start-0 option-input" 
                                               placeholder="نص الخيار"
                                               required>
                                        <div class="invalid-feedback">يرجى إدخال نص الخيار</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
                `;

                container.insertAdjacentHTML('beforeend', html);
                questionIndex++;
                updateQuestionsCount();
                attachEventListeners();
            });

            // تغيير نوع السؤال
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
                                    <input type="text"
                                           class="form-control border-start-0 option-input"
                                           name="${select.name.replace('[type]', '[options]['+key+']')}"
                                           placeholder="نص الخيار"
                                           required>
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

            // حذف سؤال
            function handleRemoveQuestion(button) {
                const row = button.closest('.question-row');
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    updateQuestionsCount();
                    reindexQuestions();
                }, 300);
            }

            // إعادة ترقيم الأسئلة
            function reindexQuestions() {
                document.querySelectorAll('.question-row').forEach((row, index) => {
                    row.setAttribute('data-index', index);
                });
            }

            // إرفاق مستمعي الأحداث
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

            // معالجات الأحداث
            function typeChangeHandler() {
                handleTypeChange(this);
            }

            function removeQuestionHandler() {
                handleRemoveQuestion(this);
            }

            // التحقق من الصحة قبل الإرسال
            document.getElementById('questions-form').addEventListener('submit', function(e) {
                const form = this;
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            // التهيئة الأولية
            updateQuestionsCount();
            attachEventListeners();
        });
    </script>

@endsection