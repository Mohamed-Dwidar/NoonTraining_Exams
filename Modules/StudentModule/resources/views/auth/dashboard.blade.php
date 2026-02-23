@extends('layoutmodule::layouts.main')

@section('title')
    لوحة تحكم الطالب
@endsection

@section('content')

    <div class="content-wrapper container-fluid">

        @include('layoutmodule::layouts.flash')

        <div class="content-body">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card p-3">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fa fa-tasks"></i>
                                &nbsp;
                                أختبارتي
                            </h4>
                        </div>

                        <div class="card-body">
                            @if ($exams->count() > 0)
                                <div class="table-responsive">
                                    <table class="table mb-0" id="assignedExamsTable">
                                        <thead class="table-primary">
                                            <tr>
                                                <th> الاختبار</th>
                                                <th>تاريخ الأختبار</th>
                                                <th>النتيجة</th>
                                                <th>الحالة</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="assignedExamsTableBody">
                                            @foreach ($exams->sortByDesc('created_at') as $student_exam)
                                                <tr id="exam-row-{{ $student_exam->id }}">
                                                    <td><strong>{{ $student_exam->exam->title }}</strong></td>
                                                    <td>{{ $student_exam->started_at ? $student_exam->started_at->format('Y-m-d H:i') : '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($student_exam->score !== null)
                                                            <span
                                                                class="badge {{ $student_exam->score >= ($student_exam->exam->success_grade ?? 0) ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $student_exam->score }} /
                                                                {{ $student_exam->exam->total_grade ?? '-' }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">لم يتم الاختبار</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($student_exam->status  == 'completed')
                                                            @if ($student_exam->score >= ($student_exam->exam->success_grade ?? 0))
                                                                <span class="badge bg-success"><i class="fa fa-check"></i>
                                                                    ناجح</span>
                                                            @else
                                                                <span class="badge bg-danger"><i class="fa fa-times"></i>
                                                                    راسب</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted"><i class="fa fa-hourglass-half"></i> في
                                                                انتظار الاختبار</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($student_exam->status == 'not_started')
                                                            <button type="button"
                                                                id="startExamBtn-{{ $student_exam->id }}"
                                                                data-exam-id="{{ $student_exam->id }}"
                                                                data-exam-title="{{ $student_exam->exam->title }}"
                                                                data-exam-duration="{{ $student_exam->exam->duration_minutes ?? 'غير محدد' }}"
                                                                data-total-questions="{{ $student_exam->exam->total_questions ?? 0 }}"
                                                                data-success-grade="{{ $student_exam->exam->success_grade ?? 0 }}"
                                                                data-total-grade="{{ $student_exam->exam->total_grade ?? 0 }}"
                                                                data-exam-url="{{ route('student.exam.start', $student_exam->id) }}"
                                                                class="btn btn-success btn-sm startExamBtn">
                                                                بدء الأختبار
                                                            </button>
                                                        @elseif($student_exam->status == 'completed')
                                                            <a href="{{ route('student.exam.result', $student_exam->id) }}" id="viewResultBtn-{{ $student_exam->id }}" data-exam-id="{{ $student_exam->id }}"
                                                                class="btn btn-info btn-sm">
                                                                عرض النتيجة
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center" role="alert">
                                    <i class="fa fa-info-circle"></i> لا توجد اختبارات مسجلة لهذا الطالب حتى
                                    الآن
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Exam Start Confirmation Modal -->
    <div class="modal fade" id="examStartModal" tabindex="-1" role="dialog" aria-labelledby="examStartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="examStartModalLabel">
                        <i class="fa fa-exclamation-triangle"></i> تأكيد بدء الاختبار
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>

                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fa fa-info-circle"></i>
                        <strong>تنبيه:</strong> بمجرد بدء الاختبار، سيبدأ العد التنازلي ولن تتمكن من إيقافه.
                    </div>
                    <h5 class="mb-2 text-center" id="examTitleModal"></h5>
                    <div class="exam-info">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fa fa-hourglass-half text-info"></i>
                                    <strong> مدة الاختبار:</strong>
                                    <span id="examDurationModal" class="text-primary"></span>
                                </p>
                                <p class="mb-2">
                                    <i class="fa fa-trophy text-warning"></i>
                                    <strong> الدرجة الكلية:</strong>
                                    <span id="totalGradeModal" class="text-primary"></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fa fa-list text-info"></i>
                                    <strong> إجمالي الأسئلة:</strong>
                                    <span id="totalQuestionsModal" class="text-primary"></span>
                                </p>
                                <p class="mb-2">
                                    <i class="fa fa-star text-warning"></i>
                                    <strong> درجة النجاح:</strong>
                                    <span id="successGradeModal" class="text-success"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="text-muted text-center mt-2"><strong>هل أنت متأكد من أنك تريد بدء الاختبار الآن؟</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> إلغاء
                    </button>
                    <button type="button" class="btn btn-success" id="confirmStartExam">
                        <i class="fa fa-check"></i> بدء الاختبار
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let examUrl = '';

        // Handle click on start exam buttons
        $(document).on('click', '.startExamBtn', function(e) {
            e.preventDefault();

            const examTitle = $(this).data('exam-title');
            const examDuration = $(this).data('exam-duration');
            const totalQuestions = $(this).data('total-questions');
            const mcqCount = $(this).data('mcq-count');
            const trueFalseCount = $(this).data('true-false-count');
            const successGrade = $(this).data('success-grade');
            const totalGrade = $(this).data('total-grade');
            examUrl = $(this).data('exam-url');

            // Update modal content
            $('#examTitleModal').text(examTitle);
            $('#examDurationModal').text(examDuration + ' دقيقة');
            $('#totalQuestionsModal').text(totalQuestions);
            $('#mcqCountModal').text(mcqCount);
            $('#trueFalseCountModal').text(trueFalseCount);
            $('#successGradeModal').text(successGrade);
            $('#totalGradeModal').text(totalGrade);

            // Show modal
            $('#examStartModal').modal('show');
        });

        // Handle confirm button click
        $('#confirmStartExam').on('click', function() {
            if (examUrl) {
                $('#examStartModal').modal('hide');
                window.location.href = examUrl;
            }
        });
    });
</script>
@endpush
