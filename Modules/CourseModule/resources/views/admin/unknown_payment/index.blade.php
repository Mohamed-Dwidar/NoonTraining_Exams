@extends('layoutmodule::admin.main')

@section('title')
    التحويلات المجهولة
@endsection


@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-desktop"></i>
                    &nbsp;
                    التحويلات المجهولة
                </h3>
                {{-- <a href="course.html">الدورات /</a> --}}
            </div>
        </div>

        @include('layoutmodule::admin.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                {{-- <div class="col-5">
                                <h2> الدورات</h2>
                            </div> --}}
                                <div class="col-lg-8"></div>
                                <div class="col-lg-4">
                                    @cannot('view_only')
                                        <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                            href="{{ route(Auth::getDefaultDriver() . '.unknown_payment.add') }}"
                                            role="button">أضف تحويل جديد</a>
                                    @endcannot
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>اسم المحول</th>
                                            <th>المبلغ المحول</th>
                                            <th>تاريخ التحويل</th>
                                            <th>طريقة التحويل</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($unknown_payments))
                                            @foreach ($unknown_payments as $unknown_payment)
                                                <tr>
                                                    <td class="strong">{{ $unknown_payment->transferor_name }}</td>
                                                    <td>{{ $unknown_payment->amount }}</td>
                                                    <td>{{ $unknown_payment->paid_at }}</td>
                                                    <td>{{ $unknown_payment->pay_method }}</td>
                                                    <td>
                                                        @cannot('view_only')
                                                            @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                <a class="btn btn-success"
                                                                    href="{{ route(Auth::getDefaultDriver() . '.unknown_payment.assign', $unknown_payment->id) }}"
                                                                    role="button">ربط بالحجز</a>
                                                            @endif

                                                            @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                <a class="btn btn-warning"
                                                                    href="{{ route(Auth::getDefaultDriver() . '.unknown_payment.edit', $unknown_payment->id) }}"
                                                                    role="button">تعديل</a>
                                                            @endif

                                                            @if (Auth::guard('admin')->check() || Auth::user()->can('can_delete'))
                                                                <a class="btn btn-danger"
                                                                    href="{{ route(Auth::getDefaultDriver() . '.unknown_payment.delete', $unknown_payment->id) }}"
                                                                    onclick="return confirm('هل انت متأكد انك تريد حذف هذه الدورة ؟')"
                                                                    role="button">حذف</a>
                                                            @endif
                                                        @endcannot
                                                    </td>
                                                </tr>
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


@endsection
