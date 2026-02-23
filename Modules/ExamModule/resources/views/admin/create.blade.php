@extends('layoutmodule::layouts.main')

@section('title')
    إنشاء أختبار جديد
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-plus-circle"></i>
                    إنشاء أختبار جديد
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
                                        action='{{ route(Auth::getDefaultDriver() . '.exam.store') }}'
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="title">عنوان الأختبار</label>
                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control @error('title') is-invalid @enderror"
                                                        id="title" name="title" value="{{ old('title') }}">
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="category_id">التصنيف</label>
                                                <div class="form-group">
                                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                                        id="category_id" name="category_id">
                                                        <option value="">-- اختر التصنيف --</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12 col-xs-12 col-6">
                                                <label for="description">وصف الأختبار</label>
                                                <div class="form-group">
                                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                                        rows="3">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="start_date">تاريخ البداية</label>
                                                <div class="form-group">
                                                    <input type="date"
                                                        class="form-control @error('start_date') is-invalid @enderror"
                                                        id="start_date" name="start_date" value="{{ old('start_date') }}">
                                                    @error('start_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="end_date">تاريخ النهاية</label>
                                                <div class="form-group">
                                                    <input type="date"
                                                        class="form-control @error('end_date') is-invalid @enderror"
                                                        id="end_date" name="end_date" value="{{ old('end_date') }}">
                                                    @error('end_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="duration_minutes">المدة (دقيقة)</label>
                                                <div class="form-group">
                                                    <input type="number"
                                                        class="form-control @error('duration_minutes') is-invalid @enderror"
                                                        id="duration_minutes" name="duration_minutes"
                                                        value="{{ old('duration_minutes') }}" min="1">
                                                    @error('duration_minutes')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="total_questions">عدد الأسئلة الكلي</label>
                                                <div class="form-group">
                                                    <input type="number"
                                                        class="form-control @error('total_questions') is-invalid @enderror"
                                                        id="total_questions" name="total_questions"
                                                        value="{{ old('total_questions') }}" min="1">
                                                    @error('total_questions')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="mcq_count">عدد أسئلة الاختيار من متعدد</label>
                                                <div class="form-group">
                                                    <input type="number"
                                                        class="form-control @error('mcq_count') is-invalid @enderror"
                                                        id="mcq_count" name="mcq_count" value="{{ old('mcq_count') }}"
                                                        min="0">
                                                    @error('mcq_count')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="true_false_count">عدد أسئلة صح/خطأ</label>
                                                <div class="form-group">
                                                    <input type="number"
                                                        class="form-control @error('true_false_count') is-invalid @enderror"
                                                        id="true_false_count" name="true_false_count"
                                                        value="{{ old('true_false_count') }}" min="0">
                                                    @error('true_false_count')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            {{-- <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                            <label for="total_grade">الدرجة الكلية</label>
                                            <div class="form-group">
                                                <input type="number" step="0.01" class="form-control @error('total_grade') is-invalid @enderror"
                                                    id="total_grade" name="total_grade" value="{{ old('total_grade') }}" min="1">
                                                @error('total_grade')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> --}}

                                            <div class="col-lg-2 col-sm-12 col-xs-12 col-6">
                                                <label for="success_grade">درجة النجاح</label>
                                                <div class="form-group">
                                                    <input type="number" step="0.01"
                                                        class="form-control @error('success_grade') is-invalid @enderror"
                                                        id="success_grade" name="success_grade"
                                                        value="{{ old('success_grade') }}" min="1">
                                                    @error('success_grade')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <a href="{{ route(Auth::getDefaultDriver() . '.exam.index') }}"
                                                class="btn btn-secondary">إلغاء</a>

                                            <button type="submit" class="btn btn-primary">حفظ</button>
                                    </form>
                                </div>
                                <div class="col-lg-1 col-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endsection
