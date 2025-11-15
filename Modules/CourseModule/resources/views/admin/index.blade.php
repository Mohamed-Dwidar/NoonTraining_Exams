@extends('layoutmodule::admin.main')

@section('title')
    الدورات
@endsection


@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-desktop"></i>
                    &nbsp;
                    الدورات
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
                                <div class="col-lg-5 col-md-4 col-sm-3 col-xs-12">
                                    {{-- <div class="filters">
                                            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="icon-filter"></i>
                                                Filter
                                            </a>
                                        <div class="dropdown-menu arrow dropdown-filter"> --}}
                                    <input type="hidden" id="fltr_val"
                                        value="@if (app('request')->fltr != null) {{ app('request')->fltr }} @endif" />

                                    {{-- <button class="dropdown-item filter-item" type="button" data-val="no">
                                                    No Filer
                                                </button>
                                                <button class="dropdown-item filter-item" type="button" data-val="1">
                                                    Active
                                                </button>
                                                <button class="dropdown-item filter-item" type="button" data-val="2">
                                                    Not Active
                                                </button>
                                            </div>
                                        </div> --}}
                                    <div class="filters" @if (Auth::guard('user')->check()) style="display:none" @endif>
                                        <input type="hidden" id="fltr_brnch_val"
                                            value="@if (app('request')->brnch != null) {{ app('request')->brnch }} @endif" />
                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="icon-sort"></i>
                                            الفرع
                                        </a>
                                        <div class="dropdown-menu arrow dropdown-filter">
                                            <input type="hidden" id="fltr_brnch"
                                                value="@if (app('request')->fltr != null) {{ app('request')->fltr }} @endif" />

                                            <button class="dropdown-item filter-item-brnch" type="button" data-val="no">
                                                الكل
                                            </button>
                                            @foreach ($branches as $branch)
                                                <button class="dropdown-item filter-item-brnch" type="button"
                                                    data-val="{{ $branch->id }}">
                                                    {{ $branch->name }}
                                                </button>
                                            @endforeach
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

                                            <button class="dropdown-item sort-item" type="button" data-val="no">
                                                افتراضي
                                            </button>
                                            <button class="dropdown-item sort-item" type="button" data-val="name_az">
                                                بالاسم أ-ي
                                            </button>
                                            <button class="dropdown-item sort-item" type="button" data-val="name_za">
                                                بالاسم ي-أ
                                            </button>
                                            <button class="dropdown-item sort-item" type="button" data-val="reg_az">
                                                تاريخ التسجيل الاقدم
                                            </button>
                                            <button class="dropdown-item sort-item" type="button" data-val="reg_za">
                                                تاريخ التسجيل الاحدث
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6 col-sm-9 col-xs-12">
                                    <div class="header-search">
                                        <label>بحث</label>
                                        <input value='@if (app('request')->srch != null) {{ app('request')->srch }} @endif'
                                            id="srchInput" />
                                        <button class="srch-icon">
                                            <i class="icon-search7"></i>
                                        </button>
                                        <a href="#" class="clear">إلغاء</a>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    @cannot('view_only')
                                        <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                            href="{{ route(Auth::getDefaultDriver() . '.courses.add') }}" role="button">أضف
                                            دورة
                                            جديدة</a>
                                    @endcannot
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>اسم الدورة</th>
                                            @if (Auth::guard('admin')->check())
                                                <th>الفرع</th>
                                            @endif
                                            <th>تاريخ بداية الدورة</th>
                                            <th>تاريخ انتهاء الدورة</th>
                                            <th>السعر</th>
                                            <th>رسوم الاختبار</th>
                                            <th class="align-center">الطلاب</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($courses))
                                            @foreach ($courses as $course)
                                                <tr>
                                                    <td class="strong">
                                                        {{ $course->FullName }}
                                                    </td>
                                                    @if (Auth::guard('admin')->check())
                                                        <td>{{ $course->branch->name }}</td>
                                                    @endif
                                                    <td>{{ $course->start_at }}</td>
                                                    <td>{{ $course->end_at }}</td>
                                                    <td>{{ $course->price }} ر.س</td>
                                                    <td>{{ $course->exam_fees }} ر.س</td>
                                                    <td class="align-center">
                                                        {{ $course->courses_regs->count() }}
                                                    </td>

                                                    <td>
                                                        <a class="btn btn-primary"
                                                            href="{{ route(Auth::getDefaultDriver() . '.courses.show', $course->id) }}"
                                                            role="button">
                                                            استعراض </a>

                                                        @cannot('view_only')
                                                            @if (Auth::guard('admin')->check() || Auth::user()->can('can_edit'))
                                                                <a class="btn btn-warning"
                                                                    href="{{ route(Auth::getDefaultDriver() . '.courses.edit', $course->id) }}"
                                                                    role="button">تعديل</a>
                                                            @endif

                                                            @if (Auth::guard('admin')->check() || Auth::user()->can('can_delete'))
                                                                {{-- @if (Auth::guard('admin')->check()) --}}
                                                                <a class="btn btn-danger"
                                                                    href="{{ route(Auth::getDefaultDriver() . '.courses.delete', $course->id) }}"
                                                                    onclick="return confirm('هل انت متأكد انك تريد حذف هذه الدورة ؟')"
                                                                    role="button">حذف</a>
                                                                {{-- @endif --}}
                                                            @endif
                                                        @endcannot
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                {{ $courses->appends(request()->query())->links('layoutmodule::admin.custom_pagination') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            var url = "{{ url(Auth::getDefaultDriver()) }}" + '/courses';
        </script>
    @endpush


@endsection
