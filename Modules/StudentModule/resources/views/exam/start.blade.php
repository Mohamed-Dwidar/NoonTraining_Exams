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
            
            <button type="button" 
                    class="btn btn-success d-none" 
                    id="submitBtn"
                    onclick="showSubmitModal()">
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
                <button type="button" class="btn btn-primary" onclick="submitExamNow()">موافق</button>
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
                <p class="text-muted">تمت الإجابة على <span id="answeredCount">0</span> من <span id="totalQuestionsCount">{{ count($questions) }}</span> سؤالاً.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="submitExamNow()">نعم، إنهاء الاختبار</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Exam variables
let currentQuestion = 0;
const totalQuestions = {{ count($questions) }};
let examDuration = {{ $exam->duration_minutes }};
let timeLeft = examDuration * 60;
let timerInterval;
let answers = {};

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing exam...');
    console.log('Total questions:', totalQuestions);
    console.log('Exam duration:', examDuration, 'minutes');
    
    // Start the timer
    startTimer();
    
    // Initialize navigation
    updateNavigationButtons();
    updateProgressBar();
    
    // Initialize question click handlers
    initQuestionHandlers();
    
    // Load saved progress
    loadSavedProgress();
    
    // Prevent accidental navigation
    window.addEventListener('beforeunload', function(e) {
        if (timeLeft > 0 && currentQuestion < totalQuestions - 1) {
            saveProgress();
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
});

// Timer Functions
function startTimer() {
    console.log('Starting timer...');
    
    // Check for saved time
    const savedStartTime = localStorage.getItem('exam_start_time_' + {{ $exam->id }});
    const savedDuration = localStorage.getItem('exam_duration_' + {{ $exam->id }});
    
    if (savedStartTime && savedDuration) {
        const now = new Date().getTime();
        const elapsedSeconds = Math.floor((now - parseInt(savedStartTime)) / 1000);
        const totalSeconds = parseInt(savedDuration) * 60;
        timeLeft = Math.max(0, totalSeconds - elapsedSeconds);
        console.log('Loaded saved time. Time left:', timeLeft, 'seconds');
    } else {
        // First time starting
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
        
        // Save progress every minute
        if (timeLeft % 60 === 0) {
            saveProgress();
        }
        
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
    console.log('Time is up!');
    document.getElementById('timerDisplay').textContent = 'انتهى الوقت!';
    
    $('#timeUpModal').modal({
        backdrop: 'static',
        keyboard: false
    }).modal('show');
}

// Question Navigation Functions
function showNextQuestion() {
    console.log('Showing next question, current:', currentQuestion);
    
    if (currentQuestion < totalQuestions - 1) {
        // Hide current question
        document.getElementById(`question-${currentQuestion}`).classList.add('d-none');
        
        // Show next question
        currentQuestion++;
        document.getElementById(`question-${currentQuestion}`).classList.remove('d-none');
        
        updateProgressBar();
        updateNavigationButtons();
        
        // Save current position
        saveCurrentPosition();
    }
}

function showPreviousQuestion() {
    console.log('Showing previous question, current:', currentQuestion);
    
    if (currentQuestion > 0) {
        // Hide current question
        document.getElementById(`question-${currentQuestion}`).classList.add('d-none');
        
        // Show previous question
        currentQuestion--;
        document.getElementById(`question-${currentQuestion}`).classList.remove('d-none');
        
        updateProgressBar();
        updateNavigationButtons();
        
        // Save current position
        saveCurrentPosition();
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    // Previous button
    prevBtn.disabled = currentQuestion === 0;
    
    // Next/Submit buttons
    if (currentQuestion === totalQuestions - 1) {
        nextBtn.classList.add('d-none');
        submitBtn.classList.remove('d-none');
    } else {
        nextBtn.classList.remove('d-none');
        submitBtn.classList.add('d-none');
    }
    
    console.log('Updated navigation: currentQuestion =', currentQuestion);
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
            
            // Store answer
            answers[questionId] = radio.value;
            console.log('Stored answer for question', questionId, ':', answers[questionId]);
            
            // Auto-save
            saveProgress();
        });
    });
}

// Progress Management Functions
function loadSavedProgress() {
    const savedAnswers = localStorage.getItem('exam_answers_' + {{ $exam->id }});
    if (savedAnswers) {
        answers = JSON.parse(savedAnswers);
        console.log('Loaded saved answers:', answers);
        
        // Apply saved answers to UI
        Object.keys(answers).forEach(questionId => {
            const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
            if (questionCard) {
                const option = questionCard.querySelector(`input[value="${answers[questionId]}"]`);
                if (option && option.parentElement) {
                    option.parentElement.classList.add('bg-primary', 'text-white');
                    option.checked = true;
                }
            }
        });
    }
    
    const savedPosition = localStorage.getItem('current_question_' + {{ $exam->id }});
    if (savedPosition !== null) {
        const savedPos = parseInt(savedPosition);
        if (savedPos >= 0 && savedPos < totalQuestions && savedPos !== currentQuestion) {
            // Hide current question
            document.getElementById(`question-${currentQuestion}`).classList.add('d-none');
            
            // Show saved position
            currentQuestion = savedPos;
            document.getElementById(`question-${currentQuestion}`).classList.remove('d-none');
            
            updateProgressBar();
            updateNavigationButtons();
        }
    }
}

function saveProgress() {
    localStorage.setItem('exam_answers_' + {{ $exam->id }}, JSON.stringify(answers));
    saveCurrentPosition();
}

function saveCurrentPosition() {
    localStorage.setItem('current_question_' + {{ $exam->id }}, currentQuestion);
}

// Submit Functions
function showSubmitModal() {
    // Count answered questions
    const answeredCount = Object.keys(answers).length;
    document.getElementById('answeredCount').textContent = answeredCount;
    document.getElementById('totalQuestionsCount').textContent = totalQuestions;
    
    $('#confirmSubmitModal').modal('show');
}

function submitExamNow() {
    console.log('Submitting exam...');
    
    // Clear timer
    clearInterval(timerInterval);
    
    // Clear localStorage
    localStorage.removeItem('exam_answers_' + {{ $exam->id }});
    localStorage.removeItem('current_question_' + {{ $exam->id }});
    localStorage.removeItem('exam_start_time_' + {{ $exam->id }});
    localStorage.removeItem('exam_duration_' + {{ $exam->id }});
    
    // Create hidden inputs for all answers
    Object.keys(answers).forEach(questionId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `answers[${questionId}]`;
        input.value = answers[questionId];
        document.getElementById('examForm').appendChild(input);
    });
    
    // Submit the form
    document.getElementById('examForm').submit();
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Right arrow for next (in RTL)
    if (e.key === 'ArrowRight') {
        e.preventDefault();
        showNextQuestion();
    }
    // Left arrow for previous (in RTL)
    if (e.key === 'ArrowLeft') {
        e.preventDefault();
        showPreviousQuestion();
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
        showNextQuestion();
    }
});

// Debug function (remove in production)
window.debugExam = function() {
    console.log('=== DEBUG INFO ===');
    console.log('Current question:', currentQuestion);
    console.log('Total questions:', totalQuestions);
    console.log('Time left:', timeLeft, 'seconds');
    console.log('Answers:', answers);
    console.log('Answers count:', Object.keys(answers).length);
    console.log('==================');
}
</script>

<style>
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