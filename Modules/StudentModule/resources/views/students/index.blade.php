@extends('layoutmodule::admin.main')

@section('title', 'قائمة الطلاب')

@section('content')
    <div class="container-fluid">

        @include('layoutmodule::admin.flash')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>الطلاب</h3>
            <a href="{{ route(Auth::getDefaultDriver() . '.students.create') }}" class="btn btn-primary">إضافة طالب</a>
        </div>

        <div class="card shadow-sm p-3 mt-2">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الكود</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>تاريخ الإنشاء</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->student_code }}</td>
                            <td>{{ $student->email ?? '-' }}</td>
                            <td>{{ $student->phone ?? '-' }}</td>
                            <td>{{ $student->created_at->format('Y-m-d') }}</td>

                            <td class="text-center">

                                <a href="{{ route(Auth::getDefaultDriver() . '.students.edit', $student->id) }}"
                                    class="btn btn-sm btn-warning">
                                    تعديل
                                </a>

                                <a href="{{ route(Auth::getDefaultDriver() . '.students.showExams', $student->id) }}"
                                    class="btn btn-sm btn-warning">
                                    ربط امتحان
                                </a>

                                <form action="{{ route(Auth::getDefaultDriver() . '.students.delete', $student->id) }}"
                                    method="POST" class="d-inline-block">
                                    @csrf
                                    <button onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                        class="btn btn-sm btn-danger">
                                        حذف
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا يوجد طلاب</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>
@endsection
