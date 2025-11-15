@extends('layoutmodule::admin.main')

@section('title')
    اضافه دورة جديدة
@endsection

@push('styles')
    <link href="{{ url('/admin-assets/vendors/js/pickers/hijri-date-picker/dist/css/bootstrap-datetimepicker.css?v2') }}"
        rel="stylesheet" />
@endpush

@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-plus"></i>
                    اضافه دورة جديدة
                </h3>
            </div>
        </div>

        @include('layoutmodule::admin.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <form class="card-form side-form" method="POST"
                                        action='{{ route(Auth::getDefaultDriver() . '.courses.store') }}'
                                        enctype="multipart/form-data">
                                        @csrf

                                        @if (Auth::guard('admin')->check())
                                            <div class="row">
                                                <div class="col-lg-2 col-sm-12 col-xs-12 col-2">
                                                    <label for="branch_id">الفرع <span class="hint">*</span></label>
                                                    <div class="form-group">
                                                        <select class="form-control" id="branch_id" name="branch_id">
                                                            @if (!empty($branches))
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}"
                                                                        @if (old('branch_id') == $branch->id) selected @endif>
                                                                        {{ $branch->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-4">
                                                <label for="name">اسم الدورة <span class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        value="{{ old('name') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-2">
                                                <label for="group_nu">رقم الجروب <span class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="group_nu"
                                                        name="group_nu" value="{{ old('group_nu') }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-12 col-xs-12 col-3">
                                                <label for="course_org_nu">رقم الدورة في المؤسسة العامة للندريب المهني و
                                                    التقني <span class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="course_org_nu"
                                                        name="course_org_nu" value="{{ old('course_org_nu') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-2">
                                                <label for="start_at">تاريخ بداية الدورة <span
                                                        class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control hijri-datepicker"
                                                        id="start_at" name="start_at" value="{{ old('start_at') }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-2">
                                                <label for="end_at">تاريخ انتهاء الدورة <span
                                                        class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control hijri-datepicker"
                                                        id="end_at" name="end_at" value="{{ old('end_at') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-2">
                                                <label for="price">سعر الدورة <span class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="price" name="price"
                                                        value="{{ old('price') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-2">
                                                <label for="exam_fees">رسوم الاختبار <span class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="exam_fees"
                                                        name="exam_fees" value="{{ old('exam_fees') }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">حفظ</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-1 col-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {

                $('.pickadate').pickadate({
                    weekdaysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    format: 'dd-mm-yyyy',
                    formatSubmit: 'yyyy-mm-dd',
                    showMonthsShort: true,
                    today: false,
                    close: 'Close',
                    // clear: '[All]',
                    // onSet: function (context) {
                    //     getInvoicesByDate();
                    // }
                });
            });
        </script>


        <script
            src="{{ url('/admin-assets/vendors/js/pickers/hijri-date-picker/dist/js/bootstrap-hijri-datetimepicker.min.js?v2') }}">
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                initHijrDatePickerDefault();
            });

            function initHijrDatePickerDefault() {
                $(".hijri-datepicker").hijriDatePicker({
                    locale: "ar-sa",
                    format: "DD-MM-YYYY",
                    actualFormat: "DD-MM-YY",
                    hijriFormat: "iYYYY-iMM-iDD",
                    dayViewHeaderFormat: "MMMM YYYY",
                    hijriDayViewHeaderFormat: "iMMMM iYYYY",
                    showSwitcher: false,
                    allowInputToggle: true,
                    useCurrent: true,
                    isRTL: true,
                    viewMode: 'days',
                    keepOpen: false,
                    hijri: true,
                    debug: true,
                    showClear: false,
                    showTodayButton: false,
                    showClose: false,
                });
            }
        </script>
    @endpush


@endsection
