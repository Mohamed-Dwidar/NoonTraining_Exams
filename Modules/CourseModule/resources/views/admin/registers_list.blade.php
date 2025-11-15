@extends('layoutmodule::admin.main')

@section('title')
طلبات التسجيل في الدورات
@endsection


@section('content')

<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3>
                <i class="fa fa-list"></i>
                &nbsp;
                طلبات التسجيل في الدورات
            </h3>
            {{-- <a href="course.html">الدورات /</a> --}}
        </div>
    </div>

    @include('layoutmodule::admin.flash')

    <div class="content-body">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <i class="fa fa-list teal font-large-2 float-xs-right"></i>
                                </div>
                                <div class="media-body text-xs-right">
                                    <h3 class="teal">{{$courses_regs->count()}}</h3>
                                    <h5>إجمالي الطلبات</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- @if(in_array('can_manage_money',auth()->user()->privileges_keys())) --}}
            <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <i class="icon icon-moneybag teal font-large-2 float-xs-right"></i>
                                </div>
                                <div class="media-body text-xs-right">
                                    <h3 class="teal">{{$courses_regs->sum('price')}}</h3>
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
                                    $paid = 0 ;
                                    if(!empty($courses_regs)){
                                        foreach ($courses_regs as $one_reg) {
                                            $paid = $paid + $one_reg->payments->sum('amount');
                                        }
                                    }
                                ?>
                                <div class="media-body text-xs-right">
                                    <h3 class="teal">{{$paid}}</h3>
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
                                    <h3 class="teal">{{$courses_regs->sum('price') - $paid}}</h3>
                                    <h5>إجمالي المتبقي</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- @endif --}}




        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">

                            <div class="col-lg-4 col-md-4">
                                <div class="filters">
                                    <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="icon-filter"></i>
                                        الدورة
                                    </a>
                                    <div class="dropdown-menu arrow dropdown-filter">
                                        <input type="hidden" id="fltr_crs_val-reg"
                                            value="@if(app('request')->fltr_crs != null){{app('request')->fltr_crs}}@endif" />

                                        <button class="dropdown-item filter-crs-item-reg" type="button" data-val="0">
                                            الكل
                                        </button>
                                        @if(!empty($courses_dates))
                                        @foreach ($courses_dates as $course_date)
                                        <button class="dropdown-item filter-crs-item-reg" type="button"
                                            data-val="{{$course_date->id}}">
                                            {{$course_date->course->name}}
                                            (
                                            من
                                            {{ \Carbon\Carbon::parse($course_date->start_at)->format('d-m-Y') }}
                                            الي
                                            {{ \Carbon\Carbon::parse($course_date->end_at)->format('d-m-Y') }}
                                            )
                                        </button>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>


                                <div class="filters">
                                    <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="icon-filter"></i>
                                        المكان
                                    </a>
                                    <div class="dropdown-menu arrow dropdown-filter">
                                        <input type="hidden" id="fltr_plc_val"
                                            value="@if(app('request')->fltr_plc != null){{app('request')->fltr_plc}}@endif" />

                                        <button class="dropdown-item filter-plc-item-reg" type="button" data-val="0">
                                            الكل
                                        </button>
                                        <button class="dropdown-item filter-plc-item-reg" type="button" data-val="1">
                                            اون لاين
                                        </button>
                                        <button class="dropdown-item filter-plc-item-reg" type="button" data-val="2">
                                            في المعهد
                                        </button>
                                    </div>
                                </div>


                                <div class="filters">
                                    <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="icon-filter"></i>
                                        الحالة
                                    </a>
                                    <div class="dropdown-menu arrow dropdown-filter">
                                        <input type="hidden" id="fltr_sts_val"
                                            value="@if(app('request')->fltr_sts != null){{app('request')->fltr_sts}}@endif" />

                                        <button class="dropdown-item filter-sts-item-reg" type="button" data-val="0">
                                            الكل
                                        </button>
                                        <button class="dropdown-item filter-sts-item-reg" type="button" data-val="1">
                                            جديد
                                        </button>
                                        {{-- <button class="dropdown-item filter-sts-item-reg" type="button" data-val="2">
                                            مقبول
                                        </button> --}}
                                        <button class="dropdown-item filter-sts-item-reg" type="button" data-val="3">
                                            قيد الانتظار
                                        </button>
                                        {{-- <button class="dropdown-item filter-sts-item-reg" type="button" data-val="4">
                                            مرفوض
                                        </button> --}}
                                        {{-- <button class="dropdown-item filter-sts-item-reg" type="button" data-val="11">
                                            طلاب قيد التحصيل
                                        </button>
                                        <button class="dropdown-item filter-sts-item-reg" type="button" data-val="12">
                                            طلاب تم تحصيل كامل المبلغ
                                        </button>
                                        <button class="dropdown-item filter-sts-item-reg" type="button" data-val="13">
                                            استلم الشهادة
                                        </button>
                                        <button class="dropdown-item filter-sts-item-reg" type="button" data-val="14">
                                            لم يستلم الشهادة
                                        </button> --}}
                                    </div>
                                </div>


                                <div class="filters">
                                    <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="icon-sort"></i>
                                        ترتيب
                                    </a>
                                    <div class="dropdown-menu arrow dropdown-sort">
                                        <input type="hidden" id="sort_val"
                                            value="@if(app('request')->srt != null){{app('request')->srt}}@endif" />

                                        <button class="dropdown-item sort-item-reg" type="button" data-val="no">
                                            افتراضي
                                        </button>
                                        <button class="dropdown-item sort-item-reg" type="button" data-val="date_az">
                                            تاريخ التقديم الاقدم
                                        </button>
                                        <button class="dropdown-item sort-item-reg" type="button" data-val="date_za">
                                            تاريخ التقديم الاحدث
                                        </button>
                                        <button class="dropdown-item sort-item-reg" type="button" data-val="cdate_az">
                                            تاريخ بدء الدورة الاقدم
                                        </button>
                                        <button class="dropdown-item sort-item-reg" type="button" data-val="cdate_za">
                                            تاريخ بدء الدورة الاحدث
                                        </button>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-5 col-md-4 col-sm-3 col-xs-12">
                                <div class="header-search">
                                    <label>بحث</label>
                                    <input value='@if(app("request")->srch != null){{app('request')->srch}}@endif'
                                        id="srchInput-reg" />
                                    <button class="srch-icon-reg">
                                        <i class="icon-search7"></i>
                                    </button>
                                    <a href="#" class="clear-reg">إلغاء</a>
                                </div>
                            </div>

                            <div class="col-lg-5 col-md-6 col-sm-9 col-xs-12">
                                <div class="fltr-date-range-reg">
                                    <label>الفترة:</label>
                                    من
                                    <input
                                        value='@if(app("request")->dateRngFrm != null){{app('request')->dateRngFrm}}@endif'
                                        id="dateRngFrm" class="pickadate" />
                                    &nbsp;
                                    الي
                                    <input
                                        value='@if(app("request")->dateRngTo != null){{app('request')->dateRngTo}}@endif'
                                        id="dateRangTo" class="pickadate" />

                                    <button class="drnge-icon-reg">
                                        <i class="icon-search7"></i>
                                    </button>
                                    <a href="#" class="clear-dateRang">إلغاء</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr class="head">
                                        <th>الطالب</th>
                                        <th>الدورة</th>
                                        <th>تاريخ التقديم</th>
                                        {{-- @if(in_array('can_manage_money',auth()->user()->privileges_keys())) --}}
                                        <th>التكلفة</th>
                                        <th>الباقي</th>
                                        {{-- @endif --}}

                                        <th>الحالة</th>
                                        <th>استلم الشهادة</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(!empty($courses_regs))
                                    @foreach ($courses_regs as $reg)
                                    <tr>
                                    
                                        <td class="strong">{{$reg->student->name}}</td>
                                        <td style="direction: rtl">
                                            {{$reg->coursesDate->course->name}}
                                            <br>
                                            من :
                                            {{ \Carbon\Carbon::parse($reg->coursesDate->start_at)->format('d-m-Y') }}
                                            <br>
                                            الي :
                                            {{ \Carbon\Carbon::parse($reg->coursesDate->end_at)->format('d-m-Y') }}
                                        </td>
                                        <td style="direction: rtl">
                                            {{ \Carbon\Carbon::parse($reg->created_at)->format('d-m-Y') }}</td>

                                        {{-- @if(in_array('can_manage_money',auth()->user()->privileges_keys())) --}}
                                        <td>
                                            @if($reg->main_price == $reg->price)
                                            {{$reg->price}}
                                            @else
                                            {{$reg->price}}
                                            <br>
                                            <span class="small"
                                                style="text-decoration: line-through">{{$reg->main_price}}</span>
                                            @endif
                                        </td>

                                        <td>{{number_format(($reg->price - $reg->payments->sum('amount')),2)}}</td>
                                        {{-- @endif --}}

                                        <td>
                                            @if($reg->status_id == 1)
                                            <label class="btn btn-blue"
                                                style="margin-right:20px;padding:5px;text-align:center;font-size: 13px">
                                                {{$reg->status->status}}
                                            </label>
                                            @elseif($reg->status_id == 2)
                                            <label class="btn btn-success" style="margin-right:20px;padding:5px">
                                                {{$reg->status->status}}
                                            </label>
                                            @elseif($reg->status_id == 3)
                                            <label class="btn btn-warning" style="margin-right:20px;padding:5px">
                                                {{$reg->status->status}}
                                            </label>
                                            @elseif($reg->status_id == 4)
                                            <label class="btn btn-danger" style="margin-right:20px;padding:5px;">
                                                {{$reg->status->status}}
                                            </label>
                                            @endif
                                        </td>
                                        <td>
                                            @if($reg->is_recive_cert == 1)
                                            <label class="btn btn-green"
                                                style="margin-right:20px;padding:5px 20px;text-align:center;font-size: 13px">
                                                نعم
                                            </label>
                                            @else
                                            <label class="btn btn-red"
                                                style="margin-right:20px;padding:5px 20px;text-align:center;font-size: 13px">
                                                لا
                                            </label>
                                            @endif
                                        </td>

                                        <td>
                                            {{-- @if(in_array('can_manage_money',auth()->user()->privileges_keys())) --}}
                                            <a class="btn-sm btn-warning one-pay" href="#" id="{{$reg->id}}"
                                                role="button" data-toggle="modal"
                                                data-target="#modal-pay-{{$reg->id}}">دفع</a>
                                            {{-- @endif --}}


                                            <a class="btn-sm btn-warning one-reg" href="#" id="{{$reg->id}}"
                                                role="button" data-toggle="modal"
                                                data-target="#modal-reg-{{$reg->id}}">التفاصيل</a>

                                            {{-- @if(in_array('can_del_students',auth()->user()->privileges_keys())) --}}
                                            <a class="btn-sm btn-danger"
                                                href="{{route(Auth::getDefaultDriver().'.courses.delete_reg',$reg->id)}}"
                                                onclick="return confirm('هل انت متأكد انك تريد حذف هذا الطلب ؟')"
                                                role="button">حذف</a>
                                            {{-- @endif --}}

                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td colspan="7"> --}}
                                    <div class="modal fade text-xs-left reg-modal" id="modal-reg-{{$reg->id}}"
                                        tabindex="-1" role="dialog" aria-labelledby="myModalLabel90" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="max-width: 800px;">
                                            <div class="modal-content">
                                                <div id="reg-{{$reg->id}}-info" style="">
                                                    <div class="modal-body content-reports reg-modal" style="padding:0">
                                                        <div class="statistic-table custom-bar">

                                                            <form class="card-form" id="regActionForm" method="POST"
                                                                action='{{ route(Auth::getDefaultDriver().".courses.reg_action") }}'>
                                                                @csrf
                                                                <input type="hidden" id="id" name="id"
                                                                    value="{{$reg->id}}" />
                                                                <input type="hidden" id="chngDate" name="chngDate"
                                                                    value="0" />
                                                                <input type="hidden" id="reg-action" name="action" />
                                                                <div class="content-body">
                                                                    <section class="card">
                                                                        <div class="card-header">
                                                                            <h4 class="card-title"
                                                                                style="float: right;">
                                                                                الطالب :
                                                                                {{$reg->student->name}}
                                                                            </h4>
                                                                            @if($reg->status_id == 1)
                                                                            <label class="btn btn-warning"
                                                                                style="margin-right:20px;padding:5px;text-align:center;font-size: 13px">
                                                                                {{$reg->status->status}}
                                                                            </label>
                                                                            @elseif($reg->status_id == 2)
                                                                            <label class="btn btn-success"
                                                                                style="margin-right:20px;padding:5px">
                                                                                {{$reg->status->status}}
                                                                            </label>
                                                                            @elseif($reg->status_id == 3)
                                                                            <label class="btn btn-warning"
                                                                                style="margin-right:20px;padding:5px">
                                                                                {{$reg->status->status}}
                                                                            </label>
                                                                            @elseif($reg->status_id == 4)
                                                                            <label class="btn btn-danger"
                                                                                style="margin-right:20px;padding:5px;">
                                                                                {{$reg->status->status}}
                                                                            </label>
                                                                            @endif

                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>

                                                                        </div>

                                                                        <div class="card-block reg-template">
                                                                            <div class="card-text col-md-12">
                                                                                <div class="row content-header">
                                                                                    <div
                                                                                        class="content-header-left col-md-12 col-xs-12 mb-1">
                                                                                        <h4 class="form-section"><i
                                                                                                class="icon-desktop"></i>
                                                                                            بيانات الدورة</h4>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">

                                                                                    <div class="col-md-12">
                                                                                        <label>الدورة :</label>
                                                                                        <span>
                                                                                            {{$reg->coursesDate->course->name}}
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-12 regDateDV">
                                                                                        <label>تبدأ في :</label>
                                                                                        <span>
                                                                                            {{$reg->coursesDate->start_at}}
                                                                                        </span>
                                                                                        &nbsp;&nbsp; &nbsp;&nbsp;
                                                                                        <label>تنتهي في :</label>
                                                                                        <span>
                                                                                            {{$reg->coursesDate->end_at}}
                                                                                        </span>

                                                                                        <a
                                                                                            class="btn btn-blue changeRegDate">تغير
                                                                                            الموعد</a>
                                                                                    </div>
                                                                                    <div class="col-md-12 chooseNewDateDV"
                                                                                        style="display: none">
                                                                                        <label>
                                                                                            الموعد الجديد :
                                                                                        </label>
                                                                                        <span>
                                                                                            <select class="form-control"
                                                                                                id="course_date_id"
                                                                                                name="course_date_id">

                                                                                                @foreach($reg->coursesDate->course->dates
                                                                                                as $date)
                                                                                                <?php
                                                                                        if($date->id == $reg->course_date_id) continue;
                                                                                        ?>
                                                                                                <option
                                                                                                    value="{{$date->id}}">
                                                                                                     من
                                                                                                    {{$date->start_at}}
                                                                                                    &nbsp;&nbsp;
                                                                                                    الي
                                                                                                    {{$date->end_at}}
                                                                                                </option>
                                                                                                @endforeach

                                                                                            </select>
                                                                                        </span>

                                                                                        <a
                                                                                            class="btn btn-blue resetRegDate">إلغاء
                                                                                            التغير </a>
                                                                                    </div>



                                                                                    <div class="col-md-12">
                                                                                        <label>المدة :</label>
                                                                                        <span>
                                                                                            {{$reg->coursesDate->period}}
                                                                                        </span>
                                                                                    </div>


                                                                                    <div class="col-md-4">
                                                                                        <label>المحاضر :</label>
                                                                                        <span>
                                                                                            {{$reg->coursesDate->instructor->name}}
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>مكان الانعقاد
                                                                                            :</label>
                                                                                        <span>
                                                                                            @if($reg->coursesDate->is_online
                                                                                            ==
                                                                                            1)
                                                                                            <span
                                                                                                style="color: blue">اون
                                                                                                لاين</span>
                                                                                            @else
                                                                                            <span
                                                                                                style="color: #1A4A22">في
                                                                                                المعهد</span>
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        {{-- @if(in_array('can_manage_money',auth()->user()->privileges_keys())) --}}
                                                                                        <label>السعر المتفق عليه :</label>
                                                                                        <span>

                                                                                            @if($reg->main_price ==
                                                                                            $reg->price)
                                                                                            {{$reg->price}}
                                                                                            @else
                                                                                            &nbsp;
                                                                                            <span class="small"
                                                                                                style="text-decoration: line-through">{{$reg->main_price}}</span>

                                                                                            {{$reg->price}}
                                                                                            @endif
                                                                                            ر.س

                                                                                        </span>
                                                                                        {{-- @endif --}}

                                                                                    </div>

                                                                                    <div class="col-md-4">
                                                                                        {{-- @if(in_array('can_manage_money',auth()->user()->privileges_keys())) --}}
                                                                                        <label>كوبون الخصم :</label>
                                                                                        @if($reg->coupon_id == 0)
                                                                                        لا يوجد
                                                                                        @else
                                                                                        {{$reg->coupon->title}}
                                                                                        @endif
                                                                                        {{-- @endif --}}
                                                                                    </div>

                                                                                    <div class="col-md-4">
                                                                                        {{-- @if(in_array('can_manage_money',auth()->user()->privileges_keys())) --}}
                                                                                        <label>نسبة الخصم :</label>
                                                                                        @if($reg->coupon_id == 0)
                                                                                        لا يوجد
                                                                                        @else
                                                                                        {{$reg->coupon->discount_value}}
                                                                                        %
                                                                                        @endif
                                                                                        {{-- @endif --}}

                                                                                    </div>


                                                                                    <div class="col-md-12">
                                                                                        <label>استلم الشهادة :</label>
                                                                                        @if($reg->is_recive_cert == 1)
                                                                                        نعم
                                                                                        @else
                                                                                        لا
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="card-block reg-template">
                                                                            <div class="card-text col-md-12">
                                                                                <div class="row content-header">
                                                                                    <div
                                                                                        class="content-header-left col-md-12 col-xs-12 mb-1">
                                                                                        <h4 class="form-section"><i
                                                                                                class="icon-head"></i>
                                                                                            بيانات الطالب</h4>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        @if($reg->student->image !=
                                                                                        null)
                                                                                        <img
                                                                                            src="{{url('uploads/students/' . $reg->student->image)}}" />
                                                                                        @else

                                                                                        @if($reg->student->gender ==
                                                                                        'male')
                                                                                        <img
                                                                                            src="{{url('uploads/students/default.jpg')}}" />
                                                                                        @else
                                                                                        <img
                                                                                            src="{{url('uploads/students/default_f.jpg')}}" />
                                                                                        @endif

                                                                                        @endif
                                                                                    </div>

                                                                                    <div class="col-md-12">
                                                                                        <label>الاسم :</label>
                                                                                        <span>
                                                                                            {{$reg->student->name}}
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-12">
                                                                                        <label> البريد الألكتروني
                                                                                            :</label>
                                                                                        <span>
                                                                                            {{$reg->student->email}}
                                                                                        </span>
                                                                                    </div>


                                                                                    <div class="col-md-4">
                                                                                        <label>رقم التليفون ثابت
                                                                                            :</label>
                                                                                        <span>
                                                                                            @if($reg->student->phone)
                                                                                            {{$reg->student->phone}}
                                                                                            @else ---
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>الجوال :</label>
                                                                                        <span>
                                                                                            @if($reg->student->mobile)
                                                                                            {{$reg->student->mobile}}
                                                                                            @else ---
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label>جوال آخر للتواصل
                                                                                            :</label>
                                                                                        <span>
                                                                                            @if($reg->student->another_mobile)
                                                                                            {{$reg->student->another_mobile}}
                                                                                            @else --- @endif
                                                                                        </span>
                                                                                    </div>


                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            تاريخ الميلاد
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->birthdate)
                                                                                            {{$reg->student->birthdate}}
                                                                                            @else
                                                                                            ---
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            محل الميلاد
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->birthblace)
                                                                                            {{$reg->student->birthblace}}
                                                                                            @else
                                                                                            ---
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-4">
                                                                                        <label>
                                                                                            رقم الحفيظة
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->hafiza_nu)
                                                                                            {{$reg->student->hafiza_nu}}
                                                                                            @else
                                                                                            ---
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>


                                                                                    <div class="col-md-12">
                                                                                        <label>
                                                                                            الجنسية
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->nationality)
                                                                                            {{$reg->student->nationality}}
                                                                                            @else
                                                                                            ---
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>


                                                                                    <div class="col-md-12">
                                                                                        <label>
                                                                                            العنوان
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->address)
                                                                                            {{$reg->student->address}}
                                                                                            @else ---
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>


                                                                                    <div class="col-md-6">
                                                                                        <label>
                                                                                            رقم البطاقة / الإقامة
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->id_nu)
                                                                                            {{$reg->student->id_nu}}
                                                                                            @else ---
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-3">
                                                                                        <label>
                                                                                            مصدرها
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->id_issue)
                                                                                            {{$reg->student->id_issue}}
                                                                                            @else
                                                                                            --- @endif
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-3">
                                                                                        <label>
                                                                                            تاريخها
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->id_issue_date)
                                                                                            {{$reg->student->id_issue_date}}
                                                                                            @else --- @endif
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-6">
                                                                                        <label>
                                                                                            المؤهل الدراسي
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->qualification)
                                                                                            {{$reg->student->qualification}}
                                                                                            @else
                                                                                            --- @endif
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-6">
                                                                                        <label>
                                                                                            الوظيفة
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->student->occupation)
                                                                                            {{$reg->student->occupation}}
                                                                                            @else
                                                                                            ---
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-12">
                                                                                        <label>
                                                                                            هل يحتاج موافقه من صاحب
                                                                                            العمل ؟
                                                                                        </label>
                                                                                        <span>
                                                                                            @if($reg->need_work_agree
                                                                                            == 1)
                                                                                            نعم @else لا
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-12">
                                                                                        <label>
                                                                                            ملاحظات
                                                                                        </label>
                                                                                        <span>
                                                                                            @if ($reg->student->notes)
                                                                                                    {{ $reg->student->notes }}
                                                                                                @else
                                                                                                    ---
                                                                                                @endif
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="card-block reg-template">
                                                                            <div class="card-text col-md-12">
                                                                                <div class="row">
                                                                                    <div class="col-md-12"
                                                                                        style="text-align: center">
                                                                                        @if($reg->status_id != 2)
                                                                                        <a class="btn btn-success accept"
                                                                                            href="#"> موافقة</a>
                                                                                        &nbsp; &nbsp;&nbsp; &nbsp;
                                                                                        @endif


                                                                                        @if($reg->status_id != 4)
                                                                                        <a class="btn btn-danger reject"
                                                                                            href="#">رفض</a>
                                                                                        &nbsp; &nbsp;&nbsp; &nbsp;
                                                                                        @endif

                                                                                        @if($reg->status_id != 3)
                                                                                        <a class="btn btn-warning waiting"
                                                                                            href="#">قائمه
                                                                                            انتظار</a>
                                                                                        @endif

                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>



                                                                    </section>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="modal fade text-xs-left pay-modal" id="modal-pay-{{$reg->id}}"
                                        tabindex="-1" role="dialog" aria-labelledby="myModalLabel90" aria-hidden="true">
                                        <div class="modal-dialog" role="document" style="max-width: 800px;">
                                            <div class="modal-content">
                                                <div id="pay-{{$reg->id}}-info" style="">
                                                    <div class="modal-body content-reports pay-modal" style="padding:0">
                                                        <div class="statistic-table custom-bar">

                                                            <form class="card-form" id="payActionForm" method="POST"
                                                                action='{{ route("admin.courses.pay_action") }}'>
                                                                @csrf
                                                                <input type="hidden" id="id" name="id"
                                                                    value="{{$reg->id}}" />
                                                                <div class="content-body">
                                                                    <section class="card">
                                                                        <div class="card-header">
                                                                            <h4 class="card-title"
                                                                                style="float: right;">
                                                                                التكلفة :
                                                                                {{$reg->price}}
                                                                                ر.س
                                                                            </h4>

                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>

                                                                        </div>

                                                                        <div class="card-block pay-template">
                                                                            <div class="card-text col-md-12">
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <label>المدفوع :</label>
                                                                                        <span>
                                                                                            {{number_format($reg->payments->sum('amount'),2)}}
                                                                                            ر.س
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-9">
                                                                                        <label>المتبقي :</label>
                                                                                        <span>
                                                                                            {{number_format(($reg->price - $reg->payments->sum('amount')),2)}}
                                                                                            ر.س
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="col-md-4">
                                                                                        <label>القيمة :</label>
                                                                                        <span>
                                                                                            <input type="text"
                                                                                                id="amount"
                                                                                                name="amount" />
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <div class="card-block pay-template">
                                                                            <div class="card-text col-md-12">
                                                                                <div class="row">
                                                                                    <div class="col-md-12"
                                                                                        style="text-align: center">

                                                                                        <a class="btn btn-success pay"
                                                                                            href="#">دفع</a>

                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>



                                                                    </section>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- </td>
                                    </tr> --}}
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')

