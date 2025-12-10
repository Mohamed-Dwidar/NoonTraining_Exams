@extends('layoutmodule::admin.main')

@section('title', 'إدارة الأسئلة')

@section('content')
    <div class="container-fluid">
        @include('layoutmodule::admin.flash')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>الأسئلة</h3>
            <a href="{{ route(Auth::getDefaultDriver() . '.question.create') }}" class="btn btn-primary">
                إضافة سؤال
            </a>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>السؤال</th>
                            <th>النوع</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $question)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($question->question_text, 80) }}</td>
                                <td>
                                    @if($question->type == 'mcq')
                                        <span class="badge bg-primary">اختياري</span>
                                    @else
                                        <span class="badge bg-success">صح/خطأ</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route(Auth::getDefaultDriver() . '.question.edit', $question->id) }}" 
                                       class="btn btn-warning btn-sm">
                                        تعديل
                                    </a>
                                    <a href="{{ route(Auth::getDefaultDriver() . '.question.delete', $question->id) }}" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('هل أنت متأكد؟')">
                                        حذف
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection