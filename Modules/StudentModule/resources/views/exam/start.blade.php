@extends('layoutmodule::admin.main')

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
            سؤال 1 من {{ count($questions) }}
        </div>
    </div>

    <form method="POST" action="{{ route('student.exam.submit', $exam->id) }}" id="examForm">
        @csrf
        
        <!-- Hidden input to store all answers -->
        <input type="hidden" name="all_answers" id="allAnswers" value="">

        <!-- Questions Container - One question at a time -->
        <div id="questionsContainer">
            @foreach ($questions as $index => $q)
                <div class="question-card mb-3 p-3 border rounded @if($index > 0) d-none @endif" 
                     data-question-index="{{ $index }}" 
                     data-question-id="{{ $q->id }}"
                     id="question-{{ $index }}">
                    
                    <!-- Question Number -->
                    <div class="mb-2 text-primary">
                        <strong>سؤال {{ $index + 1 }} من {{ count($questions) }}</strong>
                    </div>
                    
                    <strong>{{ $q->question_text }}</strong>
                    
                    @php
                        // Check if options is already an array or needs decoding
                        $options = is_array($q->options) ? $q->options : json_decode($q->options, true);
                    @endphp

                    @if ($q->type === 'mcq')
                        @foreach ($options as $option)
                            <label class="d-block p-2 border rounded mb-2 question-option" 
                                   style="cursor: pointer; transition: all 0.3s;">
                                <input type="radio" 
                                       name="answers[{{ $q->id }}]" 
                                       value="{{ $option }}" 
                                       class="d-none">
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    @else
                        <label class="d-block p-2 border rounded mb-2 question-option" 
                               style="cursor: pointer; transition: all 0.3s;">
                            <input type="radio" 
                                   name="answers[{{ $q->id }}]" 
                                   value="true" 
                                   class="d-none">
                            <span>صح</span>
                        </label>
                        <label class="d-block p-2 border rounded mb-2 question-option" 
                               style="cursor: pointer; transition: all 0.3s;">
                            <input type="radio" 
                                   name="answers[{{ $q->id }}]" 
                                   value="false" 
                                   class="d-none">
                            <span>خطأ</span>
                        </label>
                    @endif
                </div>
            @endforeach
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
            
            <button type="submit" 
                    class="btn btn-success d-none" 
                    id="submitBtn">
                إنهاء الاختبار <i class="icon-check"></i>
            </button>
        </div>
    </form>
</div>

<!-- Warning Modal -->
<div class="modal fade" id="timeUpModal" tabindex="-1" role="dialog" aria-labelledby="timeUpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="timeUpModalLabel">انتهى الوقت!</h5>
            </div>
            <div class="modal-body">
                <p>لقد انتهى وقت الاختبار. سيتم إرسال إجاباتك تلقائياً.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitExam()">موافق</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Submit Modal -->
<div class="modal fade" id="confirmSubmitModal" tabindex="-1" role="dialog" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="confirmSubmitModalLabel">تأكيد إنهاء الاختبار</h5>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من إنهاء الاختبار؟ لا يمكنك العودة بعد الإرسال.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="finalSubmit()">نعم، إنهاء الاختبار</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Exam variables
let currentQuestion = 0;
const totalQuestions = {{ count($questions) }};
let examDuration = {{ $exam->duration_minutes }}; // in minutes
let timeLeft = examDuration * 60; // convert to seconds
let timerInterval;
let answers = {};

// Initialize timer when page loads
document.addEventListener('DOMContentLoaded', function() {
    startTimer();
    updateProgressBar();
    updateNavigationButtons();
    
    // Store exam start time
    localStorage.setItem('exam_start_time', new Date().getTime());
    localStorage.setItem('exam_duration', examDuration);
    
    // Add click handlers for options
    document.querySelectorAll('.question-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options in this question
            const parentCard = this.closest('.question-card');
            parentCard.querySelectorAll('.question-option').forEach(opt => {
                opt.classList.remove('bg-primary', 'text-white');
            });
            
            // Add selected class to clicked option
            this.classList.add('bg-primary', 'text-white');
            
            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Store answer
            const questionId = parentCard.dataset.questionId;
            answers[questionId] = radio.value;
            updateAllAnswersInput();
        });
    });
    
    // Check if there are saved answers
    const savedAnswers = localStorage.getItem('exam_answers_' + {{ $exam->id }});
    if (savedAnswers) {
        answers = JSON.parse(savedAnswers);
        loadSavedAnswers();
        updateAllAnswersInput();
    }
});

