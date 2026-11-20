@extends('layoutmodule::layouts.main')

@section('title')
    إعدادات أختبار سرعة الكتابة
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-keyboard-o"></i> {{ $settings->title }}</h3>
            </div>
        </div>

        @include('typingtestmodule::admin.partials.tabs')
        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="card">
                <div class="card-content">
                    <form class="card-form side-form" method="POST" action="{{ route('admin.typing.settings.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 col-12">
                                <label>اسم الأختبار</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $settings->title }}" disabled>
                                    <small class="text-muted">اسم الأختبار ثابت ولا يمكن تعديله.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <label for="description">وصف الأختبار</label>
                                <div class="form-group">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $settings->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2 col-12">
                                <label for="duration_minutes">مدة الأختبار (دقيقة)</label>
                                <div class="form-group">
                                    <input type="number" min="1" class="form-control @error('duration_minutes') is-invalid @enderror"
                                        id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $settings->duration_minutes) }}">
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-12">
                                <label for="word_count">عدد الكلمات</label>
                                <div class="form-group">
                                    <input type="number" min="1" class="form-control @error('word_count') is-invalid @enderror"
                                        id="word_count" name="word_count" value="{{ old('word_count', $settings->word_count) }}">
                                    @error('word_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-12">
                                <label for="success_grade">درجة النجاح (عدد الكلمات الصحيحة)</label>
                                <div class="form-group">
                                    <input type="number" min="1" class="form-control @error('success_grade') is-invalid @enderror"
                                        id="success_grade" name="success_grade" value="{{ old('success_grade', $settings->success_grade) }}">
                                    @error('success_grade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <p class="text-muted small">
                            التعديلات تُطبَّق على المحاولات التي لم تبدأ بعد، ولا تؤثر على المحاولات الجارية أو المنتهية.
                        </p>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
