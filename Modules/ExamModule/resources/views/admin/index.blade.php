@extends('layoutmodule::admin.main')

@section('title')
    الأختبارات
@endsection

@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-book"></i>
                    &nbsp;
                    الأختبارات
                </h3>
            </div>
        </div>

        @include('layoutmodule::admin.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8"></div>
                                <div class="col-lg-4">
                                    <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                        href="{{ route(Auth::getDefaultDriver() . '.exam.create') }}" role="button">إنشاء أختبار جديد</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>عنوان الأختبار</th>
                                            <th>تاريخ البداية</th>
                                            <th>تاريخ النهاية</th>
                                            <th>عدد الأسئلة</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($exams)
                                            @foreach($exams as $exam)
                                                <tr>
                                                    <td class="strong">{{ $exam->title }}</td>
                                                    <td>{{ $exam->start_date }}</td>
                                                    <td>{{ $exam->end_date }}</td>
                                                    <td>{{ $exam->total_questions }}</td>
                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.exam.edit', $exam->id) }}"
                                                            role="button">تعديل</a>

                                                        <a class="btn btn-danger"
                                                            href="{{ route(Auth::getDefaultDriver() . '.exam.delete', $exam->id) }}"
                                                            onclick="return confirm('هل انت متأكد انك تريد حذف هذا الأختبار ؟')"
                                                            role="button">حذف</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="5" class="text-center">لا توجد أختبارات</td></tr>
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
