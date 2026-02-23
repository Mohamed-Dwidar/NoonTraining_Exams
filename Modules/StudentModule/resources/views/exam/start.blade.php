@extends('layoutmodule::layouts.main')

@section('content')
<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>{{ $exam->title }}</h3>

        <!-- Timer Display -->
        <div class="alert alert-warning mb-0" id="examTimer">
            <i class="icon-clock"></i>
            <span id="timerDisplay">الوقت المتبقي: --:--</span>
        </div>
    </div>

    <!-- Exam Progress Bar -->
    <div class="progress mb-4" style="height: 25px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated"
             id="progressBar"
             role="progressbar"
             style="width: 0%;"
             aria-valuenow="0"
             aria-valuemin="0"
             aria-valuemax="100">
            سؤال 1 من {{ count($examQuestions) }}
        </div>
    </div>

    <!-- Questions Container - One question at a time -->
        <div id="questionsContainer">
            @foreach ($examQuestions as $index => $question)
                @php
                $q = $question->question;
                // print_r($q->id);
                // continue;
                @endphp

                <div class="question-card mb-3 p-3 border rounded @if($index > 0) d-none @endif"
                     data-question-index="{{ $index }}"
                     data-question-id="{{ $q->id }}"
                     data-student-exam-answer-id="{{ $question->id }}"
                     id="question-{{ $index }}"
                     style="@if($index > 0) display: none; @endif">

                    <!-- Question Number -->
                    <div class="mb-2 text-primary">
                        <strong>سؤال {{ $index + 1 }} من {{ count($examQuestions) }}</strong>
                    </div>

                    <strong>{{ $q->question_text }}</strong>

                    @php
                        // Check if options is already an array or needs decoding
                        $options = is_array($q->options) ? $q->options : json_decode($q->options, true);
                    @endphp

                    @if ($q->type === 'mcq')
                        @foreach ($options as $option)
                            <label class="d-block p-2 border rounded mb-2 question-option @if($question->answer == $option) bg-primary text-white @endif"
                                   style="cursor: pointer; transition: all 0.3s;">
                                <input type="radio"
                                       name="answers[{{ $q->id }}]"
                                       value="{{ $option }}"
                                       class="d-none"
                                       @if($question->answer == $option) checked @endif>
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    @else
                        <label class="d-block p-2 border rounded mb-2 question-option @if($question->answer == 'true') bg-primary text-white @endif"
                               style="cursor: pointer; transition: all 0.3s;">
                            <input type="radio"
                                   name="answers[{{ $q->id }}]"
                                   value="true"
                                   class="d-none"
                                   @if($question->answer == 'true') checked @endif>
                            <span>صح</span>
                        </label>
                        <label class="d-block p-2 border rounded mb-2 question-option @if($question->answer == 'false') bg-primary text-white @endif"
                               style="cursor: pointer; transition: all 0.3s;">
                            <input type="radio"
                                   name="answers[{{ $q->id }}]"
                                   value="false"
                                   class="d-none"
                                   @if($question->answer == 'false') checked @endif>
                            <span>خطأ</span>
                        </label>
                    @endif
                </div>
            @endforeach

        </div>

        <!-- Saving Indicator -->
        <div class="text-center mt-2" id="savingIndicator" style="display: none;">
            <small class="text-success">
                <i class="icon-check-circle"></i> <span id="savingText">جاري الحفظ...</span>
            </small>
        </div>

        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-between mt-4">
            <button type="button"
                    class="btn btn-secondary"
                    id="prevBtn"
                    onclick="showPreviousQuestion()"
                    disabled>
                <i class="icon-arrow-right"></i> السابق
            </button>

            <button type="button"
                    class="btn btn-primary"
                    id="nextBtn"
                    onclick="showNextQuestion()">
                التالي <i class="icon-arrow-left"></i>
            </button>

            <button type="button"
                    class="btn btn-success d-none"
                    id="submitBtn"
                    onclick="showSubmitModal()">
                إنهاء الاختبار <i class="icon-check"></i>
            </button>
        </div>
</div>

<!-- Warning Modal -->
<div class="modal fade" id="timeUpModal" tabindex="-1" role="dialog" aria-labelledby="timeUpModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="timeUpModalLabel">انتهى الوقت!</h5>
            </div>
            <div class="modal-body">
                <p>لقد انتهى وقت الاختبار. سيتم إرسال إجاباتك تلقائياً.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitExamNow()" id="timeUpSubmitBtn">موافق</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Submit Modal -->
