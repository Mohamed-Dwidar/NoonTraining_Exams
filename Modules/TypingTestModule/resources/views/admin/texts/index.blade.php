@extends('layoutmodule::layouts.main')

@section('title')
    بنك نصوص أختبار سرعة الكتابة
@endsection

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-keyboard-o"></i> {{ $settings->title }} — بنك النصوص</h3>
            </div>
        </div>

        @include('typingtestmodule::admin.partials.tabs')
        @include('layoutmodule::layouts.flash')
        @if ($warning = Session::get('warning'))
            <div class="alert alert-warning">{{ $warning }}</div>
        @endif

        <div class="content-body">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <p class="text-muted mb-0">
                                يُختار نص عشوائي من النصوص النشطة لكل محاولة، ويُعرض للطالب أول
                                <strong>{{ $settings->word_count }}</strong> كلمة منه.
                            </p>
                        </div>
                        <div class="col-lg-4">
                            <a class="btn btn-success round btn-min-width mr-1 mb-1" href="{{ route(Auth::getDefaultDriver() . '.typing.texts.create') }}">
                                إضافة نص جديد
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr class="head">
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>بداية النص</th>
                                    <th>عدد الكلمات</th>
                                    <th>الحالة</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($texts as $text)
                                    <tr>
                                        <td>{{ $text->id }}</td>
                                        <td class="strong">{{ $text->title ?: '-' }}</td>
                                        <td>{{ \Illuminate\Support\Str::words($text->content, 12) }}</td>
                                        <td>
                                            <span class="badge {{ $text->words_count < $settings->word_count ? 'bg-danger' : 'bg-success' }}"
                                                @if ($text->words_count < $settings->word_count) title="أقل من عدد الكلمات المطلوب" @endif>
                                                {{ $text->words_count }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($text->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-blue-grey">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-warning" href="{{ route(Auth::getDefaultDriver() . '.typing.texts.edit', $text->id) }}">تعديل</a>
                                            <form action="{{ route(Auth::getDefaultDriver() . '.typing.texts.delete', $text->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <button onclick="return confirm('هل أنت متأكد من الحذف؟')" class="btn btn-danger">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            لا توجد نصوص بعد. أضف نصاً واحداً على الأقل حتى يتمكن الطلاب من بدء الأختبار.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
