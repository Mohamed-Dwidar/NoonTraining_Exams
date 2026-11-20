@extends('layoutmodule::layouts.main')

@section('title')
    تعديل نص - أختبار سرعة الكتابة
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-edit"></i> تعديل النص</h3>
            </div>
        </div>

        @include('typingtestmodule::admin.partials.tabs')
        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="card">
                <div class="card-content">
                    <form class="card-form side-form" method="POST" action="{{ route(Auth::getDefaultDriver() . '.typing.texts.update') }}">
                        <input type="hidden" name="id" value="{{ $text->id }}">
                        @include('typingtestmodule::admin.texts._form')
                        <div class="col-12">
                            <a href="{{ route(Auth::getDefaultDriver() . '.typing.texts.index') }}" class="btn btn-secondary">إلغاء</a>
                            <button type="submit" class="btn btn-primary">تحديث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