// Timer Functions
function startTimer() {
    // Check if time was already running
    const savedStartTime = localStorage.getItem('exam_start_time');
    const savedDuration = localStorage.getItem('exam_duration');
    
    if (savedStartTime && savedDuration) {
        const now = new Date().getTime();
        const elapsedSeconds = Math.floor((now - parseInt(savedStartTime)) / 1000);
        const totalSeconds = parseInt(savedDuration) * 60;
        timeLeft = Math.max(0, totalSeconds - elapsedSeconds);
    }
    
    updateTimerDisplay();
    
    timerInterval = setInterval(function() {
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timeUp();
            return;
        }
        
        timeLeft--;
        updateTimerDisplay();
        
        // Save progress every 30 seconds
        if (timeLeft % 30 === 0) {
            saveProgress();
        }
        
        // Warning when 5 minutes left
        if (timeLeft === 300) { // 5 minutes = 300 seconds
            showTimeWarning();
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    const timerDisplay = document.getElementById('timerDisplay');
    
    // Change color when time is low
    if (timeLeft < 300) { // Less than 5 minutes
        timerDisplay.parentElement.classList.remove('alert-warning');
        timerDisplay.parentElement.classList.add('alert-danger');
    }
    
    timerDisplay.textContent = `الوقت المتبقي: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

function showTimeWarning() {
    const timerAlert = document.getElementById('examTimer');
    timerAlert.classList.remove('alert-warning');
    timerAlert.classList.add('alert-danger');
    timerAlert.innerHTML = '<i class="icon-alert-triangle"></i> <strong>تحذير!</strong> بقي 5 دقائق فقط على انتهاء الوقت!';
    
    // Flash animation
    let flashCount = 0;
    const flashInterval = setInterval(() => {
        timerAlert.classList.toggle('alert-danger');
        flashCount++;
        if (flashCount > 6) {
            clearInterval(flashInterval);
            timerAlert.classList.add('alert-danger');
        }
    }, 500);
}

function timeUp() {
    clearInterval(timerInterval);
    document.getElementById('timerDisplay').textContent = 'انتهى الوقت!';
    
    // Show modal
    $('#timeUpModal').modal({
        backdrop: 'static',
        keyboard: false
    }).modal('show');
    
    // Auto-submit after 5 seconds
    setTimeout(submitExam, 5000);
}

// Question Navigation Functions
function showNextQuestion() {
    if (currentQuestion < totalQuestions - 1) {
        hideCurrentQuestion();
        currentQuestion++;
        showCurrentQuestion();
        updateProgressBar();
        updateNavigationButtons();
    }
}

function showPreviousQuestion() {
    if (currentQuestion > 0) {
        hideCurrentQuestion();
        currentQuestion--;
        showCurrentQuestion();
        updateProgressBar();
        updateNavigationButtons();
    }
}

function showCurrentQuestion() {
    document.getElementById(`question-${currentQuestion}`).classList.remove('d-none');
}

function hideCurrentQuestion() {
    document.getElementById(`question-${currentQuestion}`).classList.add('d-none');
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    prevBtn.disabled = currentQuestion === 0;
    
    if (currentQuestion === totalQuestions - 1) {
        nextBtn.classList.add('d-none');
        submitBtn.classList.remove('d-none');
    } else {
        nextBtn.classList.remove('d-none');
        submitBtn.classList.add('d-none');
    }
}

function updateProgressBar() {
    const progress = ((currentQuestion + 1) / totalQuestions) * 100;
    const progressBar = document.getElementById('progressBar');
    progressBar.style.width = `${progress}%`;
    progressBar.setAttribute('aria-valuenow', progress);
    progressBar.textContent = `سؤال ${currentQuestion + 1} من ${totalQuestions}`;
}

// Answer Management
function updateAllAnswersInput() {
    document.getElementById('allAnswers').value = JSON.stringify(answers);
}

function loadSavedAnswers() {
    Object.keys(answers).forEach(questionId => {
        const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
        if (questionCard) {
            const radioInput = questionCard.querySelector(`input[type="radio"][value="${answers[questionId]}"]`);
            if (radioInput) {
                radioInput.checked = true;
                radioInput.parentElement.classList.add('bg-primary', 'text-white');
            }
        }
    });
}

// Save progress to localStorage
function saveProgress() {
    localStorage.setItem('exam_answers_' + {{ $exam->id }}, JSON.stringify(answers));
    localStorage.setItem('current_question_' + {{ $exam->id }}, currentQuestion);
}

// Load saved question position
window.addEventListener('beforeunload', function(e) {
    saveProgress();
});

// Submit Exam Functions
function submitExam() {
    // Show confirmation modal if not time up
    if (timeLeft > 0) {
        $('#confirmSubmitModal').modal('show');
    } else {
        finalSubmit();
    }
}

function finalSubmit() {
    // Clear localStorage
    localStorage.removeItem('exam_answers_' + {{ $exam->id }});
    localStorage.removeItem('current_question_' + {{ $exam->id }});
    localStorage.removeItem('exam_start_time');
    localStorage.removeItem('exam_duration');
    
    // Clear timer
    clearInterval(timerInterval);
    
    // Submit the form
    document.getElementById('examForm').submit();
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Left arrow for next (since it's RTL)
    if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
        e.preventDefault();
        showNextQuestion();
    }
    // Right arrow for previous
    if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
        e.preventDefault();
        showPreviousQuestion();
    }
    // Number keys for answer selection (1-4)
    if (e.key >= '1' && e.key <= '4') {
        const questionCard = document.getElementById(`question-${currentQuestion}`);
        const options = questionCard.querySelectorAll('.question-option');
        const index = parseInt(e.key) - 1;
        if (options[index]) {
            options[index].click();
        }
    }
});
</script>

<style>
.question-option:hover {
    background-color: #f8f9fa;
    border-color: #007bff !important;
}

.question-option.bg-primary {
    border-color: #007bff !important;
}

.question-card {
    animation: fadeIn 0.5s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

#examTimer {
    font-size: 1.2rem;
    font-weight: bold;
    min-width: 200px;
}

.progress-bar {
    font-weight: bold;
}
</style>
@endpush
@endsection