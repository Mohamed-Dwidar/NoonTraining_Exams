@extends('layoutmodule::admin.main')

@section('title')
اضافه فرع جديد
@endsection

@section('content')

<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3><i class="fa fa-plus"></i>
                اضافه فرع جديد
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
                                    action='{{ route("admin.branchs.store") }}' enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-xs-12 col-6">
                                            <label for="name">اسم الفرع</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{old('name')}}">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-xs-12 col-6">
                                            <label for="address">عنوان الفرع</label>
                                            <div class="form-group">
                                                <textarea class="form-control" id="address"
                                                    name="address">{{old('address')}}</textarea>
                                            </div>
                                        </div>
                                    </div> --}}

                                    {{-- <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-xs-12 col-6">
                                            <label>هل متاح؟</label>
                                            <div class="input-group">
                                                <label class="display-inline-block custom-control custom-radio ml-1">
                                                    <input type="radio" name="is_available" class="custom-control-input"
                                                        value="1" checked>
                                                    <span class="custom-control-indicator"></span>
                                                    <span class="custom-control-description ml-0">نعم</span>
                                                </label>
                                                <label class="display-inline-block custom-control custom-radio">
                                                    <input type="radio" name="is_available" class="custom-control-input"
                                                        value="0">
                                                    <span class="custom-control-indicator"></span>
                                                    <span class="custom-control-description ml-0">لا</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div> --}}




                                    <div class="col-12">
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


@push('scripts')
<script type="text/javascript"
    src="{{ asset('admin-assets/vendors/js/editors/tinymce/plugin/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript"
    src="{{ asset('admin-assets/vendors/js/editors/tinymce/plugin/tinymce/init-tinymce.js') }}"></script>
@endpush


@endsection