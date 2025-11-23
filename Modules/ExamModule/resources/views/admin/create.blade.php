@extends('layoutmodule::admin.main')

@section('title', 'إنشاء امتحان جديد')

@section('content')

<div class="container-fluid" dir="rtl">
    @include('layoutmodule::admin.flash')

    <div class="card shadow-sm p-4">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <h5 class="mb-0">إنشاء امتحان جديد</h5>
        </div>

        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route(Auth::getDefaultDriver() .'.exam.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title" class="form-label">عنوان الامتحان</label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}"
                                   class="form-control @error('title') is-invalid @enderror" placeholder="أدخل عنوان الامتحان" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id" class="form-label">التصنيف</label>
                            <select id="category_id" name="category_id"
                                    class="form-control @error('category_id') is-invalid @enderror" required>
                                <option value="">اختر التصنيف</option>
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

                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="start_date" class="form-label">تاريخ البداية</label>
                            <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}"
                                   class="form-control @error('start_date') is-invalid @enderror">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="end_date" class="form-label">تاريخ النهاية</label>
                            <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}"
                                   class="form-control @error('end_date') is-invalid @enderror">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="duration_minutes" class="form-label">المدة (بالدقائق)</label>
                            <input id="duration_minutes" type="number" name="duration_minutes"
                                   value="{{ old('duration_minutes') }}" min="1"
                                   class="form-control @error('duration_minutes') is-invalid @enderror">
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="total_questions" class="form-label">عدد الأسئلة</label>
                            <input id="total_questions" type="number" name="total_questions"
                                   value="{{ old('total_questions') }}" min="1"
                                   class="form-control @error('total_questions') is-invalid @enderror">
                            @error('total_questions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="success_grade" class="form-label">درجة النجاح</label>
                            <input id="success_grade" type="number" step="0.01" name="success_grade"
                                   value="{{ old('success_grade') }}" min="0"
                                   class="form-control @error('success_grade') is-invalid @enderror">
                            @error('success_grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="total_grade" class="form-label">الدرجة الكلية</label>
                            <input id="total_grade" type="number" step="0.01" name="total_grade"
                                   value="{{ old('total_grade') }}" min="0"
                                   class="form-control @error('total_grade') is-invalid @enderror">
                            @error('total_grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 mt-3 d-flex justify-content-between">
                    <a href="{{ route(Auth::getDefaultDriver() .'.exam.index') }}" class="btn btn-outline-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">إنشاء الامتحان</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
