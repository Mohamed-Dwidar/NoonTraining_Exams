@extends('layoutmodule::admin.main')

@section('title')
    {{ $course->name }}
@endsection

@push('styles')
<link href="{{url('/admin-assets/vendors/js/pickers/hijri-date-picker/dist/css/bootstrap-datetimepicker.css?v2')}}"
    rel="stylesheet" />
@endpush


@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-desktop"></i>
                    &nbsp;
                    {{-- {{ $course->id }} --}}
                    {{ $course->FullName }}
                    ({{ $course->start_at }} - {{ $course->end_at }})
                </h3>
            </div>
        </div>

        @include('layoutmodule::admin.flash')

        <div class="content-body">
            <div class="row">

                <!-- Course Info -->
                <div class="col-xl-12 col-lg-12 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block profile course-view">
                                <div class="row">

                                    <div class="col-lg-3 col-md-2 col-sm-6 col-xs-6 col label">
                                        تبدأ في :
                                        &nbsp;
                                        <span class="font-normal">{{ $course->start_at }}</span>

                                        &nbsp;&nbsp;
                                        تنتهي في :
                                        &nbsp;
                                        <span class="font-normal">{{ $course->end_at }}</span>
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 col label">
                                        سعر الدورة :
                                        &nbsp;
                                        <span class="font-normal">{{ $course->price }} ر.س</span>
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 col label">
                                        رسوم الاختبار :
                                        &nbsp;
                                        <span class="font-normal">{{ $course->exam_fees }} ر.س</span>
                                    </div>

                                    {{-- <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 col label">
                                        الفرع :
                                        &nbsp;
                                        <span class="font-normal">{{ $course->branch->name }}</span>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- -->

                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-graduation-cap teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $courses_regs->count() }}</h3>
                                        <h5>عدد الطلاب</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- @if (in_array('can_manage_money', auth()->user()->privileges_keys())) --}}
                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="icon icon-moneybag teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $courses_regs->where('is_free',0)->sum('price') }}</h3>
                                        <h5>إجمالي الإيراد</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="icon icon-money1 teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <?php
                                    // $paid = 0;
                                    // if (!empty($courses_regs)) {
                                    //     foreach ($courses_regs as $one_reg) {
                                    //         $paid = $paid + $one_reg->payments->sum('coursePaidAmount');
                                    //     }
                                    // }
                                    ?>
                                    <div class="media-body text-xs-right">
                                        {{-- <h3 class="teal">{{ $paid }}</h3> --}}
                                        <h3 class="teal">{{ $courses_regs->sum('coursePaidAmount') }}</h3>
                                        <h5>إجمالي المدفوع</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-money teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $courses_regs->where('is_free',0)->sum('price') - $courses_regs->sum('coursePaidAmount') }}</h3>
                                        <h5>إجمالي المتبقي</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endif --}}
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">

                                <div class="col-lg-5 col-md-4 col-sm-3 col-xs-12">
                                    {{-- {{dd()}} --}}
                                    <div class="filters">
                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="icon-filter"></i>
                                            فلتر
                                        </a>
                                        <div class="dropdown-menu arrow dropdown-filter">
                                            <input type="hidden" id="fltr_val" value="@if (app('request')->fltr != null) {{ app('request')->fltr }} @endif" />

                                            <button class="dropdown-item filter-item-reg" type="button" data-val="0">
                                                الكل
                                            </button>
                                            @foreach ($statuses as $status)
                                                <button class="dropdown-item filter-item-reg" type="button" style="background-color: {{$status->color}}" data-val="{{$status->id}}">
                                                {{$status->status}}
                                            </button>
                                            @endforeach
                                                <button class="dropdown-item filter-item-reg" type="button" data-val="101">
                                                    مستلمي الشهادة
                                                </button>
                                                <button class="dropdown-item filter-item-reg" type="button" data-val="100">
                                                    غير مستلمي الشهادة
                                                </button>
                                        </div>
                                    </div>


                                    <div class="filters" style="display: none">
                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="icon-sort"></i>
                                            ترتيب
                                        </a>
                                        <div class="dropdown-menu arrow dropdown-sort">
                                            <input type="hidden" id="sort_val"
                                                value="@if (app('request')->srt != null) {{ app('request')->srt }} @endif" />

                                            <button class="dropdown-item sort-item-reg" type="button" data-val="no">
                                                افتراضي
                                            </button>
                                            <button class="dropdown-item sort-item-reg" type="button"
                                                data-val="date_az">
                                                تاريخ التقديم الاقدم
                                            </button>
                                            <button class="dropdown-item sort-item-reg" type="button"
                                                data-val="date_za">
                                                تاريخ التقديم الاحدث
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-5 col-md-6 col-sm-9 col-xs-12">
                                    <div class="header-search">
                                        <label>بحث</label>
                                        <input value='@if (app('request')->srch != null){{ app('request')->srch }}@endif' id="srchInput-reg" />
                                        <button class="srch-icon-reg">
                                            <i class="icon-search7"></i>
                                        </button>
                                        <a href="#" class="clear-reg">إلغاء</a>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    {{-- @if (in_array('can_manage_money', auth()->user()->privileges_keys())) --}}
                                     {{-- <div class="filters">
                                        <a id="export">
                                            <i class="icon-file-excel"></i>
                                            تصدير ملف اكسل
                                        </a>
                                    </div>   --}}
                                    {{-- @endif --}}
                                </div>

                                <div class="col-lg-4 col-md-4" style="display: none">
                                    <div class="fltr-date-range-reg">
                                        <label>الفترة:</label>
                                        من
                                        <input
                                            value='@if (app('request')->dateRngFrm != null) {{ app('
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    request')->dateRngFrm }} @endif'
                                            id="dateRngFrm" class="hijri-datepicker" />
                                        &nbsp;
                                        الي
                                        <input
                                            value='@if (app('request')->dateRngTo != null) {{ app('
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    request')->dateRngTo }} @endif'
                                            id="dateRangTo" class="hijri-datepicker" />

                                        <button class="drnge-icon-reg">
                                            <i class="icon-search7"></i>
                                        </button>
                                        <a href="#" class="clear-dateRang">إلغاء</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @if ($courses_regs->count() > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr class="head">
                                                {{-- <th style="padding: 0.75rem 0;width:40px;">&nbsp;</th> --}}
                                                <th>الطالب</th>
                                                {{-- <th>تاريخ التقديم</th> --}}
                                                <th>السعر المتفق عليه</th>
                                                <th>المدفوع</th>
                                                <th>الباقي</th>
                                                {{-- <th style="text-align: center">سدد رسوم الاختبار</th> --}}
                                                <th class="align-center">الحالة</th>
                                                <th style="text-align: center">استلم الشهادة</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            {{-- {{dd($courses_regs->toArray())}} --}}
                                            @foreach ($courses_regs as $reg)
                                                <tr id="reg{{ $reg->id }}">
                                                    {{-- <td class="strong" style="padding: 0.75rem 0;background-color: {{$reg->status->color}} ">&nbsp;</td> --}}
                                                    <td class="strong">
                                                        <label
                                                            for="{{ $reg->id }}">{{ $reg->student->name }}</label>
                                                    </td>
                                                    {{-- <td>{{$reg->created_at}}</td> --}}
                                                    <td>
                                                        <div>
                                                            <span class="regprice" reg_id="{{ $reg->id }}">{{ $reg->price }}</span>
                                                            @if ($reg->main_price != $reg->price)                                                            
                                                            <br>
                                                            <span class="small" style="text-decoration: line-through">{{ $reg->main_price }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>{{ number_format($reg->coursePaidAmount,2) }}</td>
                                                    <td>
                                                        @if ($reg->is_free == 1)
                                                            0.00
                                                        @else
                                                        <div>
                                                            <span class="remaining_amount" reg_id="{{ $reg->id }}">{{ number_format($reg->price - $reg->coursePaidAmount, 2) }}</span>
                                                        </div>                                                        
                                                        @endif
                                                    </td>
                                                    {{-- <td class="align-center">
                                                        @if($reg->is_exam_paid == 0)
                                                        <i class="fa fa-close red list-boolean-icon"></i>
                                                        @else
                                                        <i class="fa fa-check green list-boolean-icon"></i>                                                        
                                                        @endif
                                                    </td> --}}
                                                    <td class="align-center paid_status" style="font-weight:bold; background-color: {{$reg->status->color}} ">
                                                        <div style="display: none" class="paid_status_id">{{$reg->is_course_paid}}</div>
                                                        <div style="display: none" class="paid_exam_id">{{$reg->is_exam_paid}}</div>
                                                        
                                                        @if ($reg->is_leave == 1)
                                                        [ مغادر ] &nbsp;&nbsp;&nbsp;
                                                        @endif
                                                        {{$reg->status->status}}
                                                    </td>

                                                    <td class="align-center">
                                                        @if($reg->is_recive_cert == 0)
                                                        <i class="fa fa-close red list-boolean-icon"></i>
                                                        @else
                                                        <i class="fa fa-check green list-boolean-icon"></i>                                                        
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <a class="btn-sm btn-blue one-reg" href="#"
                                                            id="{{ $reg->id }}" role="button" data-toggle="modal"
                                                            data-target="#modal-reg-{{ $reg->id }}">التفاصيل</a>

                                                        <a class="btn-sm btn-primary one-pay" href="#"
                                                            id="{{ $reg->id }}" role="button" data-toggle="modal"
                                                            data-target="#modal-pay-{{ $reg->id }}">الدفعات</a>
                                                         

                                                       @cannot('view_only')
                                                            @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                <a class="btn-sm btn-warning one-status" href="#"
                                                                id="{{ $reg->id }}" role="button" data-toggle="modal"
                                                                data-target="#modal-status-{{ $reg->id }}">تغيير حالة الدفع</a>
                                                            @endif

                                                            @if (Auth::guard('admin')->check() || Auth::user()->can('can_delete'))
                                                                <a class="btn-sm btn-danger"
                                                                    href="{{ route(Auth::getDefaultDriver().'.courses.delete_reg', $reg->id) }}"
                                                                    onclick="return confirm('هل انت متأكد انك تريد حذف هذا الطلب ؟')"
                                                                    role="button">حذف</a>
                                                            @endif
                                                        @endcannot
                                                    </td>
                                                </tr>

                                                <div class="modal fade text-xs-left reg-modal"
                                                    id="modal-reg-{{ $reg->id }}" tabindex="-1" role="dialog"
                                                    aria-labelledby="myModalLabel90" aria-hidden="true">
                                                    <div class="modal-dialog" role="document" style="max-width: 800px;">
                                                        <div class="modal-content">
                                                            <div id="reg-{{ $reg->id }}-info" style="">
                                                                <div class="modal-body content-reports reg-modal"
                                                                    style="padding:0">
                                                                    <div class="statistic-table custom-bar">
                                                                        <div class="content-body">
                                                                            <div class="card">
                                                                                <div class="card-header">
                                                                                    <h4 class="card-title mb-0">
                                                                                        الطالب :
                                                                                        <span class="student-name">{{ $reg->student->name }}</span>
                                                                                    </h4>
                                                                                    <div class="buttoms icons">
                                                                                        <div id="copy-button-{{ $reg->id }}" class=" d-flex align-items-center mr-2">
                                                                                            <i class="fa fa-copy" title="Copy Info"></i><span class="ml-1">نسخ البيانات</span>
                                                                                        </div>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="card-block reg-template">
                                                                                    <div class="card-text col-md-12">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 align-center" style="padding:10px 0;margin-top:-15px;margin-bottom: 25px;font-weight:bold; background-color: {{$reg->status->color}} ">
                                                                                                @if ($reg->is_leave == 1)
                                                                                                [ مغادر ] &nbsp;&nbsp;&nbsp;
                                                                                                @endif
                                                                                                {{$reg->status->status}}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row content-header">
                                                                                            <div
                                                                                                class="content-header-left col-md-12 col-xs-12 mb-1">
                                                                                                <h4 class="form-section">
                                                                                                    <i
                                                                                                        class="icon-desktop"></i>
                                                                                                    بيانات الدورة
                                                                                                </h4>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <div
                                                                                                    class="col-lg-2 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                    الدورة
                                                                                                </div>
                                                                                                <div class="col-lg-8 col-md-4 col-sm-12 col-xs-12 col course-name">
                                                                                                    {{ $reg->course->FullName }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <div
                                                                                                    class="col-lg-2 col-md-3 col-sm-12 col-xs-12 col label font-bold">
                                                                                                    تبدأ في</div>
                                                                                                <div
                                                                                                    class="col-lg-4 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                    {{ $reg->course->start_at }}
                                                                                                </div>

                                                                                                <div
                                                                                                    class="col-lg-2 col-md-3 col-sm-12 col-xs-12 col label font-bold">
                                                                                                    تنتهي في</div>
                                                                                                <div
                                                                                                    class="col-lg-4 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                    {{ $reg->course->end_at }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div> 

                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <input type="hidden" id="main_price" value="{{$reg->main_price}}" reg_id="{{ $reg->id }}"/>
                                                                                                <input type="hidden" id="paid_amount" value="{{$reg->coursePaidAmount}}" reg_id="{{ $reg->id }}"/>
                                                                                                <div class="col-lg-5 col-md-3 col-sm-12 col-xs-12 col label font-bold">السعر الأساسي</div>
                                                                                                <div class="col-lg-7 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                    <span>
                                                                                                         <span>{{ $reg->main_price }}</span>
                                                                                                        ر.س
                                                                                                    </span>
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <div class="col-md-6 newPrice" style="display: none">
                                                                                                <input type="hidden" id="reg_id" name="reg_id" value="{{ $reg->id }}" />
                                                                                                <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 col label font-bold"> السعر الجديد</div>
                                                                                                <div class="col-lg-8 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                    <span>
                                                                                                        <input type="text" id="new_price" name="new_price" value="" reg_id="{{ $reg->id }}" style="width: 63px" />
                                                                                                    </span>

                                                                                                    <a class="btn btn-green savePrice" reg_id="{{ $reg->id }}">حفظ </a>
                                                                                                    <a class="btn btn-red resetSave">إلغاء التغير </a>
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <div class="col-md-6 mainCost" reg_id="{{ $reg->id }}">
                                                                                                <div class="col-lg-5 col-md-3 col-sm-12 col-xs-12 col label font-bold">السعر المتفق عليه</div>
                                                                                                <div class="col-lg-7 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                    <span class="student_price">{{ $reg->student_price }}</span>
                                                                                                    ر.س
                                                                                                    @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                                                    <a class="btn btn-blue changePrice">تغير السعر</a>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-6 discountPrice" reg_id="{{ $reg->id }}">
                                                                                                <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 col label font-bold">قيمة الخصم</div>
                                                                                                <div class="col-lg-8 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                    <span class="discount-value-text" reg_id="{{ $reg->id }}">{{ number_format($reg->DiscountAmount ?? 0, 2) }} ر.س</span>
                                                                                                    @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                                                    <button type="button" class="btn btn-blue btn-sm edit-discount-btn" reg_id="{{ $reg->id }}">تغيير</button>
                                                                                                    <span class="discount-edit-group" reg_id="{{ $reg->id }}" style="display:none;">
                                                                                                        <input type="number" min="0" step="0.01" id="discount-input" class="discount-input" reg_id="{{ $reg->id }}" value="{{ $reg->DiscountAmount ?? 0 }}" style="width: 80px; display: inline-block;" />
                                                                                                        <button type="button" class="btn btn-green btn-sm save-discount-btn save-btn" reg_id="{{ $reg->id }}">حفظ</button>
                                                                                                        <button type="button" class="btn btn-red btn-sm cancel-discount-btn cancel-btn" reg_id="{{ $reg->id }}">إلغاء</button>
                                                                                                    </span>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-6 finalPrice" reg_id="{{ $reg->id }}">
                                                                                                <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 col label font-bold">السعر النهائي</div>
                                                                                                <div class="col-lg-8 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                    <span class="price" reg_id="{{ $reg->id }}">{{ number_format($reg->price, 2) }}</span>
                                                                                                    ر.س
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <script>
                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                document.querySelectorAll('.edit-discount-btn').forEach(function(btn) {
                                                                                                    btn.addEventListener('click', function() {
                                                                                                        var regId = this.getAttribute('reg_id');
                                                                                                        document.querySelector('.discount-value-text[reg_id="' + regId + '"]').style.display = 'none';
                                                                                                        this.style.display = 'none';
                                                                                                        document.querySelector('.discount-edit-group[reg_id="' + regId + '"]').style.display = 'inline-block';
                                                                                                    });
                                                                                                });
                                                                                                document.querySelectorAll('.cancel-discount-btn').forEach(function(btn) {
                                                                                                    btn.addEventListener('click', function() {
                                                                                                        var regId = this.getAttribute('reg_id');
                                                                                                        document.querySelector('.discount-edit-group[reg_id="' + regId + '"]').style.display = 'none';
                                                                                                        document.querySelector('.discount-value-text[reg_id="' + regId + '"]').style.display = 'inline';
                                                                                                        document.querySelector('.edit-discount-btn[reg_id="' + regId + '"]').style.display = 'inline-block';
                                                                                                    });
                                                                                                });
                                                                                                document.querySelectorAll('.save-discount-btn').forEach(function(btn) {
                                                                                                    btn.addEventListener('click', function() {
                                                                                                        var regId = this.getAttribute('reg_id');
                                                                                                        var input = document.querySelector('.discount-input[reg_id="' + regId + '"]');
                                                                                                        var value = parseFloat(input.value) || 0;
                                                                                                        document.querySelector('.discount-value-text[reg_id="' + regId + '"]').textContent = value.toFixed(2);
                                                                                                        document.querySelector('.discount-edit-group[reg_id="' + regId + '"]').style.display = 'none';
                                                                                                        document.querySelector('.discount-value-text[reg_id="' + regId + '"]').style.display = 'inline';
                                                                                                        document.querySelector('.edit-discount-btn[reg_id="' + regId + '"]').style.display = 'inline-block';
                                                                                                        // تحديث السعر النهائي
                                                                                                        var priceElem = document.querySelector('.mainCost[reg_id="' + regId + '"] .student_price');
                                                                                                        var finalPriceElem = document.querySelector('.price[reg_id="' + regId + '"]');
                                                                                                        var price = priceElem ? parseFloat(priceElem.textContent) : 0;
                                                                                                        var finalPrice = price - value;
                                                                                                        if(finalPriceElem) {
                                                                                                            finalPriceElem.textContent = finalPrice.toFixed(2);
                                                                                                        }
                                                                                                    });
                                                                                                });
                                                                                            });
                                                                                        </script>
                                                                                        <script>
                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                document.querySelectorAll('.discount-input').forEach(function(input) {
                                                                                                    input.addEventListener('input', function() {
                                                                                                        var regId = this.getAttribute('reg_id');
                                                                                                        var priceElem = document.querySelector('.mainCost[reg_id="' + regId + '"] .price');
                                                                                                        var finalPriceElem = document.querySelector('.price[reg_id="' + regId + '"]');
                                                                                                        var price = priceElem ? parseFloat(priceElem.textContent) : 0;
                                                                                                        var discount = parseFloat(this.value) || 0;
                                                                                                        var finalPrice = price - (price * discount / 100);
                                                                                                        if(finalPriceElem) {
                                                                                                            finalPriceElem.textContent = finalPrice.toFixed(2);
                                                                                                        }
                                                                                                    });
                                                                                                });
                                                                                            });
                                                                                        </script>

                                                                                        <div class="row">
                                                                                            <div class="col-md-12 mainExamFees" reg_id="{{ $reg->id }}">
                                                                                                <input type="hidden" id="main_exam_price" value="{{$reg->exam_fees}}" reg_id="{{ $reg->id }}"/>
                                                                                                <input type="hidden" id="paid_exam_amount" value="{{$reg->examPaidAmount}}" reg_id="{{ $reg->id }}"/>
                                                                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col label font-bold">رسوم الاختبار</div>
                                                                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col">
                                                                                                    <span class="exam_fees">{{ $reg->exam_fees }}</span>
                                                                                                    ر.س


                                                                                                    @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                                                    <a class="btn btn-blue changeExamPrice">تغير السعر</a>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-md-12 newExamPrice" style="display: none">
                                                                                                <input type="hidden" id="reg_id" name="reg_id" value="{{ $reg->id }}" />
                                                                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col label font-bold"> الرسوم الجديدة</div>
                                                                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col">
                                                                                                    <span>
                                                                                                        <input type="text" id="new_exam_price" name="new_exam_price" value="" reg_id="{{ $reg->id }}" style="width: 63px" />
                                                                                                    </span>

                                                                                                    <a class="btn btn-green save-btn saveExamPrice" reg_id="{{ $reg->id }}"> حفظ </a>
                                                                                                    <a class="btn btn-red cancel-btn resetExamSave">إلغاء التغير </a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>


                                                                                        <div class="row">
                                                                                            <div class="col-md-12 regBy" reg_id="{{ $reg->id }}">
                                                                                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col label font-bold">
                                                                                                    تم التسجيل عن طريق
                                                                                                </div>
                                                                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col regByText">
                                                                                                    <span>
                                                                                                        @if ($reg->registered_by)
                                                                                                            {{ $reg->registered_by }}
                                                                                                        @else
                                                                                                            ----
                                                                                                        @endif
                                                                                                    </span>
                                                                                                    @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                                                        <a class="btn btn-blue changeRegBy">تغير</a>
                                                                                                    @endif
                                                                                                </div>

                                                                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col newRegBy" style="display: none">
                                                                                                    <input type="hidden" id="reg_id" name="reg_id" value="{{ $reg->id }}" />
                                                                                                    <span>
                                                                                                        <input type="text" id="reg_by" name="reg_by" value="" reg_id="{{ $reg->id }}" style="width: 160px" />
                                                                                                    </span>

                                                                                                    <a class="btn btn-green save-btn saveRegBy" reg_id="{{ $reg->id }}"> حفظ </a>
                                                                                                    <a class="btn btn-red cancel-btn resetRegBy">إلغاء التغير </a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 regDate" reg_id="{{ $reg->id }}">
                                                                                                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 col label font-bold">تاريخ التقديم</div>
                                                                                                <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                    {{ \Carbon\Carbon::parse($reg->created_at)->format('d-m-Y') }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <div
                                                                                                    class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col label font-bold">
                                                                                                    تم استلام الشهادة </div>
                                                                                                <div
                                                                                                    class="col-lg-9 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                    @if ($reg->is_recive_cert == 1)
                                                                                                        نعم
                                                                                                    @else
                                                                                                        لا
                                                                                                    @endif
                                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;

                                                                                                    @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                                                    @if ($reg->is_recive_cert == 0)
                                                                                                        <a class="btn btn-blue cert"
                                                                                                            href="{{ route(Auth::getDefaultDriver().'.courses.set_cert_as_delivered', $reg->id) }}">
                                                                                                            تسجيل استلام
                                                                                                            الشهاده
                                                                                                        </a>
                                                                                                    @else
                                                                                                        <a class="btn btn-blue cert"
                                                                                                            href="{{ route(Auth::getDefaultDriver().'.courses.set_cert_as_not_delivered', $reg->id) }}"
                                                                                                            onclick="return confirm('هل انت متأكد انك تريد تسجيل عدم استلام الشهاده ؟')">
                                                                                                            تسجيل عدم استلام
                                                                                                            الشهاده
                                                                                                        </a>
                                                                                                    @endif
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>

                                                                                <div>
                                                                                    <hr>
                                                                                </div>

                                                                                <div class="card-block reg-template">
                                                                                    <div class="card-text col-md-12">
                                                                                        <div class="row content-header">
                                                                                            <div
                                                                                                class="content-header-left col-md-12 col-xs-12 mb-1">
                                                                                                <h4 class="form-section">
                                                                                                    <i class="icon-head"></i>
                                                                                                    بيانات الطالب
                                                                                                </h4>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                الاسم
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-8 col-md-4 col-sm-12 col-xs-12 col">
                                                                                                {{ $reg->student->name }}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row" style="display: none">
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                الفرع
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col">
                                                                                                {{ $reg->student->branch->name }}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                رقم الهوية / الإقامة
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col id-number">
                                                                                                {{ $reg->student->id_nu }}
                                                                                            </div>


                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                تاريخ الانتهاء
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                {{ $reg->student->id_expire_date }}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                الجوال
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col phone">
                                                                                                @if ($reg->student->phone1)
                                                                                                    {{ $reg->student->phone1 }}
                                                                                                @else
                                                                                                    ---
                                                                                                @endif
                                                                                            </div>

                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                جوال آخر للتواصل
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col">
                                                                                                @if ($reg->student->phone2)
                                                                                                    {{ $reg->student->phone2 }}
                                                                                                @else
                                                                                                    ---
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                المدينة
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col">
                                                                                                {{ $reg->student->city->name }}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                قطاع العمل
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-8 col-md-4 col-sm-12 col-xs-12 col">
                                                                                                {{ $reg->student->company }}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                البريد الألكتروني
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-4 col-md-4 col-sm-12 col-xs-12 col">
                                                                                                {{ $reg->student->email }}
                                                                                            </div>
                                                                                        </div>


                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                تاريخ الميلاد
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col">
                                                                                                @if ($reg->student->birthdate)
                                                                                                    {{ $reg->student->birthdate }}
                                                                                                @else
                                                                                                    ---
                                                                                                @endif
                                                                                            </div>

                                                                                            <div
                                                                                                class="col-lg-1 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                الجنسية
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-2 col-md-3 col-sm-12 col-xs-12 col">
                                                                                                @if ($reg->student->nationality)
                                                                                                    {{ $reg->student->nationality }}
                                                                                                @else
                                                                                                    ---
                                                                                                @endif
                                                                                            </div>

                                                                                            <div
                                                                                                class="col-lg-1 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                النوع
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-1 col-md-3 col-sm-12 col-xs-12 col">
                                                                                                @if ($reg->student->gender == 'male')
                                                                                                    ذكر
                                                                                                @else
                                                                                                    أنثي
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col-lg-3 col-md-2 col-sm-12 col-xs-12 col label font-bold">
                                                                                                ملاحظات
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-9 col-md-4 col-sm-12 col-xs-12 col">
                                                                                                @if ($reg->student->notes)
                                                                                                    {{ $reg->student->notes }}
                                                                                                @else
                                                                                                    ---
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- </div> --}}

                                                <div class="modal fade text-xs-left pay-modal" id="modal-pay-{{ $reg->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel90" aria-hidden="true">
                                                    <div class="modal-dialog" role="document" style="max-width: 800px;">
                                                        <div class="modal-content">
                                                            <div id="pay-{{ $reg->id }}-info" style="">
                                                                <div class="modal-body content-reports pay-modal" style="padding:0">
                                                                        <div class="statistic-table custom-bar">
                                                                            <div class="content-body">
                                                                                <div class="card-header">
                                                                                    <h4 class="card-title" style="float: right;">
                                                                                        الدفعات للطالب :
                                                                                        {{ $reg->student->name }}
                                                                                    </h4>

                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>

                                                                                <div class="card-block pay-template">
                                                                                    <div class="card-text col-md-12">
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <label><b>سعر الدورة :</b></label>
                                                                                                <span class="price">
                                                                                                    <span class="regprice" reg_id="{{ $reg->id }}">{{ number_format($reg->price, 2) }}</span>                                                                                                    
                                                                                                    ر.س
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label><b>المدفوع :</b></label>
                                                                                                <span style="color:green" class="paid_amount">
                                                                                                    {{ number_format($reg->coursePaidAmount, 2) }}
                                                                                                    ر.س
                                                                                                </span>
                                                                                            </div>

                                                                                            <div class="col-md-3">
                                                                                                <label><b>المتبقي :</b></label>
                                                                                                <span style="color:red" class="remaining_amount" reg_id="{{ $reg->id }}">
                                                                                                    @if(in_array($reg->status_id,[8,9]))
                                                                                                    0.00
                                                                                                
                                                                                                    @else
                                                                                                    {{ number_format($reg->price - $reg->coursePaidAmount, 2) }}
                                                                                                    
                                                                                                    @endif
                                                                                                </span>
                                                                                                <span style="color:red" >ر.س</span>
                                                                                            </div>
                                                                                            <div class="col-md-1">
                                                                                                @if($reg->is_course_paid == 1)
                                                                                                <i class="fa fa-check green list-boolean-icon"></i>                                                        
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                        
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <label><b>رسوم الاختبار :</b></label>
                                                                                                <span class="price">
                                                                                                    <span class="regexamprice" reg_id="{{ $reg->id }}">{{ number_format($reg->exam_fees, 2) }}</span>
                                                                                                    ر.س
                                                                                                </span>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <label><b>المدفوع :</b></label>
                                                                                                <span style="color:green">
                                                                                                    {{ number_format($reg->examPaidAmount, 2) }}
                                                                                                    ر.س
                                                                                                </span>
                                                                                            </div>

                                                                                            <div class="col-md-3">
                                                                                                <label><b>المتبقي :</b></label>
                                                                                                <span style="color:red" class="remaining_exam_amount" reg_id="{{ $reg->id }}">
                                                                                                    {{ number_format($reg->exam_fees - $reg->examPaidAmount, 2) }}
                                                                                                </span>
                                                                                                <span style="color:red">ر.س</span>
                                                                                            </div>
                                                                                            <div class="col-md-1">
                                                                                                @if($reg->is_exam_paid == 1)
                                                                                                <i class="fa fa-check green list-boolean-icon"></i>                                                        
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="card-block pay-template">
                                                                                    <div class="card-text col-md-12">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <h4 class="card-title" style="float: right;">الدفعات</h4>
                                                                                            </div>
                                                                                            
                                                                                            @if($reg->payments->count() > 0)
                                                                                            @foreach ($reg->payments as $payment)
                                                                                                <div class="col-md-3">
                                                                                                    <b>- ( {{$payment->pay_type}} )</b>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    {{$payment->paid_at}}
                                                                                                </div>
                                                                                                <div class="col-md-2">
                                                                                                    <b>{{$payment->amount}}  ر.س </b>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    [ {{$payment->pay_method}} ]
                                                                                                </div>
                                                                                            @endforeach
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                                <div class="card-block pay-template" style="margin-bottom: 20px">
                                                                                    <form class="card-form" id="payActionForm" method="POST" action="{{ route(Auth::getDefaultDriver().'.courses.pay_action') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" id="id" name="id" value="{{ $reg->id }}" />
                                                                                        <input type="hidden" id="price_rest" class="remaining_amount" reg_id="{{$reg->id}}" value="{{ $reg->price - $reg->coursePaidAmount}}" />
                                                                                        <input type="hidden" id="exam_fees_rest" value="{{ $reg->exam_fees - $reg->examPaidAmount }}" />
                                                                                        <div class="card-text col-md-12">
                                                                                            <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                    <h4 class="card-title" style="float: right;">تسجيل دفع جديد</h4>
                                                                                                </div>
                                                                                                
                                                                                                <div class="col-md-3">
                                                                                                    <label for="pay_type">نوع الدفع :</label>
                                                                                                    <span>
                                                                                                        <select id="pay_type" name="pay_type" class="form-control">
                                                                                                            @if(!in_array($reg->status_id,[8,9]))       
                                                                                                            <option value="دفعة للدورة">دفعة للدورة</option>
                                                                                                            @endif
                                                                                                            <option value="رسوم الاختبار">رسوم الاختبار</option>
                                                                                                            <option value="رسوم إعادة الاختبار">رسوم إعادة الاختبار</option>
                                                                                                        </select>
                                                                                                    </span>
                                                                                                </div>
                                                                                                
                                                                                                
                                                                                                <div class="col-md-2">
                                                                                                    <label for="amount">القيمة :</label>
                                                                                                    <span>
                                                                                                        <input type="text" id="pay_amount" class="form-control" name="amount" />
                                                                                                    </span>
                                                                                                </div>
                                                                                                
                                                                                                <div class="col-md-4">
                                                                                                    <label for="paid_at">تاريخ الدفع :</label>
                                                                                                    <span>
                                                                                                        <input type="text" id="paid_at" class="form-control hijri-datepicker" name="paid_at" />
                                                                                                    </span>
                                                                                                </div>
                                                                                                
                                                                                                <div class="col-md-2">
                                                                                                    <label for="pay_method">طريقة الدفع :</label>
                                                                                                    <span>
                                                                                                        <select id="pay_method" name="pay_method" class="form-control">
                                                                                                            <option value="كاش">كاش</option>
                                                                                                            <option value="تحويل">تحويل</option>
                                                                                                            <option value="شبكة">شبكة</option>
                                                                                                            <option value="تابي">تابي</option>
                                                                                                            <option value="تمارا">تمارا</option>
                                                                                                            <option value="يور واي">يور واي</option>
                                                                                                            <option value="أخرى">أخرى</option>
                                                                                                        </select>
                                                                                                    </span>
                                                                                                </div>                                                                                            
                                                                                                
                                                                                                <div class="col-md-1" style="text-align: center">
                                                                                                    <label>&nbsp;</label>
                                                                                                    <span>
                                                                                                        <br />
                                                                                                        <a class="btn btn-success pay" data-id="{{$reg->id}}" href="#">دفع</a>
                                                                                                    </span>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row">
                                                                                                <div class="alert alert-danger" style="display: none">
                                                                                                    <strong>خطأ!</strong>
                                                                                                    <ul>
                                                                                                        <li></li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="modal fade text-xs-left status-modal" id="modal-status-{{ $reg->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel90" aria-hidden="true">
                                                    <div class="modal-dialog" role="document" style="max-width: 500px;">
                                                        <div class="modal-content">
                                                            <div id="status-{{ $reg->id }}-info" style="">
                                                                <div class="modal-body content-reports status-modal" style="padding:0">
                                                                    <div class="statistic-table custom-bar">
                                                                        <form class="card-form" id="updateRegStatusForm" method="POST" action='{{ route(Auth::getDefaultDriver().'.courses.updatePaymentType') }}'>
                                                                            @csrf
                                                                            <input type="hidden" id="reg_id" name="reg_id" value="{{ $reg->id }}" />
                                                                            <div class="content-body">
                                                                                <div class="card-header">
                                                                                    <h4 class="card-title" style="float: right;">
                                                                                        تغيير حالة الدفع للطالب :
                                                                                        {{ $reg->student->name }}
                                                                                    </h4>
                                                                                </div>
                                                                                
                                                                                <div class="card-block status-template" style="margin-bottom: 20px">
                                                                                    <div class="card-text col-md-12">
                                                                                        <div class="row">                                                                                                
                                                                                            <div class="col-md-6">
                                                                                                <label for="status">الحالة :</label>
                                                                                                <span>
                                                                                                    <select id="status" name="status" class="form-control">
                                                                                                        <option value="normal" selected>افتراضي</option>
                                                                                                        <option value="free" @if(in_array($reg->status_id,[8,9])) selected @endif>الدورة مجانا</option>
                                                                                                        <option value="nopaying" @if($reg->status_id == 10) selected @endif>ممتنع عن السداد</option>
                                                                                                        <option value="leave" @if($reg->is_leave == 1) selected @endif>مغادر</option>
                                                                                                        {{-- @foreach ($statuses as $status)
                                                                                                        <option value="{{$status->id}}" @if($reg->status_id == $status->id) selected @endif>{{$status->status}}</option>
                                                                                                        @endforeach --}}
                                                                                                    </select>
                                                                                                </span>
                                                                                            </div>
                                                                                            
                                                                                            <div class="col-md-12" style="text-align: center">
                                                                                                <label>&nbsp;</label>
                                                                                                <span>
                                                                                                    <br />
                                                                                                    <a class="btn btn-success changeStatus" href="#">تغيير الحالة</a>
                                                                                                </span>
                                                                                            </div>
                                                                                        </div>
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12" style="text-align: center;padding: 50px 0">
                                        لا يوجد طلاب
                                    </div>
                                </div>

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            //$('#modal-reg-2').modal('show');

            var url = '{{ url(Auth::getDefaultDriver().'/courses/show/' . $course->id) }}';
            $(document).ready(function() {
                $('.pickadate').pickadate({
                    weekdaysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    format: 'dd-mm-yyyy',
                    formatSubmit: 'yyyy-mm-dd',
                    showMonthsShort: true,
                    today: false,
                    close: 'Close',
                    selectYears: 20,
                    selectMonths: true,
                    min: new Date(2021, 1, 1),
                    // max: new Date(),
                    // clear: '[All]',
                    // onSet: function (context) {
                    //     getInvoicesByDate();
                    // }
                });

                $(".select-dropdown__button").click(function() {
                    return false;
                });

                $('input[name=target]').change(function(event) {
                    showTargets();
                });

                function showTargets() {
                    var target = $('input[name=target]:checked').val();
                    $(".target-select").hide();
                    $("." + $('input[name=target]:checked').val() + "-select").show();

                }

                var actvRegID = 0;
                var actvPayID = 0;

                ///Reg Details
                $('.one-reg').click(function() {
                    actvRegID = this.id;
                    $('#modal-reg-info div.modal-content').html($('#reg-' + actvRegID + '-info').html());
                });

                $('.reg-modal .changeRegDate').click(function() {
                    $('#reg-' + actvRegID + '-info .regDateDV').hide();
                    $('#reg-' + actvRegID + '-info .chooseNewDateDV').show();
                    $('#reg-' + actvRegID + '-info #chngDate').val(1);
                });

                /////////////////
                //changePrice
                $('.reg-modal .changePrice').click(function() {
                    $('#reg-' + actvRegID + '-info .mainCost').hide();
                    $('#reg-' + actvRegID + '-info .newPrice').show();
                    //  $('#reg-'+actvRegID+'-info .savePrice').hide();
                    $('#reg-' + actvRegID + '-info #new_price').val();

                });

                $('.reg-modal .savePrice').on('click', function() {
                    // alert('hello');
                    var reg_id = $(this).attr('reg_id');
                    var new_price = $(`#new_price[reg_id="${reg_id}"]`).val();
                    var paid_amount = $(`#paid_amount[reg_id="${reg_id}"]`).val();
                    var main_price = $(`#main_price[reg_id="${reg_id}"]`).val();
                    var remain_amount = new_price - paid_amount;
                    var disc_perc = new_price == main_price ? 0 : Math.round((100 - (new_price / main_price * 100)) * 100) / 100;

                    $.ajax({
                        type: 'POST',
                        url: '{{ route(Auth::getDefaultDriver().'.ChangePriceForOneStudent') }}',
                        data: {
                            'new_price': new_price,
                            'reg_id': reg_id,
                            '_token': '<?php echo csrf_token(); ?>'
                        },
                        success: (result) => {
                            // console.log(result)
                            $('#reg-' + actvRegID + '-info .mainCost').show();
                            $('#reg-' + actvRegID + '-info .changePrice').show();
                            $('#reg-' + actvRegID + '-info .newPrice').hide();
                            //console.log($(`.mainCost[reg_id="${$('#reg_id').val()}"] .price`).length,$('#reg_id').val(),`.mainCost[reg_id="${$('#reg_id').val()}"] .price`);
                            $(`.mainCost[reg_id="${reg_id}"] .student_price`).text(result['new_price']);
                            $(`.finalPrice[reg_id="${reg_id}"] .price`).text(result['new_price']);
                            $(`.discountPrice[reg_id="${reg_id}"] .discount-value-text`).text(0 + '  ر.س');
                            $(`.regprice[reg_id="${reg_id}"]`).text(result['new_price']);
                            $(`.remaining_amount[reg_id="${reg_id}"]`).text(remain_amount);

                        },
                        error: function(jqXHR, exception) {
                            console.log('Uncaught Error.\n' + jqXHR.responseText);
                        }
                    });
                    return false;
                });


                $('.reg-modal .resetSave').click(function() {
                    $('#reg-' + actvRegID + '-info .newPrice').hide();
                    $('#reg-' + actvRegID + '-info .mainCost').show();
                    $('#reg-' + actvRegID + '-info .changePrice').show();
                    $('#reg-' + actvRegID + '-info #new_price').val(0);
                });

                $('input[name=new_price]').change(function(event) {
                    $('#reg-' + actvRegID + '-info .mainCost').hide();
                });
                ///////////////////////////


                //update discount
                $('.reg-modal .edit-discount-btn').click(function() {
                    $('#reg-' + actvRegID + '-info .discount-value-text').hide();
                    $('#reg-' + actvRegID + '-info .edit-discount-btn').hide();
                    $('#reg-' + actvRegID + '-info .discount-input').show();
                    $('#reg-' + actvRegID + '-info .save-discount-btn').show();
                    $('#reg-' + actvRegID + '-info .cancel-discount-btn').show();
                    $('#reg-' + actvRegID + '-info #new_discount').val();
                });
                
                $('.reg-modal .save-discount-btn').on('click', function() {
                    var reg_id = $(this).attr('reg_id');
                    var new_discount = $(`#discount-input[reg_id="${reg_id}"]`).val();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route(Auth::getDefaultDriver().'.UpdateDiscountForOneStudent') }}',
                        data: {
                            'new_discount': new_discount,
                            'reg_id': reg_id,
                            '_token': '<?php echo csrf_token(); ?>'
                        },
                        success: (result) => {
                            // console.log(result)
                            $('#reg-' + actvRegID + '-info .discount-value-text').show();
                            $('#reg-' + actvRegID + '-info .edit-discount-btn').show();
                            $('#reg-' + actvRegID + '-info .discount-input').hide();
                            $('#reg-' + actvRegID + '-info .save-discount-btn').hide();
                            $('#reg-' + actvRegID + '-info .cancel-discount-btn').hide();
                            $(`.discount-value-text[reg_id="${reg_id}"]`).text(new_discount + '  ر.س');
                            // تحديث السعر النهائي
                            var priceElem = document.querySelector('.mainCost[reg_id="' + reg_id + '"] .student_price');
                            var finalPriceElem = document.querySelector('.price[reg_id="' + reg_id + '"]');
                            var price = priceElem ? parseFloat(priceElem.textContent) : 0;
                            var finalPrice = price - new_discount;
                            if (finalPriceElem) {
                                finalPriceElem.textContent = finalPrice.toFixed(2);
                            }
                            // تحديث المتبقي
                            var paidAmountElem = document.querySelector('.paid_amount[reg_id="' + reg_id + '"]');
                            var remainingAmountElem = document.querySelector('.remaining_amount[reg_id="' + reg_id + '"]');
                            var paidAmount = paidAmountElem ? parseFloat(paidAmountElem.textContent) : 0;
                            var remainingAmount = finalPrice - paidAmount;
                            if (remainingAmountElem) {
                                remainingAmountElem.textContent = remainingAmount.toFixed(2);
                            }
                        },
                        error: function(jqXHR, exception) {
                            console.log('Uncaught Error.\n' + jqXHR.responseText);
                        }
                    });
                    return false;
                });

                
                /////////////////
                
                //changeExamPrice
                $('.reg-modal .changeExamPrice').click(function() {
                    $('#reg-' + actvRegID + '-info .mainExamFees').hide();
                    $('#reg-' + actvRegID + '-info .newExamPrice').show();
                    $('#reg-' + actvRegID + '-info #new_exam_price').val();

                });

                $('.reg-modal .saveExamPrice').on('click', function() {
                    // alert('hello');
                    var reg_id = $(this).attr('reg_id');
                    var new_exam_price = $(`#new_exam_price[reg_id="${reg_id}"]`).val();
                    var paid_exam_amount = $(`#paid_exam_amount[reg_id="${reg_id}"]`).val();
                    var main_exam_price = $(`#main_exam_price[reg_id="${reg_id}"]`).val();
                    var remain_exam_amount = new_exam_price - paid_exam_amount;

                    $.ajax({
                        type: 'POST',
                        url: '{{ route(Auth::getDefaultDriver().'.ChangeExamPriceForOneStudent') }}',
                        data: {
                            'new_exam_price': new_exam_price,
                            'reg_id': reg_id,
                            '_token': '<?php echo csrf_token(); ?>'
                        },
                        success: (result) => {
                            // console.log(result)
                            $('#reg-' + actvRegID + '-info .mainExamFees').show();
                            $('#reg-' + actvRegID + '-info .changeExamPrice').show();
                            $('#reg-' + actvRegID + '-info .newExamPrice').hide();
                            $(`.mainExamFees[reg_id="${reg_id}"] .exam_fees`).text(result['new_exam_price']);
                            $(`.regexamprice[reg_id="${reg_id}"]`).text(result['new_exam_price']);
                            $(`.remaining_exam_amount[reg_id="${reg_id}"]`).text(remain_exam_amount);
                        },
                        error: function(jqXHR, exception) {
                            console.log('Uncaught Error.\n' + jqXHR.responseText);
                        }
                    });
                    return false;
                });


                $('.reg-modal .resetExamSave').click(function() {
                    $('#reg-' + actvRegID + '-info .newExamPrice').hide();
                    $('#reg-' + actvRegID + '-info .mainExamFees').show();
                    $('#reg-' + actvRegID + '-info .changeExamPrice').show();
                    $('#reg-' + actvRegID + '-info #new_exam_price').val(0);
                });

                $('input[name=new_exam_price]').change(function(event) {
                    $('#reg-' + actvRegID + '-info .mainExamCost').hide();
                });
                ///////////////////////////
                
                //changeRegBy
                $('.reg-modal .changeRegBy').click(function() {
                    $('#reg-' + actvRegID + '-info .regByText').hide();
                    $('#reg-' + actvRegID + '-info .newRegBy').show();
                    $('#reg-' + actvRegID + '-info .newRegBy #reg_by').val('');

                });

                $('.reg-modal .saveRegBy').on('click', function() {
                    var reg_id = $(this).attr('reg_id');
                    var reg_by = $(`.newRegBy #reg_by[reg_id="${reg_id}"]`).val();
                    console.log(reg_id, reg_by);
                    $.ajax({
                        type: 'POST',
                        url: '{{ route(Auth::getDefaultDriver().'.updateRegBy') }}',
                        data: {
                            'reg_by': reg_by,
                            'reg_id': reg_id,
                            '_token': '<?php echo csrf_token(); ?>'
                        },
                        success: (result) => {
                            console.log(result)
                            $('#reg-' + actvRegID + '-info .regByText').show();
                            $('#reg-' + actvRegID + '-info .newRegBy').hide();
                            $(`.regByText span`).text(reg_by);
                        },
                        error: function(jqXHR, exception) {
                            console.log('Uncaught Error.\n' + jqXHR.responseText);
                        }
                    });
                    return false;
                });


                $('.reg-modal .resetRegBy').click(function() {
                    $('#reg-' + actvRegID + '-info .regByText').show();
                    $('#reg-' + actvRegID + '-info .newRegBy').hide();
                    $('#reg-' + actvRegID + '-info .newRegBy #reg_by').val('');
                });
                ///////////////////////////
                

                //Pay
                $('.one-pay').click(function() {
                    actvRegID = this.id;
                    $('#modal-pay-info div.modal-content').html($('#pay-' + actvRegID + '-info').html());
                });
                $('.pay-modal .pay').click(function() {;
                    var regPriceRest = parseFloat($('#pay-' + actvRegID + '-info #price_rest').val()) ;
                    var regExamFeesRest = parseFloat($('#pay-' + actvRegID + '-info #exam_fees_rest').val()) ;
                    var payType = $('#pay-' + actvRegID + '-info  #pay_type').find(":selected").val();
                    var payAmount = parseFloat($('#pay-' + actvRegID + '-info #pay_amount').val());
                    var payDate = $('#pay-' + actvRegID + '-info #paid_at').val();
                    //  console.log();
                    //  return false;
                    
                    if($('#pay-' + actvRegID + '-info #pay_amount').val() == ''){
                        showPayAlert('يجب ادخال القيمة المدفوعة');
                        return false;
                    }else if(payDate == ''){
                        showPayAlert('يجب ادخال تاريخ الدفع');
                        return false;
                    }else if(payType == 'دفعة للدورة'){
                        if(payAmount > regPriceRest){       //if paid more than the rest price
                            showPayAlert('القيمة المدفوعة اكبر من المبلغ المتبقي لقيمة الدورة');
                            return false;
                        }
                    }else if(payType == 'رسوم الاختبار'){
                        if(payAmount > regExamFeesRest){       //if paid more than the rest price
                            showPayAlert('القيمة المدفوعة اكبر من المبلغ المتبقي لرسوم الاختبار');
                            return false;
                        }
                    }
                    $('#pay-' + actvRegID + '-info #payActionForm').submit();
                    return false;
                });

                function showPayAlert(msg){
                    $('#pay-' + actvRegID + '-info .alert li').html(msg); 
                    $('#pay-' + actvRegID + '-info .alert').show().delay(4000).fadeOut('slow');                    
                }

                //Status
                $('.one-status').click(function() {
                    actvRegID = this.id;
                   // $('#modal-pay-info div.modal-content').html($('#pay-' + actvRegID + '-info').html());
                });
                $('.status-modal .changeStatus').click(function() {
                    console.log(actvRegID);
                    $('#status-' + actvRegID + '-info #updateRegStatusForm').submit();
                    return;
                });


                ////////Search &  Filter & Sort//////
                var searchSortFilterParams = '';
                //Search//
                $('#srchInput-reg').keypress(function(e) {
                    var key = e.which;
                    if (key == 13){         // the enter key code                    
                        $('button[class="srch-icon-reg"]').click();
                        return false;
                    }
                });
                $(".srch-icon-reg").click(function(event) {
                    searchSortFilterParams = collectSearchSortFilterParamsRegs();
                    document.location.href = url + searchSortFilterParams;
                });
                $(".header-search .clear-reg").on('click', function() {
                    $('#srchInput-reg').val('');
                    searchSortFilterParams = collectSearchSortFilterParamsRegs();
                    document.location.href = url + searchSortFilterParams;
                });
                /////
                ///Sort///
                $('.sort-item-reg').click(function(event) {
                    event.preventDefault();
                    var e = $(this);
                    $('#sort_val').val(e.data('val'));
                    searchSortFilterParams = collectSearchSortFilterParamsRegs();
                    document.location.href = url + searchSortFilterParams;
                });
                /////

                //Filter Satus////
                $('.filter-item-reg').click(function(event) {
                    event.preventDefault();
                    var e = $(this);
                    $('#fltr_val').val(e.data('val'));
                    searchSortFilterParams = collectSearchSortFilterParamsRegs();
                    document.location.href = url + searchSortFilterParams;
                });
                /////
                /////
                //Filter Date Range//
                $(".fltr-date-range-reg .drnge-icon-reg").click(function(event) {
                    searchSortFilterParams = collectSearchSortFilterParamsRegs();
                    document.location.href = url + searchSortFilterParams;
                });
                $(".fltr-date-range-reg .clear-dateRang").on('click', function() {
                    $('.fltr-date-range-reg #dateRngFrm').val('');
                    $('.fltr-date-range-reg #dateRangTo').val('');
                    searchSortFilterParams = collectSearchSortFilterParamsRegs();
                    document.location.href = url + searchSortFilterParams;
                });
                /////

                //Export///
                $("#export").click(function(event) {
                    // alert(searchSortFilterParams);
                    // return false;
                    searchSortFilterParams = collectSearchSortFilterParamsRegs();
                    if (searchSortFilterParams != '') {
                        document.location.href = url + searchSortFilterParams + '&export=yes';
                    } else {
                        document.location.href = url + '?export=yes';
                    }
                });
                ////////



                /*$('#export-excel').click(function(){
                    searchSortFilterParams = collectSearchSortFilterParamsRegs();
                    // alert(url + searchSortFilterParams);
                    if(searchSortFilterParams != ''){
                        document.location.href = url + searchSortFilterParams + '&export=yes';
                    }else{
                        document.location.href = url + '?export=yes';
                    }

                });*/

                function collectSearchSortFilterParamsRegs() {
                    /////Search////
                    var srchVal = $('#srchInput-reg').val();
                    var srchParam = srchVal != '' ? "srch=" + srchVal : "";
                    /////////////

                    ////Sort////
                    var sortVal = $('#sort_val').val();
                    var sortParam = (sortVal != '' && sortVal != 'no') ? "srt=" + sortVal : "";
                    //////////

                    ////Filter////
                    var fltrStsVal = $('#fltr_val').val();
                    if(fltrStsVal < 100){
                        var filterParam = (fltrStsVal != '' && fltrStsVal != 0) ? "fltr_sts=" + fltrStsVal : "";
                    }else{
                        var filterParam = (fltrStsVal != '' && fltrStsVal != 0) ? "fltr_crt=" + (fltrStsVal - 100) : "";
                    }
                    ////////// 

                    /////Filter Date Range////
                    var dateRngFromVal = $('.fltr-date-range-reg #dateRngFrm').val();
                    var dateRngToVal = $('.fltr-date-range-reg #dateRangTo').val();
                    var dateRngParam = (dateRngFromVal != '' || dateRngToVal != '') ? "dateRngFrm=" + dateRngFromVal + "&dateRngTo=" + dateRngToVal : "";
                    /////////////


                    var finalParams = "";
                    if (srchParam != "") {
                        finalParams += (srchParam + "&");
                    }
                    if (sortParam != "") {
                        finalParams += (sortParam + "&");
                    }
                    if (filterParam != "") {
                        finalParams += (filterParam + "&");
                    }
                    if (dateRngParam != "") {
                        finalParams += (dateRngParam + "&");
                    }

                    finalParams = finalParams.replace(/&\s*$/, ""); //remove the last (&)
                    return finalParams != "" ? "?" + finalParams : "";
                }

                ////////////////////


            });
        </script>


        <script src="{{url('/admin-assets/vendors/js/pickers/hijri-date-picker/dist/js/bootstrap-hijri-datetimepicker.min.js?v2')}}"> </script>

        <script type="text/javascript">
            $(document).ready(function () {
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[id^="copy-button-"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const regId = this.id.split('-')[2];


                        // const courseName = $('#modal-reg-'+regId+' .course-name').html();
                        // const studentName = studentNameElement ? studentNameElement.innerText : 'N/A';
                        // const idNumber = idNumberElement ? idNumberElement.innerText : 'N/A';
                        // const phone = phoneElement ? phoneElement.innerText : 'N/A';
                        // const totalAmount = totalAmountElement ? totalAmountElement.innerText : 'N/A';
                        // const paidAmount = paidAmountElement ? paidAmountElement.innerText : 'N/A';
                        // const remainingAmount = remainingAmountElement ? remainingAmountElement
                        //     .innerText : 'N/A';
                        // const status = statusElement ? statusElement.innerText : 'N/A';


                        const courseNameElement = document.querySelector('#modal-reg-'+regId+' .course-name');
                        const studentNameElement = document.querySelector('#modal-reg-'+regId+' .student-name');
                        const idNumberElement = document.querySelector('#modal-reg-'+regId+' .id-number');
                        const phoneElement = document.querySelector('#modal-reg-'+regId+' .phone');
                        const totalAmountElement = document.querySelector('#modal-pay-'+regId+' .price');
                        const paidAmountElement = document.querySelector('#modal-pay-'+regId+' .paid_amount');
                        const remainingAmountElement = document.querySelector('#modal-pay-'+regId+' .remaining_amount');
                        const statusElement = document.querySelector('#reg'+regId+' .paid_status_id');
                        const validStatuses = [3, 4, 5, 6, 7, 9];
                        const examPaidStatusElement = document.querySelector('#reg'+regId+' .paid_exam_id');
                        
                        const courseName = courseNameElement ? courseNameElement.innerText : 'N/A';
                        const studentName = studentNameElement ? studentNameElement.innerText : 'N/A';
                        const idNumber = idNumberElement ? idNumberElement.innerText : 'N/A';
                        const phone = phoneElement ? phoneElement.innerText : 'N/A';
                        const totalAmount = totalAmountElement ? totalAmountElement.innerText.replace(/\s+/g, ' ').trim() : 'N/A';
                        const paidAmount = paidAmountElement ? paidAmountElement.innerText.replace(/\s+/g, ' ').trim() : 'N/A';
                        const remainingAmount = remainingAmountElement ? remainingAmountElement.innerText.replace(/\s+/g, ' ').trim() : 'N/A';
                        //const status = validStatuses.includes(Number(statusElement.innerText)) ? 'نعم' : 'لا';
                        const examPaidStatus = examPaidStatusElement.innerText == 1 ? 'نعم' : 'لا';

                        const copyText =
                            'اسم الدورة: '+courseName+'\nاسم الطالب: '+studentName+'\nرقم الهوية: '+idNumber+'\nرقم الجوال: '+phone+'\nالمبلغ كامل : '+totalAmount+'\nمدفوع  : '+paidAmount+'\nمتبقي :'+remainingAmount+'\nسدد رسوم الاختبار ؟: '+examPaidStatus;
// console.log(copyText);
                          
                        navigator.clipboard.writeText(copyText).then(() => {
                            alert('تم نسخ المعلومات بنجاح');
                        }).catch(err => {
                            alert(
                                'فشل في نسخ المعلومات! تأكد من دعم المتصفح لاستخدام Clipboard API.');
                        });
                    });
                });
            });
        </script>
    @endpush

@endsection
