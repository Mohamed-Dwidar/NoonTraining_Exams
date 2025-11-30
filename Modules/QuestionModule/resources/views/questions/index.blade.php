@extends('layoutmodule::admin.main')

@section('title', 'إدارة الأسئلة')

@section('content')
    <style>
        .filter-card {
            background: #ffffffcc;
            border-radius: 14px;
            padding: 20px;
            backdrop-filter: blur(6px);
            border: 1px solid #eee;
        }

        .page-title-box {
            padding: 20px 0;
        }

        .page-title-box h3 {
            font-weight: 700;
            font-size: 26px;
        }

        .custom-table thead th {
            background: #f7f7f7 !important;
            font-weight: 600 !important;
            border-bottom: 2px solid #e9ecef !important;
        }

        .custom-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .custom-table tbody tr:hover {
            background: #fafafa;
        }

        .btn-primary {
            background-color: #176b31 !important;
            border-color: #176b31 !important;
        }

        .btn-primary:hover {
            background-color: #0f4a22 !important;
        }

        .filter-label {
            font-weight: 600;
            font-size: 14px;
        }

        .search-input {
            border-radius: 8px;
        }

        .table-empty {
            padding: 40px !important;
            font-size: 17px;
            color: #777;
        }

        .action-btns .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="container-fluid">

        @include('layoutmodule::admin.flash')

        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h3 class="mb-0">إدارة الأسئلة</h3>
            <a href="{{ route(Auth::getDefaultDriver() . '.question.create') }}" class="btn btn-primary px-4">
                <i class="fas fa-plus ms-2"></i> إضافة سؤال جديد
            </a>
        </div>


        <div class="card filter-card shadow-sm mb-4">
            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <label class="filter-label">الفئة</label>
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الفئات</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="filter-label">النوع</label>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الأنواع</option>
                        <option value="mcq" {{ request('type') == 'mcq' ? 'selected' : '' }}>اختيار من متعدد</option>
                        <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>صح / خطأ</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="filter-label">بحث</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control search-input"
                            placeholder="ابحث في نص السؤال..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                    </div>
                </div>

            </form>
        </div>


        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table custom-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>السؤال</th>
                            <th>الفئة</th>
                            <th>النوع</th>
                            <th>الإجابة</th>
                            <th>الحالة</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                            ...
                        @empty
                            <tr>
                                <td colspan="7" class="text-center table-empty">
                                    <i class="fas fa-inbox fa-2x mb-3 text-muted"></i><br>
                                    لا توجد أسئلة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
        </div>


    </div>
@endsection
