@extends('layoutmodule::admin.main')

@section('title')
    تعديل تحويل
@endsection

@push('styles')
    <link href="{{ url('/admin-assets/vendors/js/pickers/hijri-date-picker/dist/css/bootstrap-datetimepicker.css?v2') }}"
        rel="stylesheet" />
@endpush


@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    تعديل تحويل
                </h3>
            </div>
        </div>

        @include('layoutmodule::admin.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        {{-- <div class="card-header">
                        <div class="row">
                            <div class="col-5">
                                <h2> Course Information</h2>
                            </div>
                        </div>
                    </div> --}}
                        <div class="card-content">
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <form class="card-form side-form" method="POST"
                                        action='{{ route(Auth::getDefaultDriver() . '.unknown_payment.update') }}'
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ old('id', $unknown_payment->id) }}">

                                        <div class="row">
                                            <div class="col-lg-3 col-sm-12 col-xs-12 col-2">
                                                <label for="transferor_name">اسم المحول <span
                                                        class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="transferor_name"
                                                        name="transferor_name"
                                                        value="{{ old('transferor_name', $unknown_payment->transferor_name) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-2">
                                                <label for="amount">المبلغ المحول <span class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="number" class="form-control" id="amount" name="amount"
                                                        value="{{ old('amount', $unknown_payment->amount) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-2">
                                                <label for="paid_at">تاريخ التحويل <span class="hint">*</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control hijri-datepicker"
                                                        id="paid_at" name="paid_at"
                                                        value="{{ old('paid_at', $unknown_payment->paid_at) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-2">
                                                <label for="pay_method">طريقة التحويل<span class="hint">*</span></label>
                                                <div class="form-group">
                                                    <select id="pay_method" name="pay_method" class="form-control">
                                                        <option value="كاش"
                                                            @if (old('pay_method', $unknown_payment->pay_method) == 'كاش') selected @endif>كاش</option>
                                                        <option value="تحويل"
                                                            @if (old('pay_method', $unknown_payment->pay_method) == 'تحويل') selected @endif>تحويل</option>
                                                        <option value="شبكة"
                                                            @if (old('pay_method', $unknown_payment->pay_method) == 'شبكة') selected @endif>شبكة</option>
                                                        <option value="تابي"
                                                            @if (old('pay_method', $unknown_payment->pay_method) == 'تابي') selected @endif>تابي</option>
                                                        <option value="تمارا"
                                                            @if (old('pay_method', $unknown_payment->pay_method) == 'تمارا') selected @endif>تمارا</option>
                                                        <option value="يور واي"
                                                            @if (old('pay_method', $unknown_payment->pay_method) == 'يور واي') selected @endif>يور واي</option>
                                                        <option value="أخرى"
                                                            @if (old('pay_method', $unknown_payment->pay_method) == 'أخرى') selected @endif>أخرى</option>


                                                    </select>
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
        {{-- <script type="text/javascript">
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
        </script> --}}

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