<div class="modal fade" id="confirmSubmitModal" tabindex="-1" role="dialog" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="confirmSubmitModalLabel">تأكيد إنهاء الاختبار</h5>
            </div>
            <div class="modal-body">
                <p><strong>هل أنت متأكد من إنهاء الاختبار؟</strong></p>
                <p class="text-danger"><i class="icon-alert-triangle"></i> لا يمكنك العودة إلى الأسئلة بعد الإرسال.</p>
                <p class="text-muted">تمت الإجابة على <span id="answeredCount">0</span> من <span id="totalQuestionsCount">{{ count($examQuestions) }}</span> سؤال.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="submitExamNow()" id="confirmSubmitBtn">نعم، إنهاء الاختبار</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Exam variables
let currentQuestion = 0;
const totalQuestions = {{ count($examQuestions) }};
const studentExamId = {{ $studentExam->id }};
let examDuration = {{ $exam->duration_minutes }};
let timeLeft = examDuration * 60;
let timerInterval;
let answers = {};
let isSaving = false;
let examCompleted = false;

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Ensure only first question is visible
    for (let i = 0; i < totalQuestions; i++) {
        const questionEl = document.getElementById(`question-${i}`);
        if (questionEl) {
            if (i === 0) {
                questionEl.classList.remove('d-none');
                questionEl.style.display = 'block';
            } else {
                questionEl.classList.add('d-none');
                questionEl.style.display = 'none';
            }
        }
    }

    startTimer();
    updateNavigationButtons();
    updateProgressBar();
    initQuestionHandlers();
    loadSavedProgress();

    // Prevent accidental navigation
    window.addEventListener('beforeunload', function(e) {
        if (!examCompleted && timeLeft > 0) {
            e.preventDefault();
            e.returnValue = 'لديك اختبار قيد التقدم. هل أنت متأكد من المغادرة؟';
        }
    });
});

