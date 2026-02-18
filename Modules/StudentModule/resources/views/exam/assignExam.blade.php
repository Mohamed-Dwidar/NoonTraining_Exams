@extends('layoutmodule::admin.main')

@section('title', 'تسجيل الأختبارات للطالب')
@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-user-graduate"></i> تسجيل الأختبارات للطالب</h3>
            </div>
        </div>

        @include('layoutmodule::admin.flash')
        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fa fa-graduation-cap"></i>
                                &nbsp;
                                الطالب : <strong> {{ $student->name }}</strong>
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <form class="card-form side-form" method="POST"
                                        action="{{ route(Auth::getDefaultDriver() . '.students.assignExam') }}">
                                        @csrf

                                        <input type="hidden" name="student_id" value="{{ $student->id }}">

                                        <!-- Select All / Deselect All Buttons -->
                                        <div class="mb-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                                <i class="fa fa-check-double"></i> اختر الكل
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="deselectAll">
                                                <i class="fa fa-times"></i> إلغاء التحديد
                                            </button>
                                        </div>

                                        <div class="row">
                                            @forelse ($exams as $exam)
                                                <div class="col-lg-6 col-md-6 col-12 mb-3">
                                                    <div class="exam-checkbox-card border rounded p-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input exam-checkbox" type="checkbox"
                                                                name="exam_id[]" value="{{ $exam->id }}"
                                                                id="exam_{{ $exam->id }}"
                                                                {{ in_array($exam->id, $student->exams->pluck('id')->toArray()) ? 'checked' : '' }}>

                                                            <label class="form-check-label w-100"
                                                                for="exam_{{ $exam->id }}" style="cursor: pointer;">
                                                                <div class="d-flex flex-column">
                                                                    <!-- Row 1: Title -->
                                                                    <div class="mb-0">
                                                                        <strong>{{ $exam->title }}</strong>
                                                                    </div>

                                                                    <!-- Row 2: Category -->
                                                                    @if ($exam->category_id)
                                                                        <div class="mb-0">
                                                                            <small class="text-secondary">
                                                                                <i class="fa fa-tag"></i>
                                                                                {{ $exam->category->name ?? 'غير محدد' }}
                                                                            </small>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Row 3: Description -->
                                                                    @if ($exam->description)
                                                                        <div class="mb-1">
                                                                            <small
                                                                                class="text-muted">{{ Str::limit($exam->description, 100) }}</small>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Row 4: Questions (total - mcq - true_false) -->
                                                                    <div class="mb-0">
                                                                        <small class="text-secondary">
                                                                            <i class="fa fa-list"></i>
                                                                            إجمالي:
                                                                            <strong>{{ $exam->total_questions ?? 0 }}</strong>
                                                                            @if ($exam->mcq_count || $exam->true_false_count)
                                                                                - اختياري:
                                                                                <strong>{{ $exam->mcq_count ?? 0 }}</strong>
                                                                                - صح/خطأ:
                                                                                <strong>{{ $exam->true_false_count ?? 0 }}</strong>
                                                                            @endif
                                                                        </small>
                                                                    </div>

                                                                    <!-- Row 5: Grades (success_grade - total_grade) -->
                                                                    @if ($exam->success_grade || $exam->total_grade)
                                                                        <div class="mb-0">
                                                                            <small class="text-secondary">
                                                                                <i class="fa fa-star"></i>
                                                                                @if ($exam->success_grade)
                                                                                    نجاح:
                                                                                    <strong>{{ $exam->success_grade }}</strong>
                                                                                @endif
                                                                                @if ($exam->total_grade)
                                                                                    - كلي:
                                                                                    <strong>{{ $exam->total_grade }}</strong>
                                                                                @endif
                                                                            </small>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Row 6: Duration -->
                                                                    @if ($exam->duration_minutes)
                                                                        <div>
                                                                            <small class="text-secondary">
                                                                                <i class="fa fa-clock"></i>
                                                                                {{ $exam->duration_minutes }} دقيقة
                                                                            </small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="alert alert-info" role="alert">
                                                        <i class="fa fa-info-circle"></i> لا توجد أختبارات متاحة
                                                        حالياً
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>

                                        <!-- Form Actions -->
                                        <div class="mt-4 d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> حفظ التعيينات
                                            </button>
                                            <a href="{{ route(Auth::getDefaultDriver() . '.students.index') }}"
                                                class="btn btn-secondary">
                                                <i class="fa fa-arrow-left"></i> رجوع
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('selectAll').addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.exam-checkbox').forEach(checkbox => {
                    checkbox.checked = true;
                });
            });

            document.getElementById('deselectAll').addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.exam-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
        </script>
    @endsection
