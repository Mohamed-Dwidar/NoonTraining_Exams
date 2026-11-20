@csrf
<div class="row">
    <div class="col-lg-6 col-12">
        <label for="title">عنوان النص (اختياري، للتمييز فقط)</label>
        <div class="form-group">
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                value="{{ old('title', $text->title ?? '') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 col-12">
        <label for="content">نص الأختبار</label>
        <div class="form-group">
            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10"
                style="font-size: 1.15rem; line-height: 2rem;">{{ old('content', $text->content ?? '') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">
                عدد الكلمات: <strong id="contentWords">0</strong>
                — المطلوب في الأختبار: <strong>{{ $settings->word_count }}</strong> كلمة.
                إذا كان النص أطول يُعرض للطالب أول {{ $settings->word_count }} كلمة فقط.
            </small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group">
            <input type="hidden" name="is_active" value="0">
            <label>
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $text->is_active ?? true) ? 'checked' : '' }}>
                نشط (يدخل في الاختيار العشوائي)
            </label>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            const area = document.getElementById('content');
            const out = document.getElementById('contentWords');
            const required = {{ $settings->word_count }};
            function count() {
                const n = area.value.trim() === '' ? 0 : area.value.trim().split(/\s+/).length;
                out.textContent = n;
                out.className = n < required ? 'text-danger' : 'text-success';
            }
            area.addEventListener('input', count);
            count();
        })();
    </script>
@endpush