// Timer Functions
function startTimer() {
    console.log('Starting timer...');

    // Check for saved time from previous session
    const savedStartTime = localStorage.getItem('exam_start_time_' + {{ $exam->id }});
    const savedDuration = localStorage.getItem('exam_duration_' + {{ $exam->id }});

    if (savedStartTime && savedDuration) {
        const now = new Date().getTime();
        const elapsedSeconds = Math.floor((now - parseInt(savedStartTime)) / 1000);
        const totalSeconds = parseInt(savedDuration) * 60;
        timeLeft = Math.max(0, totalSeconds - elapsedSeconds);
    } else {
        // First time starting - save start time
        localStorage.setItem('exam_start_time_' + {{ $exam->id }}, new Date().getTime());
        localStorage.setItem('exam_duration_' + {{ $exam->id }}, examDuration);
    }

    updateTimerDisplay();

    // Start the interval
    timerInterval = setInterval(function() {
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timeUp();
            return;
        }

        timeLeft--;
        updateTimerDisplay();

        // Show warning when 5 minutes left
        if (timeLeft === 300) {
            showTimeWarning();
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    const display = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    document.getElementById('timerDisplay').textContent = `الوقت المتبقي: ${display}`;

    // Change color when time is running low
    const timerElement = document.getElementById('examTimer');
    if (timeLeft < 300) { // Less than 5 minutes
        timerElement.classList.remove('alert-warning');
        timerElement.classList.add('alert-danger');
    } else {
        timerElement.classList.remove('alert-danger');
        timerElement.classList.add('alert-warning');
    }
}

function showTimeWarning() {
    const timerAlert = document.getElementById('examTimer');
    timerAlert.innerHTML = '<i class="icon-alert-triangle"></i> <strong>تحذير!</strong> بقي 5 دقائق فقط!';

    // Flash effect
    let flashCount = 0;
    const flash = setInterval(() => {
        timerAlert.classList.toggle('alert-danger');
        flashCount++;
        if (flashCount > 6) {
            clearInterval(flash);
            timerAlert.classList.add('alert-danger');
        }
    }, 500);
}

function timeUp() {
    document.getElementById('timerDisplay').textContent = 'انتهى الوقت!';

    saveCurrentAnswer(() => {
        $('#timeUpModal').modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
    });
}

// Question Navigation Functions
function showNextQuestion() {
    if (currentQuestion < totalQuestions - 1 && !isSaving) {
        disableNavigationButtons();

        saveCurrentAnswer(() => {
            const currentQuestionEl = document.getElementById(`question-${currentQuestion}`);
            currentQuestionEl.classList.add('d-none');
            currentQuestionEl.style.display = 'none';

            currentQuestion++;
            const nextQuestionEl = document.getElementById(`question-${currentQuestion}`);
            nextQuestionEl.classList.remove('d-none');
            nextQuestionEl.style.display = 'block';

            updateProgressBar();
            updateNavigationButtons();
            enableNavigationButtons();
        });
    }
}

function showPreviousQuestion() {
    if (currentQuestion > 0 && !isSaving) {
        disableNavigationButtons();

        saveCurrentAnswer(() => {
            const currentQuestionEl = document.getElementById(`question-${currentQuestion}`);
            currentQuestionEl.classList.add('d-none');
            currentQuestionEl.style.display = 'none';

            currentQuestion--;
            const prevQuestionEl = document.getElementById(`question-${currentQuestion}`);
            prevQuestionEl.classList.remove('d-none');
            prevQuestionEl.style.display = 'block';

            updateProgressBar();
            updateNavigationButtons();
            enableNavigationButtons();
        });
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    prevBtn.disabled = currentQuestion === 0;

    if (currentQuestion === totalQuestions - 1) {
        nextBtn.classList.add('d-none');
        nextBtn.style.display = 'none';
        submitBtn.classList.remove('d-none');
        submitBtn.style.display = 'inline-block';
    } else {
        nextBtn.classList.remove('d-none');
        nextBtn.style.display = 'inline-block';
        submitBtn.classList.add('d-none');
        submitBtn.style.display = 'none';
    }
}

function disableNavigationButtons() {
    document.getElementById('prevBtn').disabled = true;
    document.getElementById('nextBtn').disabled = true;
    document.getElementById('submitBtn').disabled = true;
}

function enableNavigationButtons() {
    document.getElementById('prevBtn').disabled = currentQuestion === 0;
    document.getElementById('nextBtn').disabled = false;
    document.getElementById('submitBtn').disabled = false;
}

function updateProgressBar() {
    const progress = ((currentQuestion + 1) / totalQuestions) * 100;
    const progressBar = document.getElementById('progressBar');

    progressBar.style.width = `${progress}%`;
    progressBar.setAttribute('aria-valuenow', progress);
    progressBar.textContent = `سؤال ${currentQuestion + 1} من ${totalQuestions}`;
}

// Answer Management Functions
function initQuestionHandlers() {
    document.querySelectorAll('.question-option').forEach(option => {
        option.addEventListener('click', function() {
            const questionCard = this.closest('.question-card');
            const questionId = questionCard.dataset.questionId;
            const radio = this.querySelector('input[type="radio"]');

            // Unselect all options in this question
            questionCard.querySelectorAll('.question-option').forEach(opt => {
                opt.classList.remove('bg-primary', 'text-white');
            });

            // Select this option
            this.classList.add('bg-primary', 'text-white');
            radio.checked = true;

            // Store answer in memory (will be saved when navigating)
            answers[questionId] = radio.value;
        });
    });
}

// AJAX Save Functions
function saveCurrentAnswer(callback) {
    const questionCard = document.getElementById(`question-${currentQuestion}`);
    if (!questionCard) {
        if (callback) callback();
        return;
    }

    const questionId = questionCard.dataset.questionId;
    const answer = answers[questionId];

    if (!answer) {
        if (callback) callback();
        return;
    }

    if (isSaving) {
        setTimeout(() => saveCurrentAnswer(callback), 100);
        return;
    }

    isSaving = true;
    showSavingIndicator('جاري الحفظ...');

    fetch('{{ route("student.exam.submitAnswer") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            studentExamId: studentExamId,
            question_id: questionId,
            answer: answer
        })
    })
    .then(response => response.json())
    .then(data => {
        showSavingIndicator('تم حفظ الإجابة ✓', 'success');
        setTimeout(() => {
            hideSavingIndicator();
            isSaving = false;
            if (callback) callback();
        }, 500);
    })
    .catch(error => {
        showSavingIndicator('فشل الحفظ!', 'danger');
        setTimeout(() => {
            hideSavingIndicator();
            isSaving = false;
            if (callback) callback();
        }, 1500);
    });
}

