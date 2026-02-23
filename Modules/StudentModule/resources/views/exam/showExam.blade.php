@extends('layoutmodule::layouts.main')

@section('title', 'الأختبارات المسجلة للطالب')

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-user-graduate"></i> الأختبارات المسجلة للطالب
                    <strong>{{ $student->name }}</strong>
                </h3>
            </div>
        </div>


        @include('layoutmodule::layouts.flash')

        <!-- Success Message (for AJAX) -->
        <div id="successMessage" class="alert alert-success alert-dismissible fade d-none" role="alert" style="display: none;">
            <i class="fa fa-check-circle"></i>
            <span id="successText"></span>
            <button type="button" class="btn-close" onclick="this.parentElement.classList.add('d-none')"></button>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h4 class="card-title">
                                        <i class="fa fa-graduation-cap"></i> الاختبارات المسجلة
                                        (<span id="assignedExamCount">{{ $student->exams->count() }}</span>)
                                    </h4>
                                </div>
                                <div class="col-lg-4">
                                    <button class="btn btn-success round btn-min-width mr-1 mb-1" id="assignExamBtn" type="button">
                                        <i class="fa fa-plus-circle"></i> تعيين اختبار للطالب
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="assignedExamsContainer">
                                @if ($student->exams->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table mb-0" id="assignedExamsTable">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>عنوان الاختبار</th>
                                                    <th>تاريخ الأختبار</th>
                                                    <th>النتيجة</th>
                                                    <th>الحالة</th>
                                                    <th>الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody id="assignedExamsTableBody">
                                                @foreach ($student->studentExams->sortByDesc('created_at') as $student_exam)
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
                                                            @if ($student_exam->score !== null)
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
                                                            @if ($student_exam->score === null)
                                                                <button type="button" class="btn btn-sm btn-danger"
                                                                    onclick="unassignExam({{ $student_exam->id }}, '{{ $student_exam->exam->title }}'); return false;"
                                                                    title="إلغاء تعيين الاختبار">
                                                                    <i class="fa fa-times"></i> إلغاء التعيين
                                                                </button>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center" role="alert">
                                        <i class="fa fa-info-circle"></i> لا توجد اختبارات مسجلة لهذا الطالب حتى الآن
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Exam Modal -->
    <div class="modal fade" id="assignExamModal" tabindex="-1" aria-labelledby="assignExamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title text-white" id="assignExamModalLabel">
                        <i class="fa fa-plus-circle"></i>
                         تعيين اختبار جديد

                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>

                </div>
                <div class="modal-body">
                    <div id="modalMessage" class="alert d-none" role="alert"></div>

                    <div id="modalContent">
                        <div class="mb-3">
                            <label class="form-label fw-bold">اختر الاختبار:</label>
                            <input type="text" id="examSearch" class="form-control mb-3" placeholder="ابحث عن اختبار...">
                        </div>

                        <div id="examsList" class="list-group" style="max-height: 500px; overflow-y: auto;">
                            @php
                                $assignedExamIds = $student->exams->pluck('id')->toArray();
                            @endphp

                            @foreach ($exams->sortByDesc('created_at') as $exam)
                                <a href="#" class="list-group-item list-group-item-action exam-item" data-exam-id="{{ $exam->id }}" data-exam-title="{{ $exam->title }}"
                                   onclick="assignExam({{ $exam->id }}, '{{ $exam->title }}', this); return false;">
                                    <div class="row align-items-center">
                                        <div class="col-md-9">
                                            <h6 class="mb-0">{{ $exam->title }}</h6>
                                            <small class="text-muted d-block mb-0">{{ $exam->created_at->format('Y-m-d') }}</small>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-sm btn-success"
                                                onclick="event.stopPropagation(); assignExam({{ $exam->id }}, '{{ $exam->title }}', this); return false;"
                                                style="white-space: nowrap;">
                                                @if (in_array($exam->id, $assignedExamIds))
                                                    <i class="fa fa-check"></i> تعيين مرة أخرى
                                                @else
                                                    <i class="fa fa-plus"></i> تعيين
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title text-white" id="confirmModalLabel">
                        <i class="fa fa-exclamation-triangle"></i> تأكيد العملية
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage" class="mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> إلغاء
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmButton">
                        <i class="fa fa-check"></i> تأكيد
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Handle assign exam button click - Use jQuery for Bootstrap 4
            const assignExamBtn = document.getElementById('assignExamBtn');
            if (assignExamBtn) {
                assignExamBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Use jQuery to trigger modal since Bootstrap 4 is being used
                    if (typeof jQuery !== 'undefined') {
                        jQuery('#assignExamModal').modal('show');
                    } else if (typeof bootstrap !== 'undefined') {
                        // Fallback for Bootstrap 5
                        const modal = new bootstrap.Modal(document.getElementById('assignExamModal'));
                        modal.show();
                    } else {
                        alert('Modal library not loaded');
                    }
                });
            }

            const modalEl = document.getElementById('assignExamModal');
            if (modalEl) {
                if (typeof jQuery !== 'undefined') {
                    jQuery(modalEl).on('show.bs.modal hidden.bs.modal', function() {
                        clearModalMessage();
                    });
                } else {
                    modalEl.addEventListener('show.bs.modal', clearModalMessage);
                    modalEl.addEventListener('hidden.bs.modal', clearModalMessage);
                }
            }

            // Search functionality
            const examSearch = document.getElementById('examSearch');
            if (examSearch) {
                examSearch.addEventListener('keyup', function() {
                    const searchValue = this.value.toLowerCase();
                    const examItems = document.querySelectorAll('.exam-item');

                    examItems.forEach(item => {
                        const title = item.getAttribute('data-exam-title').toLowerCase();
                        if (title.includes(searchValue)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        });

        // Assign exam via AJAX
        function assignExam(examId, examTitle, clickedElement) {
            // Get the exam item element
            const examItem = clickedElement.closest('.exam-item');
            const originalHtml = examItem ? examItem.innerHTML : '';
            if (examItem) {
                examItem.dataset.originalHtml = originalHtml;
            }

            // Show loading state
            if (examItem) {
                examItem.innerHTML = '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> جاري التعيين...</div>';
            }
            setModalMessage('info', '<i class="fa fa-spinner fa-spin"></i> جاري التعيين...');

            fetch('{{ route(Auth::getDefaultDriver() . '.students.assignSingleExam') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_id: {{ $student->id }},
                        exam_id: examId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const successMessage = document.getElementById('successMessage');
                        const successText = document.getElementById('successText');
                        successText.textContent = data.message + ': ' + examTitle;
                        successMessage.classList.add('show');
                        successMessage.classList.remove('d-none');
                        if (examItem) {
                            examItem.innerHTML = '<div class="text-center text-success"><i class="fa fa-check-circle"></i> ' +
                                (data.message || 'تم التعيين بنجاح') + ': ' + escapeHtml(examTitle) + '</div>';
                        }

                        // Update assigned exams table (prepend new row)
                        const container = document.getElementById('assignedExamsContainer');
                        const tbody = document.getElementById('assignedExamsTableBody');
                        const studentExamId = data.student_exam_id;
                        const newRowHtml = `
                            <tr id="exam-row-${studentExamId}">
                                <td><strong>${escapeHtml(examTitle)}</strong></td>
                                <td>-</td>
                                <td class="text-center"><span class="text-muted">لم يتم الاختبار</span></td>
                                <td><span class="text-muted"><i class="fa fa-hourglass-half"></i> في انتظار الاختبار</span></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="unassignExam(${studentExamId}, '${escapeHtml(examTitle).replace(/'/g, "\\'")}')؛ return false;"
                                        title="إلغاء تعيين الاختبار">
                                        <i class="fa fa-times"></i> إلغاء التعيين
                                    </button>
                                </td>
                            </tr>
                        `;

                        if (tbody) {
                            tbody.insertAdjacentHTML('afterbegin', newRowHtml);
                        } else if (container) {
                            container.innerHTML = `
                                <div class="table-responsive">
                                    <table class="table mb-0" id="assignedExamsTable">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>عنوان الاختبار</th>
                                                <th>تاريخ الأختبار</th>
                                                <th>الدرجة</th>
                                                <th>الحالة</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody id="assignedExamsTableBody">
                                            ${newRowHtml}
                                        </tbody>
                                    </table>
                                </div>
                            `;
                        }

                        const countEl = document.getElementById('assignedExamCount');
                        if (countEl) {
                            const currentCount = parseInt(countEl.textContent, 10) || 0;
                            countEl.textContent = currentCount + 1;
                        }

                        // Restore modal item and update button text after showing success
                        if (examItem && examItem.dataset.originalHtml) {
                            setTimeout(() => {
                                examItem.innerHTML = examItem.dataset.originalHtml;
                                const assignBtn = examItem.querySelector('button');
                                if (assignBtn) {
                                    assignBtn.classList.remove('btn-success');
                                    assignBtn.classList.add('btn-secondary');
                                    assignBtn.innerHTML = '<i class="fa fa-check"></i> تعيين مرة أخرى';
                                }
                            }, 600);
                        }

                        setTimeout(() => {
                            // Close modal using jQuery (Bootstrap 4)
                            if (typeof jQuery !== 'undefined') {
                                jQuery('#assignExamModal').modal('hide');
                            } else if (typeof bootstrap !== 'undefined') {
                                // Fallback for Bootstrap 5
                                const modal = bootstrap.Modal.getInstance(document.getElementById('assignExamModal'));
                                if (modal) modal.hide();
                            }
                        }, 800);
                    } else {
                        // Show error if request fails
                        setModalMessage('danger', '<i class="fa fa-times-circle"></i> ' + (data.message || 'حدث خطأ أثناء التعيين'));
                        if (examItem && examItem.dataset.originalHtml) {
                            examItem.innerHTML = examItem.dataset.originalHtml;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    setModalMessage('danger', '<i class="fa fa-times-circle"></i> حدث خطأ أثناء التعيين');
                    if (examItem && examItem.dataset.originalHtml) {
                        examItem.innerHTML = examItem.dataset.originalHtml;
                    } else if (examItem) {
                        examItem.innerHTML = 'حدث خطأ. حاول مرة أخرى.';
                    }
                });
        }

        function setModalMessage(type, html) {
            const modalMessage = document.getElementById('modalMessage');
            const modalContent = document.getElementById('modalContent');
            if (!modalMessage) return;
            modalMessage.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-info');
            modalMessage.classList.add('alert-' + type);
            modalMessage.innerHTML = html;
            if (modalContent) {
                modalContent.classList.add('d-none');
            }
        }

        function clearModalMessage() {
            const modalMessage = document.getElementById('modalMessage');
            const modalContent = document.getElementById('modalContent');
            if (!modalMessage) return;
            modalMessage.classList.add('d-none');
            modalMessage.classList.remove('alert-success', 'alert-danger', 'alert-info');
            modalMessage.innerHTML = '';
            if (modalContent) {
                modalContent.classList.remove('d-none');
            }
        }

        function formatDateTime(date) {
            const pad = value => String(value).padStart(2, '0');
            const year = date.getFullYear();
            const month = pad(date.getMonth() + 1);
            const day = pad(date.getDate());
            const hours = pad(date.getHours());
            const minutes = pad(date.getMinutes());
            return `${year}-${month}-${day} ${hours}:${minutes}`;
        }

        function escapeHtml(value) {
            const div = document.createElement('div');
            div.textContent = value;
            return div.innerHTML;
        }

        // Unassign exam via AJAX
        function unassignExam(studentExamId, examTitle) {
            // Show confirmation modal
            const confirmMessage = document.getElementById('confirmMessage');
            const confirmButton = document.getElementById('confirmButton');

            if (confirmMessage) {
                confirmMessage.textContent = 'هل أنت متأكد من إلغاء تعيين الاختبار: ' + examTitle + '؟';
            }

            // Remove previous click handlers
            const newConfirmButton = confirmButton.cloneNode(true);
            confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);

            // Add new click handler
            newConfirmButton.addEventListener('click', function() {
                // Close modal
                if (typeof jQuery !== 'undefined') {
                    jQuery('#confirmModal').modal('hide');
                } else if (typeof bootstrap !== 'undefined') {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
                    if (modal) modal.hide();
                }

                const row = document.getElementById('exam-row-' + studentExamId);
                if (row) {
                    row.style.opacity = '0.5';
                }

                fetch('{{ route(Auth::getDefaultDriver() . '.students.unassignExam') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            student_exam_id: studentExamId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const successMessage = document.getElementById('successMessage');
                            const successText = document.getElementById('successText');
                            successText.textContent = data.message + ': ' + examTitle;
                            successMessage.classList.add('show');
                            successMessage.classList.remove('d-none');

                            // Hide the row with fadeout animation
                            if (row) {
                                row.style.transition = 'opacity 0.5s ease';
                                row.style.opacity = '0';
                                setTimeout(() => {
                                    row.remove();
                                    // Update count
                                    const countEl = document.getElementById('assignedExamCount');
                                    if (countEl) {
                                        const currentCount = parseInt(countEl.textContent, 10) || 0;
                                        countEl.textContent = Math.max(0, currentCount - 1);
                                    }

                                    // Check if table is now empty
                                    const tbody = document.getElementById('assignedExamsTableBody');
                                    if (tbody && tbody.children.length === 0) {
                                        const container = document.getElementById('assignedExamsContainer');
                                        if (container) {
                                            container.innerHTML = '<div class="alert alert-info text-center" role="alert"><i class="fa fa-info-circle"></i> لا توجد اختبارات مسجلة لهذا الطالب حتى الآن</div>';
                                        }
                                    }
                                }, 500);
                            }
                        } else {
                            alert(data.message || 'حدث خطأ أثناء إلغاء التعيين');
                            if (row) {
                                row.style.opacity = '1';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء إلغاء التعيين');
                        if (row) {
                            row.style.opacity = '1';
                        }
                    });
            });

            // Show the confirmation modal
            if (typeof jQuery !== 'undefined') {
                jQuery('#confirmModal').modal('show');
            } else if (typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
                modal.show();
            }
        }
    </script>

    <style>
        .exam-item {
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
            margin-bottom: 0.75rem;
            border-radius: 0.375rem;
            background-color: #fff;
        }

        .exam-item:hover {
            background-color: #f8f9fa;
            border-color: #0d6efd;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .exam-item button {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .exam-item button:not(:disabled) {
            cursor: pointer;
        }

        #examsList .list-group-item {
            border: 1px solid #dee2e6;
        }

        .table th {
            white-space: nowrap;
        }
    </style>
@endsection
