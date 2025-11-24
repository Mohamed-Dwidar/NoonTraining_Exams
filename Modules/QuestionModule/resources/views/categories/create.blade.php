@extends('layoutmodule::admin.main')

@section('title', 'إضافة تصنيف')

@section('content')
<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3><i class="fa fa-tags"></i> إضافة تصنيف جديد</h3>
        </div>
    </div>

    @include('layoutmodule::admin.flash')
    
    <div class="content-body">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-lg-12 col-12">

                        <form method="POST" action="{{ route(Auth::getDefaultDriver() . '.categories.store') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-lg-12 col-sm-12">
                                    <label for="name" class="form-label fw-bold">اسم التصنيف</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ old('name', $category->name ?? '') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12 col-sm-12">
                                    <label for="description" class="form-label fw-bold">الوصف</label>
                                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-success px-4">حفظ</button>
                                <a href="{{ route(Auth::getDefaultDriver() . '.categories.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>

                        </form>

                         </div>
                            <div class="col-lg-1 col-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