// Show/Hide Saving Indicator
function showSavingIndicator(text, type = 'primary') {
    const indicator = document.getElementById('savingIndicator');
    const savingText = document.getElementById('savingText');
    const indicator_small = indicator.querySelector('small');

    savingText.textContent = text;
    indicator_small.className = `text-${type}`;
    indicator.style.display = 'block';
}

function hideSavingIndicator() {
    const indicator = document.getElementById('savingIndicator');
    indicator.style.display = 'none';
}

// Progress Management Functions
function loadSavedProgress() {
    // Load answers from the rendered page (already saved in DB)
    document.querySelectorAll('.question-card').forEach(questionCard => {
        const questionId = questionCard.dataset.questionId;
        const selectedOption = questionCard.querySelector('input[type="radio"]:checked');

        if (selectedOption) {
            answers[questionId] = selectedOption.value;
        }
    });
}

// Submit Functions
function showSubmitModal() {
    const answeredCount = Object.keys(answers).length;
    document.getElementById('answeredCount').textContent = answeredCount;
    document.getElementById('totalQuestionsCount').textContent = totalQuestions;

    $('#confirmSubmitModal').modal({
        backdrop: 'static',
        keyboard: false
    }).modal('show');
}

function submitExamNow() {
    if (examCompleted) {
        return; // Prevent double submission
    }

    examCompleted = true;

    // Disable all submit buttons and cancel button
    const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
    const timeUpSubmitBtn = document.getElementById('timeUpSubmitBtn');
    const cancelBtns = document.querySelectorAll('[data-dismiss="modal"]');

    if (confirmSubmitBtn) {
        confirmSubmitBtn.disabled = true;
        confirmSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> جاري الإرسال...';
    }

    if (timeUpSubmitBtn) {
        timeUpSubmitBtn.disabled = true;
        timeUpSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> جاري الإرسال...';
    }

    // Disable cancel buttons
    cancelBtns.forEach(btn => btn.disabled = true);

    // Save the current answer first, then redirect to complete exam
    saveCurrentAnswer(() => {
        // Clear timer
        clearInterval(timerInterval);

        // Clear localStorage
        localStorage.removeItem('exam_start_time_' + {{ $exam->id }});
        localStorage.removeItem('exam_duration_' + {{ $exam->id }});

        // Hide modals
        $('#confirmSubmitModal').modal('hide');
        $('#timeUpModal').modal('hide');

        // Redirect to complete exam route
        window.location.href = '{{ route("student.exam.complete", $studentExam->id) }}';
    });
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (examCompleted) return;

    // Right arrow for next (in RTL)
    if (e.key === 'ArrowRight') {
        e.preventDefault();
        if (currentQuestion < totalQuestions - 1) {
            showNextQuestion();
        }
    }
    // Left arrow for previous (in RTL)
    if (e.key === 'ArrowLeft') {
        e.preventDefault();
        if (currentQuestion > 0) {
            showPreviousQuestion();
        }
    }
    // Number keys 1-4 for selecting answers
    if (e.key >= '1' && e.key <= '4') {
        e.preventDefault();
        const questionCard = document.getElementById(`question-${currentQuestion}`);
        if (questionCard) {
            const options = questionCard.querySelectorAll('.question-option');
            const index = parseInt(e.key) - 1;
            if (options[index]) {
                options[index].click();
            }
        }
    }
    // Space for next (if not on an input)
    if (e.key === ' ' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
        if (currentQuestion < totalQuestions - 1) {
            showNextQuestion();
        }
    }
});
</script>

<style>
/* Ensure questions are properly hidden */
.question-card.d-none {
    display: none !important;
}

/* Saving Indicator Animation */
#savingIndicator {
    animation: slideDown 0.3s ease-out;
    font-weight: 500;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.question-option {
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 10px;
    padding: 12px;
    border: 2px solid #dee2e6;
    border-radius: 8px;
}

.question-option:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.question-option.bg-primary {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.question-card {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

#examTimer {
    font-size: 1.2rem;
    font-weight: bold;
    min-width: 220px;
    text-align: center;
}

.progress-bar {
    font-weight: bold;
    font-size: 1rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card {
        padding: 1rem !important;
    }

    #examTimer {
        min-width: 180px;
        font-size: 1rem;
    }

    .question-option {
        padding: 10px;
    }
}
</style>
@endpush
@endsection
