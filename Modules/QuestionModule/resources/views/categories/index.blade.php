@extends('layoutmodule::admin.main')

@section('title', 'قائمة التصنيفات')

@section('content')
<div class="container-fluid">

    @include('layoutmodule::admin.flash')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>التصنيفات</h3>
        <a href="{{ route(Auth::getDefaultDriver() . '.categories.create') }}" class="btn btn-primary">إضافة تصنيف</a>
    </div>

    <div class="card shadow-sm p-3 mt-2">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الوصف</th>
                    <th>تاريخ الإنشاء</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $cat)
                    <tr>
                        <td>{{ $cat->name }}</td>
                        <td>{{ $cat->description }}</td>
                        <td>{{ $cat->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <a href="{{ route(Auth::getDefaultDriver() . '.categories.edit', $cat->id) }}" class="btn btn-sm btn-warning">تعديل</a>

                            <form action="{{ route(Auth::getDefaultDriver() . '.categories.destroy', $cat->id) }}" method="POST" class="d-inline-block">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('هل أنت متأكد من الحذف؟')" class="btn btn-sm btn-danger">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">لا توجد تصنيفات</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $categories->links() }}
    </div>

</div>
@endsection
