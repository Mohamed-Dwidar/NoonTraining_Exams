@extends('layoutmodule::layouts.main')

@section('title')
    الأسئلة
@endsection

@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-question-circle"></i>
                    &nbsp;
                    الأسئلة
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8"></div>
                                <div class="col-lg-4">
                                    <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                        href="{{ route(Auth::getDefaultDriver() . '.question.create') }}"
                                        role="button">إضافة أسئلة جديدة</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>السؤال</th>
                                            <th>النوع</th>
                                            <th>التصنيف</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($questions)
                                            @foreach ($questions as $question)
                                                <tr>
                                                    <td class="strong">{{ Str::limit($question->question_text, 60) }}</td>
                                                    <td>
                                                        @if ($question->type == 'mcq')
                                                            <span class="badge badge-primary">اختياري</span>
                                                        @else
                                                            <span class="badge badge-success">صح/خطأ</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $question->category->name ?? 'غير محدد' }}</td>
                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.question.edit', $question->id) }}"
                                                            role="button">تعديل</a>

                                                        <a class="btn btn-danger"
                                                            href="{{ route(Auth::getDefaultDriver() . '.question.delete', $question->id) }}"
                                                            onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                            role="button">حذف</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">لا توجد أسئلة</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $questions->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
