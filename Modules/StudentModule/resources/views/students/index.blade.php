@extends('layoutmodule::layouts.main')

@section('title')
    الطلاب
@endsection

@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-graduation-cap"></i>
                    &nbsp;
                    الطلاب
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
                                <div class="col-lg-8">
                                    <form method="GET" action="{{ route(Auth::getDefaultDriver() . '.students.index') }}"
                                        class="d-flex">
                                        <div class="input-group">
                                            <input type="text" name="search" id="searchInput" class="form-control"
                                                placeholder="بحث عن طالب..." value="{{ request()->query('search') }}" style="width: 400px">
                                            &nbsp;&nbsp;&nbsp;
                                            <button class="btn btn-outline-secondary" type="submit" style="margin-top:5px">بحث</button>
                                            &nbsp;
                                            <button class="btn btn-outline-danger" type="button"  style="margin-top:5px"
                                                id="clearBtn">مسح</button>

                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-4">
                                    <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                        href="{{ route(Auth::getDefaultDriver() . '.students.create') }}"
                                        role="button">إضافة طالب جديد</a>

                                    <button class="btn btn-primary round btn-min-width mr-1 mb-1" data-toggle="modal"
                                        data-target="#importModal" type="button">استيراد الطلاب</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>الاسم</th>
                                            <th>رقم الهوية</th>
                                            <th>الهاتف</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($students)
                                            @foreach ($students as $student)
                                                <tr>
                                                    <td class="strong">{{ $student->name }}</td>
                                                    <td>{{ $student->national_id ?? '-' }}</td>
                                                    <td>{{ $student->phone ?? '-' }}</td>
                                                    <td>{{ $student->email ?? '-' }}</td>
                                                    <td>
                                                        <a class="btn btn-info"
                                                            href="{{ route(Auth::getDefaultDriver() . '.students.showExams', $student->id) }}"
                                                            role="button">إستعراض الأختبارات</a>

                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.students.edit', $student->id) }}"
                                                            role="button">تعديل</a>

                                                        <form
                                                            action="{{ route(Auth::getDefaultDriver() . '.students.delete', $student->id) }}"
                                                            method="POST" class="d-inline-block">
                                                            @csrf
                                                            <button onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                                class="btn btn-danger">حذف</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center">لا يوجد طلاب</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $students->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Import Students Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">استيراد الطلاب من ملف Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="importForm" action="{{ route(Auth::getDefaultDriver() . '.students.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div id="formContent">
                            <div class="form-group">
                                <label for="fileInput">اختر ملف Excel لاستيراد الطلاب</label>
                                <input type="file" name="file" id="fileInput" class="form-control"
                                    accept=".xlsx,.xls,.csv" required>
                                <small class="form-text text-muted">الصيغ المدعومة: (.xlsx, .xls)</small>
                                <a href="http://127.0.0.1:8001/assets/templates/walkover_template.xlsx"
                                    class="btn btn-sm btn-info mt-2 badge info" style="color: #ffffff !important;" download>
                                    تحميل نموذج الملف</a>
                            </div>
                        </div>
                        <div id="uploadingMessage" style="display: none;" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">جاري التحميل...</span>
                            </div>
                            <p class="mt-3">جاري رفع الملف واستيراد الطلاب...</p>
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

                // Clear search button
                $('#clearBtn').on('click', function() {
                    $('#searchInput').val('');
                    window.location.href = "{{ route(Auth::getDefaultDriver() . '.students.index') }}";
                });
            });
        </script>
    @endpush
@endsection
