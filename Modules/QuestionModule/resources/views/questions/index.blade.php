@extends('layoutmodule::admin.main')

@section('title', 'إدارة الأسئلة')

@section('content')
<div class="container-fluid">

    @include('layoutmodule::admin.flash')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">إدارة الأسئلة</h3>
        <a href="{{ route(Auth::getDefaultDriver() . '.question.create') }}" class="btn btn-primary">
            <i class="fas fa-plus ms-2"></i> إضافة سؤال جديد
        </a>
    </div>

    <!-- فلترة حسب الفئة -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">فلترة حسب الفئة</label>
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الفئات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">فلترة حسب النوع</label>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الأنواع</option>
                        <option value="mcq" {{ request('type') == 'mcq' ? 'selected' : '' }}>اختيار من متعدد</option>
                        <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>صح / خطأ</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">بحث</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث في نص السؤال..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>نص السؤال</th>
                            <th width="150">الفئة</th>
                            <th width="120">النوع</th>
                            <th width="120">الإجابة</th>
                            <th width="120">الحالة</th>
                            <th width="150" class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                        <tr>
                            <td>{{ $question->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        @if($question->image)
                                            <img src="{{ asset('storage/' . $question->image) }}" alt="صورة السؤال" class="rounded" width="40" height="40">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-question text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-truncate" style="max-width: 300px;" title="{{ $question->question_text }}">
                                            {{ Str::limit($question->question_text, 60) }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $question->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $question->category->name }}
                                </span>
                            </td>
                            <td>
                                @if($question->type === 'mcq')
                                    <span class="badge bg-primary">اختيار من متعدد</span>
                                @else
                                    <span class="badge bg-success">صح / خطأ</span>
                                @endif
                            </td>
                            <td>
                                @if($question->type === 'mcq')
                                    <span class="badge bg-success">{{ $question->answer['correct_answer'] ?? $question->answer }}</span>
                                @else
                                    <span class="badge {{ $question->answer === 'true' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $question->answer === 'true' ? 'صح' : 'خطأ' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $question->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $question->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route(Auth::getDefaultDriver() . '.question.edit', $question->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route(Auth::getDefaultDriver() . '.question.destroy', $question->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-3"></i>
                                <br>
                                لا توجد أسئلة
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

          
        {{-- {{ $questions->links() }} --}}
        </div>
    </div>

</div>
@endsection