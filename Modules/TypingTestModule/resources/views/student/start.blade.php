@extends('layoutmodule::layouts.main')

@section('title')
    {{ $settings->title }}
@endsection

@include('typingtestmodule::partials.styles')

@push('styles')
    <style>
        .tt-bar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: .5rem; }
        #typingTimer { font-size: 1.2rem; font-weight: bold; min-width: 200px; text-align: center; }
        .typing-counters .badge { display: inline-block; font-size: 1rem; padding: .4rem .8rem; margin: 0 0 .25rem .25rem; border-radius: 4px; color: #fff; }
        #typedText {
            direction: rtl;
            font-size: 1.3rem;
            line-height: 2.2rem;
            min-height: 220px;
        }
    </style>
@endpush

@section('content')
    @php $sourceWords = \Modules\TypingTestModule\Services\TypingEvaluator::words($attempt->source_text); @endphp
    <div class="card p-3">
        <div class="tt-bar mb-2">
            <div>
                <h3 class="mb-0"><i class="fa fa-keyboard-o"></i> {{ $settings->title }}</h3>
                @if ($examTitle)
                    <small class="text-muted">الجزء الأول من أختبار: <strong>{{ $examTitle }}</strong></small>
                @endif
            </div>
            <div class="alert alert-warning mb-0" id="typingTimer">
                <i class="fa fa-clock-o"></i> <span id="timerDisplay">--:--</span>
            </div>
        </div>

        <div class="typing-counters mb-2">
            <span class="badge bg-primary">عدد الكلمات المكتوبة: <span id="typedCount">0</span> / {{ count($sourceWords) }}</span>
            <span class="badge bg-success">صحيحة: <span id="correctCount">0</span></span>
            <span class="badge bg-danger">خاطئة: <span id="wrongCount">0</span></span>
            <span class="badge bg-blue-grey">درجة النجاح: {{ $attempt->success_grade }} كلمة صحيحة</span>
        </div>

        <div class="typing-source mb-2" id="sourceText" oncopy="return false" oncut="return false" oncontextmenu="return false">
            @foreach ($sourceWords as $i => $word)
                <span class="tw" data-i="{{ $i }}">{{ $word }}</span>
            @endforeach
        </div>

        <form method="POST" action="{{ route('student.typing.submit', $attempt->id) }}" id="typingForm">
            @csrf
            <textarea name="typed_text" id="typedText" class="form-control" rows="7"
                placeholder="ابدأ الكتابة هنا..." autocomplete="off" autocorrect="off" autocapitalize="off"
                spellcheck="false">{{ $attempt->typed_text }}</textarea>

            <div class="tt-bar mt-2">
                <small class="text-muted" id="saveStatus"></small>
                <button type="button" class="btn btn-success" id="finishBtn">
                    إنهاء الأختبار <i class="fa fa-check"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="timeUpModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">انتهى الوقت!</h5>
                </div>
                <div class="modal-body">
                    <p>لقد انتهى وقت الأختبار. جاري إرسال ما كتبته...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            // Same rules as Modules/TypingTestModule/Services/TypingEvaluator.php
            const SOURCE = @json($sourceWords);
            const SAVE_URL = @json(route('student.typing.save', $attempt->id));
            const CSRF = @json(csrf_token());

            const textarea = document.getElementById('typedText');
            const form = document.getElementById('typingForm');
            const spans = Array.from(document.querySelectorAll('#sourceText .tw'));
            const typedCountEl = document.getElementById('typedCount');
            const correctCountEl = document.getElementById('correctCount');
            const wrongCountEl = document.getElementById('wrongCount');
            const saveStatus = document.getElementById('saveStatus');
            const normSource = SOURCE.map(normalize);

            let deadline = Date.now() + {{ (int) $remainingSeconds }} * 1000;
            let submitted = false;
            let dirty = false;

            function splitWords(text) {
                return text.replace(/[​‎‏﻿]/g, '').trim().split(/[\s ]+/).filter(w => w.length > 0);
            }

            function normalize(word) {
                return word.replace(/[أإآٱ]/g, 'ا').replace(/ة/g, 'ه').replace(/ى/g, 'ي');
            }

            function evaluate() {
                const raw = textarea.value;
                const typed = splitWords(raw);
                const endsWithSpace = /[\s ]$/.test(raw);
                let correct = 0, wrong = 0;

                spans.forEach(s => s.className = 'tw');

                typed.forEach((word, i) => {
                    if (i >= SOURCE.length) { wrong++; return; }
                    const t = normalize(word);
                    const s = normSource[i];
                    const isLast = i === typed.length - 1 && !endsWithSpace;

                    if (s === t) {
                        spans[i].classList.add('tw-correct');
                        correct++;
                    } else if (isLast && s.startsWith(t)) {
                        spans[i].classList.add('tw-current'); // still typing this word
                    } else {
                        spans[i].classList.add('tw-wrong');
                        wrong++;
                    }
                });

                // Mark the next word to type
                const next = (typed.length > 0 && !endsWithSpace) ? -1 : typed.length;
                if (next >= 0 && next < spans.length) spans[next].classList.add('tw-current');

                typedCountEl.textContent = typed.length;
                correctCountEl.textContent = correct;
                wrongCountEl.textContent = wrong;
            }

            // ---- Anti copy/paste ----
            ['paste', 'drop', 'contextmenu', 'copy', 'cut'].forEach(evt =>
                textarea.addEventListener(evt, e => e.preventDefault())
            );

            textarea.addEventListener('input', () => { dirty = true; evaluate(); });

            // ---- Autosave ----
            function save() {
                if (!dirty || submitted) return;
                dirty = false;
                fetch(SAVE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ typed_text: textarea.value })
                })
                .then(r => r.json())
                .then(data => {
                    saveStatus.textContent = data.success ? 'تم الحفظ تلقائياً ✓' : '';
                    if (typeof data.remaining === 'number') deadline = Date.now() + data.remaining * 1000;
                })
                .catch(() => { dirty = true; saveStatus.textContent = 'تعذر الحفظ، سيتم إعادة المحاولة...'; });
            }
            setInterval(save, 15000);

            // ---- Timer ----
            const timerEl = document.getElementById('typingTimer');
            const timerDisplay = document.getElementById('timerDisplay');
            function tick() {
                const left = Math.max(0, Math.ceil((deadline - Date.now()) / 1000));
                const m = Math.floor(left / 60), s = left % 60;
                timerDisplay.textContent = 'الوقت المتبقي: ' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                if (left <= 60) { timerEl.classList.remove('alert-warning'); timerEl.classList.add('alert-danger'); }
                if (left <= 0) timeUp();
            }
            const timer = setInterval(tick, 500);

            function submitNow() {
                if (submitted) return;
                submitted = true;
                clearInterval(timer);
                textarea.readOnly = true;
                document.getElementById('finishBtn').disabled = true;
                form.submit();
            }

            function timeUp() {
                if (submitted) return;
                $('#timeUpModal').modal('show');
                submitNow();
            }

            document.getElementById('finishBtn').addEventListener('click', function() {
                if (confirm('هل أنت متأكد من إنهاء الأختبار؟ لا يمكنك العودة للكتابة بعد الإرسال.')) submitNow();
            });

            window.addEventListener('beforeunload', function(e) {
                if (!submitted) {
                    save();
                    e.preventDefault();
                    e.returnValue = 'لديك اختبار قيد التقدم. هل أنت متأكد من المغادرة؟';
                }
            });

            evaluate();
            tick();
            textarea.focus();
        })();
    </script>
@endpush
