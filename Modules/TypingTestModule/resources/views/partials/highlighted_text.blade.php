{{-- Server-side rendering of a graded attempt. Expects $evaluation from TypingEvaluator::evaluate() --}}
@include('typingtestmodule::partials.styles')
<div class="typing-source">
    @foreach ($evaluation['source'] as $i => $word)
        <span class="tw @if ($evaluation['statuses'][$i] === 'correct') tw-correct @elseif ($evaluation['statuses'][$i] === 'wrong') tw-wrong @endif">{{ $word }}</span>
    @endforeach
    @foreach ($evaluation['extra'] as $word)
        <span class="tw tw-extra" title="كلمة زائدة">{{ $word }}</span>
    @endforeach
</div>
<div class="typing-legend mt-1 small text-muted">
    <span><span class="tt-swatch" style="background:#d4edda"></span> كلمة صحيحة</span>
    <span><span class="tt-swatch" style="background:#f8d7da"></span> كلمة خاطئة</span>
    <span><span class="tt-swatch" style="background:#f8f9fa"></span> لم تُكتب</span>
</div>
