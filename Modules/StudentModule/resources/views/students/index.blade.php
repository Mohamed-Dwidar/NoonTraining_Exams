@extends('layoutmodule::admin.main')

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
                                        href="{{ route(Auth::getDefaultDriver() . '.students.create') }}" role="button">إضافة طالب جديد</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>الاسم</th>
                                            <th>البريد الإلكتروني</th>
                                            <th>الهاتف</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($students)
                                            @foreach($students as $student)
                                                <tr>
                                                    <td class="strong">{{ $student->name }}</td>
                                                    <td>{{ $student->email ?? '-' }}</td>
                                                    <td>{{ $student->phone ?? '-' }}</td>
                                                    <td>

                                                        <a class="btn btn-info"
                                                            href="{{ route(Auth::getDefaultDriver() . '.students.showExams', $student->id) }}"
                                                            role="button">إستعراض الأختبارات</a>

                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.students.edit', $student->id) }}"
                                                            role="button">تعديل</a>

                                                        <form action="{{ route(Auth::getDefaultDriver() . '.students.delete', $student->id) }}"
                                                            method="POST" class="d-inline-block">
                                                            @csrf
                                                            <button onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                                class="btn btn-danger">حذف</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="5" class="text-center">لا يوجد طلاب</td></tr>
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
