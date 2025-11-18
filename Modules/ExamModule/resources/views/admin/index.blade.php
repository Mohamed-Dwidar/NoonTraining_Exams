@extends('layoutmodule::admin.main')

@section('title')
    الامتحانات
@endsection

@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-file"></i>
                    &nbsp;
                    الامتحانات
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
                                        href="{{ route('admin.exam.create') }}" role="button">
                                        أضف امتحان جديد
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div@extends class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th> عنوان الامتحان </th>
                                            <th> تاريخ البداية </th>
                                            <th> تاريخ النهاية </th>
                                            <th> عدد الأسئلة </th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if ($exams)
                                            @foreach ($exams as $exam)
                                                <tr>
                                                    <td class="strong">{{ $exam->title }}</td>

                                                    <td>{{ $exam->start_date }}</td>
                                                    <td>{{ $exam->end_date }}</td>

                                                    <td>{{ $exam->total_questions }}</td>

                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.exam.edit', $exam->id) }}"
                                                            role="button">
                                                            تعديل
                                                        </a>

                                                        <a class="btn btn-danger"
                                                            href="{{ route(Auth::getDefaultDriver() . '.exam.delete', $exam->id) }}"
                                                            onclick="return confirm('هل انت متأكد انك تريد حذف هذا الامتحان ؟')"
                                                            role="button">
                                                            حذف
                                                        </a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                            </div@extends('layoutmodule::admin.main') @section('title', 'الامتحانات' ) @section('content') <div
                                class="container-fluid">

                            @include('layoutmodule::admin.flash')

                            <a href="{{ route(Auth::getDefaultDriver() . '.exam.create') }}" class="btn btn-primary mb-3">
                                إنشاء امتحان جديد
                            </a>


                            <div class="card">
                                <div class="card-body">

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>العنوان</th>
                                                <th>التاريخ</th>
                                                <th>تحكم</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($exams as $exam)
                                                <tr>
                                                    <td>{{ $exam->title }}</td>
                                                    <td>{{ $exam->start_date }} - {{ $exam->end_date }}</td>
                                                    <td>
                                                        @if (auth()->user() && auth()->user()->hasPermission('manage_exams'))
                                                            <a href="{{ route(Auth::getDefaultDriver() . '.exam.question.list', $exam->id) }}"
                                                                class="btn btn-success btn-sm">
                                                                إضافة الأسئلة / تعديل
                                                            </a>
                                                        @endif

                                                        @if (auth()->user() && auth()->user()->hasPermission('manage_exams'))
                                                            <a href="{{ route(Auth::getDefaultDriver() . '.exam.question.show', $exam->id) }}"
                                                                class="btn btn-info btn-sm">
                                                                عرض الأسئلة والإجابات
                                                            </a>
                                                        @endif
                                                        @if (auth()->user() && auth()->user()->hasPermission('manage_exams'))
                                                            <a href="{{ route(Auth::getDefaultDriver() . '.exam.delete', $exam->id) }}"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('هل انت متأكد انك تريد حذف هذا الامتحان ؟')">
                                                                حذف الامتحان
                                                            </a>
                                                        @endif
                                                    </td>


                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                    </div>

                @endsection
            </div>
        </div>

    </div>
</div>

</div>

@endsection
