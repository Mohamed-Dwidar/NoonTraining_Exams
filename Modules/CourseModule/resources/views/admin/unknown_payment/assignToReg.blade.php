@extends('layoutmodule::admin.main')

@section('title', 'ربط بالحجز')

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>ربط بالحجز</h3>
            </div>
        </div>

        @include('layoutmodule::admin.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-content">
                            <form class="card-form side-form" method="POST"
                                action="{{ route(Auth::getDefaultDriver() . '.unknown_payment.assign_payment') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ old('id', $unknown_payment->id) }}">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="transferor_name" style="font-weight: bold;">اسم المحول:</label>
                                            <span class="form-control-static">{{ $unknown_payment->transferor_name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="amount" style="font-weight: bold;">المبلغ المحول:</label>
                                            <span class="form-control-static">{{ $unknown_payment->amount }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="paid_at" style="font-weight: bold;">تاريخ التحويل:</label>
                                            <span class="form-control-static">{{ $unknown_payment->paid_at }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="pay_method" style="font-weight: bold;">طريقة التحويل:</label>
                                            <span class="form-control-static">{{ $unknown_payment->pay_method }}</span>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="course" style="font-weight: bold;">الدورة </label>
                                        <div class="form-group">
                                            <select id="course" name="course" class="form-control">
                                                <option value="">اختر الدورة</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->fullName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <label for="reg_id" style="font-weight: bold;">
                                            الطالب <span class="hint">*</span>
                                        </label>
                                        <div class="form-group">
                                            <select id="reg_id" name="reg_id" class="form-control">
                                                <option value="">اختر الطالب</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                    </div>
                                </div>
                            </form>
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
                });

                $('#course').change(function() {
                    var courseId = $(this).val();
                    if (courseId) {
                        $('#reg_id').empty();
                        $('#reg_id').append('<option value="">جاري التحميل...</option>');
                        $.ajax({
                            url: '/{{ Auth::getDefaultDriver() }}/unknown_payment/get-students/' + courseId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#reg_id').empty();
                                $('#reg_id').append('<option value="">اختر الطالب</option>');
                                $.each(data, function(key, value) {
                                    $('#reg_id').append('<option value="' + value.reg_id + '" data-regid="' + value.reg_id + '">' + value.student_name + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#reg_id').empty();
                        $('#reg_id').append('<option value="">اختر الطالب</option>');
                    }
                });

                // $('#student').change(function() {
                //     var regId = $(this).find(':selected').data('regid');
                //     $('#reg_id').val(regId);
                // });
            });
        </script>
    @endpush
@endsection
