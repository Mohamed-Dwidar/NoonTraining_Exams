@extends('layoutmodule::layouts.main')

@section('title')
    الأسئلة
@endsection

@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-question-circle"></i>
                    &nbsp;
                    الأسئلة
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8"></div>
                                <div class="col-lg-4">
                                    <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                        href="{{ route(Auth::getDefaultDriver() . '.question.create') }}"
                                        role="button">إضافة أسئلة جديدة</a>

                                    <button class="btn btn-primary round btn-min-width mr-1 mb-1" data-toggle="modal"
                                        data-target="#importModal" type="button">استيراد الأسئلة</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>السؤال</th>
                                            <th>النوع</th>
                                            <th>التصنيف</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($questions)
                                            @foreach ($questions as $question)
                                                <tr>
                                                    <td class="strong">{{ Str::limit($question->question_text, 60) }}</td>
                                                    <td>
                                                        @if ($question->type == 'mcq')
                                                            <span class="badge badge-primary">اختياري</span>
                                                        @else
                                                            <span class="badge badge-success">صح/خطأ</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $question->category->name ?? 'غير محدد' }}</td>
                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.question.edit', $question->id) }}"
                                                            role="button">تعديل</a>

                                                        <a class="btn btn-danger"
                                                            href="{{ route(Auth::getDefaultDriver() . '.question.delete', $question->id) }}"
                                                            onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                            role="button">حذف</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">لا توجد أسئلة</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $questions->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Import Questions Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">استيراد الأسئلة من ملف Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="importForm" action="{{ route(Auth::getDefaultDriver() . '.questions.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div id="formContent">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">اختر التصنيف <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control" required>
                                            <option value="">اختر التصنيف</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="question_type">نوع الأسئلة <span class="text-danger">*</span></label>
                                        <select name="question_type" id="question_type" class="form-control" required>
                                            <option value="">اختر النوع</option>
                                            <option value="mcq">اختيار من متعدد</option>
                                            <option value="true_false">صح/خطأ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="fileInput">اختر ملف Excel <span class="text-danger">*</span></label>
                                        <input type="file" name="file" id="fileInput" class="form-control"
                                            accept=".xlsx,.xls,.csv" required>
                                        <small class="form-text text-muted">الصيغ المدعومة: Excel (.xlsx, .xls)</small>
                                        <a href="{{ asset('imports/questions_mcq_template.xlsx') }}"
                                            class="btn btn-sm btn-info mt-2 badge info" style="color: #ffffff !important;"
                                            download>
                                            <i class="fa fa-download"></i>
                                            تحميل نموذج ملف اسئلة اختيار من متعدد</a>

                                        <br />

                                        <a href="{{ asset('imports/questions_true_false_template.xlsx') }}"
                                            class="btn btn-sm btn-info mt-2 badge info" style="color: #ffffff !important;"
                                            download>
                                            <i class="fa fa-download"></i>
                                            تحميل نموذج ملف اسئلة صح/خطأ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="uploadingMessage" style="display: none;" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">جاري التحميل...</span>
                            </div>
                            <p class="mt-3">جاري رفع الملف واستيراد الأسئلة...</p>
                        </div>
                    </div>
                    <div class="modal-footer" id="modalFooter">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">استيراد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Import form handling
                $('#importForm').on('submit', function(e) {
                    // Hide the form content and footer
                    $('#formContent').hide();
                    $('#modalFooter').hide();

                    // Show the uploading message
                    $('#uploadingMessage').show();
                });

                // Reset modal when it's closed
                $('#importModal').on('hidden.bs.modal', function() {
                    $('#formContent').show();
                    $('#modalFooter').show();
                    $('#uploadingMessage').hide();
                    $('#importForm')[0].reset();
                });
            });
        </script>
    @endpush
@endsection