<script>
    var url = '{{url("admin/courses/registers")}}';

    $(document).ready(function () {
        var actvRegID = 0;
        $('.one-reg').click(function(){
            actvRegID = this.id ;
            $('#modal-reg-info div.modal-content').html($('#reg-'+actvRegID+'-info').html());
        });

        $('.reg-modal .changeRegDate').click(function(){
            $('#reg-'+actvRegID+'-info .regDateDV').hide();
            $('#reg-'+actvRegID+'-info .chooseNewDateDV').show();
            $('#reg-'+actvRegID+'-info #chngDate').val(1);            
        });

        $('.reg-modal .resetRegDate').click(function(){
            $('#reg-'+actvRegID+'-info .chooseNewDateDV').hide();  
            $('#reg-'+actvRegID+'-info .regDateDV').show(); 
            $('#reg-'+actvRegID+'-info #chngDate').val(0);                  
        });

        $('.reg-modal .accept').click(function(){
            $('#reg-'+actvRegID+'-info #reg-action').val('accept');
            $('#reg-'+actvRegID+'-info #regActionForm').submit();
            return;
        });
        $('.reg-modal .reject').click(function(){
            $('#reg-'+actvRegID+'-info #reg-action').val('reject');  
            $('#reg-'+actvRegID+'-info #regActionForm').submit();
            return;     
        });
        $('.reg-modal .waiting').click(function(){
            $('#reg-'+actvRegID+'-info #reg-action').val('waiting');
            $('#reg-'+actvRegID+'-info #regActionForm').submit();
            return;      
        });

       
        $('.one-pay').click(function(){
            actvRegID = this.id ;
            $('#modal-pay-info div.modal-content').html($('#pay-'+actvRegID+'-info').html());
        });
        $('.pay-modal .pay').click(function(){
            $('#pay-'+actvRegID+'-info #payActionForm').submit();
            return;      
        });

       

        ////////Search &  Filter & Sort//////
        var searchSortFilterParams = '';
        //Search//
        $('#srchInput-reg').keypress(function (e) {
            var key = e.which;
            if (key == 13) // the enter key code
            {
                $('button[class="srch-icon-reg"]').click();
                return false;
            }
        });
        $(".srch-icon-reg").click(function (event) {
            searchSortFilterParams = collectSearchSortFilterParamsRegs();
            document.location.href = url + searchSortFilterParams;
        });
        $(".header-search .clear-reg").on('click', function () {
            $('#srchInput-reg').val('');
            searchSortFilterParams = collectSearchSortFilterParamsRegs();
            document.location.href = url + searchSortFilterParams;
        });
        /////
        ///Sort///
        $('.sort-item-reg').click(function (event) {
            event.preventDefault();
            var e = $(this);
            $('#sort_val').val(e.data('val'));
            searchSortFilterParams = collectSearchSortFilterParamsRegs();
            document.location.href = url + searchSortFilterParams;
        });
        /////
        
        //Filter Course////
        $('.filter-crs-item-reg').click(function (event) {
            event.preventDefault();
            var e = $(this);
            $('#fltr_crs_val-reg').val(e.data('val'));
            searchSortFilterParams = collectSearchSortFilterParamsRegs();
            document.location.href = url + searchSortFilterParams;
        });
        /////
        
        //Filter Place////
        $('.filter-plc-item-reg').click(function (event) {
            event.preventDefault();
            var e = $(this);
            $('#fltr_plc_val').val(e.data('val'));
            searchSortFilterParams = collectSearchSortFilterParamsRegs();
            document.location.href = url + searchSortFilterParams;
        });
        /////
        
        //Filter Satus////
        $('.filter-sts-item-reg').click(function (event) {
            event.preventDefault();
            var e = $(this);
            $('#fltr_sts_val').val(e.data('val'));
            searchSortFilterParams = collectSearchSortFilterParamsRegs();
            document.location.href = url + searchSortFilterParams;
        });
        /////
        //Filter Date Range//
        $(".fltr-date-range-reg .drnge-icon-reg").click(function (event) {
            searchSortFilterParams = collectSearchSortFilterParamsRegs();
            document.location.href = url + searchSortFilterParams;
        });
        $(".fltr-date-range-reg .clear-dateRang").on('click', function () {
            $('.fltr-date-range-reg #dateRngFrm').val('');
            $('.fltr-date-range-reg #dateRangTo').val('');
            searchSortFilterParams = collectSearchSortFilterParamsRegs();
            document.location.href = url + searchSortFilterParams;
        });
        /////



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

            ////Filter Course////
            var fltrCrsVal = $('#fltr_crs_val-reg').val();
            var filterCrsParam = (fltrCrsVal != '' && fltrCrsVal != 0) ? "fltr_crs=" + fltrCrsVal : "";
            ////////// 

            ////Filter Place////
            var fltrPlcVal = $('#fltr_plc_val').val();
            var filterPlcParam = (fltrPlcVal != '' && fltrPlcVal != 0) ? "fltr_plc=" + fltrPlcVal : "";
            ////////// 

            ////Filter Status////
            var fltrStsVal = $('#fltr_sts_val').val();
            var filterStsParam = (fltrStsVal != '' && fltrStsVal != 0) ? "fltr_sts=" + fltrStsVal : "";
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
            if (filterCrsParam != "") {
                finalParams += (filterCrsParam + "&");
            }
            if (filterPlcParam != "") {
                finalParams += (filterPlcParam + "&");
            }
            if (filterStsParam != "") {
                finalParams += (filterStsParam + "&");
            }
            if (dateRngParam != "") {
                finalParams += (dateRngParam + "&");
            }
            finalParams = finalParams.replace(/&\s*$/, ""); //remove the last (&)
            
             return finalParams != "" ? "?" + finalParams : "";
        }

        ////////////////////
        

    
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
@endpush
@endsection