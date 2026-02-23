@extends('layoutmodule::layouts.main')

@section('title')
    إضافة تصنيف
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-plus-circle"></i>
                    إضافة تصنيف جديد
                </h3>
            </div>
        </div>

        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-lg-12 col-12">
                                    <form class="card-form side-form" method="POST"
                                        action="{{ route(Auth::getDefaultDriver() . '.categories.store') }}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="name">اسم التصنيف</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" value="{{ old('name') }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="description">الوصف</label>
                                                <div class="form-group">
                                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                                        id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <a href="{{ route(Auth::getDefaultDriver() . '.categories.index') }}"
                                                class="btn btn-secondary">إلغاء</a>
                                            <button type="submit" class="btn btn-primary">حفظ</button>
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
