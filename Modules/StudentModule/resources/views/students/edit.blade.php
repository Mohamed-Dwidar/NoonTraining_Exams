@extends('layoutmodule::admin.main')

@section('title')
    تعديل بيانات طالب
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-edit"></i>
                    تعديل بيانات الطالب
                </h3>
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
                                    <form class="card-form side-form" method="POST"
                                        action="{{ route(Auth::getDefaultDriver() . '.students.update') }}" enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" name="id" value="{{ $student->id }}">

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="name">اسم الطالب</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name" value="{{ old('name', $student->name) }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="phone">رقم الهاتف</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                        id="phone" name="phone" value="{{ old('phone', $student->phone) }}">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="email">البريد الإلكتروني</label>
                                                <div class="form-group">
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" value="{{ old('email', $student->email) }}">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                                <label for="national_id">الرقم القومي</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control @error('national_id') is-invalid @enderror"
                                                        id="national_id" name="national_id" value="{{ old('national_id', $student->national_id) }}">
                                                    @error('national_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-sm-12 col-xs-12 col-6">
                                                <label for="password">الرقم السري</label>
                                                <div class="form-group">
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                        id="password" name="password">
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <a href="{{ route(Auth::getDefaultDriver() . '.students.index') }}"
                                                class="btn btn-secondary">إلغاء</a>

                                            <button type="submit" class="btn btn-primary">تحديث</button>
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
