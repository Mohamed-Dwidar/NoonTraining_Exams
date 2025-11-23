@extends('layoutmodule::admin.main')

@section('title', 'تعديل تصنيف')

@section('content')
    <div class="container-fluid">

        @include('layoutmodule::admin.flash')

        <div class="card shadow-sm p-4">
            <h3 class="mb-4">تعديل التصنيف</h3>

            <form method="POST" action="{{ route(Auth::getDefaultDriver() .'.categories.update', $category->id) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-bold">اسم التصنيف</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">الوصف</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
                </div>

                <button type="submit" class="btn btn-success px-4">حفظ</button>
                <a href="{{route(Auth::getDefaultDriver() .'.categories.index') }}" class="btn btn-secondary">إلغاء</a>

            </form>
        </div>

    </div>
@endsection
