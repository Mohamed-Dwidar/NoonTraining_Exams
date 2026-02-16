@extends('layoutmodule::admin.main')

@section('title')
    التصنيفات
@endsection

@section('content')

    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3>
                    <i class="fa fa-tags"></i>
                    &nbsp;
                    التصنيفات
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
                                        href="{{ route(Auth::getDefaultDriver() . '.categories.create') }}"
                                        role="button">إضافة تصنيف جديد</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr class="head">
                                            <th>الأسم</th>
                                            <th>الوصف</th>
                                            <th>الأسئلة</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($categories)
                                            @foreach ($categories as $cat)
                                                <tr>
                                                    <td class="strong">{{ $cat->name }}</td>
                                                    <td>{{ $cat->description }}</td>
                                                    <td>{{ $cat->questions->count() }}</td>
                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="{{ route(Auth::getDefaultDriver() . '.categories.edit', $cat->id) }}"
                                                            role="button">تعديل</a>

                                                        <form
                                                            action="{{ route(Auth::getDefaultDriver() . '.categories.destroy', $cat->id) }}"
                                                            method="POST" class="d-inline-block">
                                                            @csrf @method('DELETE')
                                                            <button onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                                class="btn btn-danger">
                                                                حذف
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center">لا توجد تصنيفات</td>
                                            </tr>
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
