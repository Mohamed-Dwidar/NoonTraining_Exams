@extends('layoutmodule::admin.main')

@section('title', 'إنشاء امتحان جديد')

@section('content')
<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3><i class="fa fa-plus-circle"></i> إنشاء امتحان جديد</h3>
        </div>
    </div>

    @include('layoutmodule::admin.flash')

    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">معلومات الامتحان</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="POST" action="{{ route(Auth::getDefaultDriver() .'.exam.store') }}">
                                @csrf

                                <div class="row">
                                    {{-- Title --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title" class="form-label fw-bold">عنوان الامتحان <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                   id="title" name="title" value="{{ old('title') }}" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Category --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category_id" class="form-label fw-bold">التصنيف <span class="text-danger">*</span></label>
                                            <select class="form-control @error('category_id') is-invalid @enderror"
                                                    id="category_id" name="category_id" required>
                                                <option value="">-- اختر التصنيف --</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label fw-bold">وصف الامتحان</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Dates --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="form-label fw-bold">تاريخ البداية <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date" class="form-label fw-bold">تاريخ النهاية <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Duration --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="duration_minutes" class="form-label fw-bold">المدة (دقيقة) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                                   id="duration_minutes" name="duration_minutes"
                                                   value="{{ old('duration_minutes', 60) }}" min="1" required>
                                            @error('duration_minutes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Total Questions --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_questions" class="form-label fw-bold">عدد الأسئلة <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('total_questions') is-invalid @enderror"
                                                   id="total_questions" name="total_questions"
                                                   value="{{ old('total_questions', 20) }}" min="1" required>
                                            @error('total_questions')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Question Types Distribution --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mcq_count" class="form-label fw-bold">أسئلة اختيار من متعدد</label>
                                            <input type="number" class="form-control @error('mcq_count') is-invalid @enderror"
                                                   id="mcq_count" name="mcq_count"
                                                   value="{{ old('mcq_count', 15) }}" min="0">
                                            @error('mcq_count')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="true_false_count" class="form-label fw-bold">أسئلة صح/خطأ</label>
                                            <input type="number" class="form-control @error('true_false_count') is-invalid @enderror"
                                                   id="true_false_count" name="true_false_count"
                                                   value="{{ old('true_false_count', 5) }}" min="0">
                                            @error('true_false_count')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Grades --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="total_grade" class="form-label fw-bold">الدرجة الكلية <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('total_grade') is-invalid @enderror"
                                                   id="total_grade" name="total_grade"
                                                   value="{{ old('total_grade', 100) }}" min="1" required>
                                            @error('total_grade')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="success_grade" class="form-label fw-bold">درجة النجاح <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('success_grade') is-invalid @enderror"
                                                   id="success_grade" name="success_grade"
                                                   value="{{ old('success_grade', 50) }}" min="1" required>
                                            @error('success_grade')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Created By (hidden if needed) --}}
                                    <input type="hidden" name="created_by" value="{{ auth()->id() }}">

                                    {{-- Submit Buttons --}}
                                    <div class="col-12 mt-4">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="fa fa-save"></i> حفظ الامتحان
                                            </button>
                                            <a href="{{ route(Auth::getDefaultDriver() . '.exam.index') }}"
                                               class="btn btn-secondary px-4">
                                                <i class="fa fa-times"></i> إلغاء
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set min date for end date based on start date
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if (startDateInput) {
            // Set today as default start date if empty
            if (!startDateInput.value) {
                const today = new Date().toISOString().split('T')[0];
                startDateInput.value = today;
            }

            // Update end date min when start date changes
            startDateInput.addEventListener('change', function() {
                if (endDateInput.value && new Date(endDateInput.value) < new Date(this.value)) {
                    endDateInput.value = this.value;
                }
                endDateInput.min = this.value;
            });

            // Initialize end date min
            endDateInput.min = startDateInput.value;
        }

        // Set default end date (start date + 7 days)
        if (endDateInput && !endDateInput.value && startDateInput && startDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 7);
            endDateInput.value = endDate.toISOString().split('T')[0];
        }

        // Calculate question distribution
        const totalQuestionsInput = document.getElementById('total_questions');
        const mcqCountInput = document.getElementById('mcq_count');
        const trueFalseCountInput = document.getElementById('true_false_count');

        function updateQuestionDistribution() {
            if (totalQuestionsInput && mcqCountInput && trueFalseCountInput) {
                const total = parseInt(totalQuestionsInput.value) || 0;
                const mcq = parseInt(mcqCountInput.value) || 0;
                const trueFalse = parseInt(trueFalseCountInput.value) || 0;

                // Auto-calculate missing values
                if (mcq + trueFalse !== total) {
                    if (mcq > 0 && trueFalse === 0) {
                        trueFalseCountInput.value = total - mcq;
                    } else if (trueFalse > 0 && mcq === 0) {
                        mcqCountInput.value = total - trueFalse;
                    }
                }

                // Set max values
                mcqCountInput.max = total;
                trueFalseCountInput.max = total;
            }
        }

        if (totalQuestionsInput) {
            totalQuestionsInput.addEventListener('input', updateQuestionDistribution);
        }
        if (mcqCountInput) {
            mcqCountInput.addEventListener('input', updateQuestionDistribution);
        }
        if (trueFalseCountInput) {
            trueFalseCountInput.addEventListener('input', updateQuestionDistribution);
        }

        // Initialize distribution
        updateQuestionDistribution();
    });
</script>
@endsection
