@extends('layoutmodule::layouts.main')

@section('title')
    نتائج أختبار سرعة الكتابة
@endsection

@section('content')
    @php $guard = Auth::getDefaultDriver(); @endphp
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-keyboard-o"></i> أختبار سرعة الكتابة — النتائج</h3>
            </div>
        </div>

        @include('typingtestmodule::admin.partials.tabs')
        @include('layoutmodule::layouts.flash')

        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route($guard . '.typing.results.index') }}" class="row">
                        <div class="col-lg-4 col-12 mb-1">
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                placeholder="بحث باسم الطالب أو رقم الهوية أو الهاتف">
                        </div>
                        <div class="col-lg-2 col-6 mb-1">
                            <select name="result" class="form-control">
                                <option value="">كل النتائج</option>
                                <option value="passed" @selected(request('result') === 'passed')>ناجح</option>
                                <option value="failed" @selected(request('result') === 'failed')>راسب</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-6 mb-1">
                            <select name="type" class="form-control">
                                <option value="">الكل</option>
                                <option value="standalone" @selected(request('type') === 'standalone')>منفصل</option>
                                <option value="linked" @selected(request('type') === 'linked')>مرتبط بدورة</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-12 mb-1">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> بحث</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="head">
                                    <th>الطالب</th>
                                    <th>رقم الهوية</th>
                                    <th>النوع</th>
                                    <th>تاريخ الأختبار</th>
                                    <th>الكلمات الصحيحة</th>
                                    <th>النتيجة</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attempts as $attempt)
                                    <tr>
                                        <td class="strong">{{ optional($attempt->student)->name ?? '-' }}</td>
                                        <td>{{ optional($attempt->student)->national_id ?? '-' }}</td>
                                        <td>
                                            @if ($attempt->isLinked())
                                                <span class="badge bg-info">{{ optional(optional($attempt->studentExam)->exam)->title ?? 'دورة' }}</span>
                                            @else
                                                <span class="badge bg-blue-grey">منفصل</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($attempt->started_at)->format('Y-m-d H:i') }}</td>
                                        <td>{{ $attempt->correct_words }} / {{ $attempt->word_count }}</td>
                                        <td>
                                            @if ($attempt->passed)
                                                <span class="badge bg-success"><i class="fa fa-check"></i> ناجح</span>
                                            @else
                                                <span class="badge bg-danger"><i class="fa fa-times"></i> راسب</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route($guard . '.typing.results.show', $attempt->id) }}">التفاصيل</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد نتائج</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        {{ $attempts->links('layoutmodule::layouts.custom_pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
