@extends('layoutmodule::admin.main')

@section('title', 'تعديل بيانات طالب')

@section('content')
    <div class="content-wrapper container-fluid">
        <div class="content-header">
            <div class="content-header-left mb-2 breadcrumb-new col">
                <h3><i class="fa fa-edit"></i> تعديل بيانات الطالب</h3>
            </div>
        </div>

        @include('layoutmodule::admin.flash')

        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card p-2">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-lg-12 col-12">

                                    <form method="POST"
                                        action="{{ route(Auth::getDefaultDriver() . '.students.update') }}">
                                        @csrf

                                        <input type="hidden" name="id" value="{{ $student->id }}">

                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <label class="form-label fw-bold">اسم الطالب</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name', $student->name) }}" required>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="form-label fw-bold">البريد الإلكتروني</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email', $student->email) }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <label class="form-label fw-bold">رقم الهاتف</label>
                                                <input type="text" name="phone" class="form-control"
                                                    value="{{ old('phone', $student->phone) }}">
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="form-label fw-bold">كلمة المرور</label>
                                                <div class="input-group">
                                                    <input type="password" id="passwordInput" name="password"
                                                        class="form-control" placeholder="أدخل كلمة مرور جديدة">
                                                    <span class="input-group-text" onclick="togglePassword()">
                                                        <i id="passwordIcon" class="fa fa-eye"></i>
                                                    </span>
                                                </div>
                                                <small class="text-muted">اترك الحقل فارغًا إذا كنت لا تريد تغيير كلمة
                                                    المرور</small>
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="form-label fw-bold">الرقم القومي</label>
                                                <input type="text" name="national_id" class="form-control"
                                                    value="{{ old('national_id', $student->national_id) }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <label class="form-label fw-bold">تاريخ الميلاد</label>
                                                <input type="date" name="birth_date" class="form-control"
                                                    value="{{ old('birth_date', $student->birth_date) }}">
                                            </div>

                                            <div class="col-lg-6">
                                                <label class="form-label fw-bold">النوع</label>
                                                <select name="gender" class="form-control">
                                                    <option value="male"
                                                        {{ $student->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                                    <option value="female"
                                                        {{ $student->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <label class="form-label fw-bold">كود الطالب</label>
                                                <input type="text" name="student_code" class="form-control"
                                                    value="{{ old('student_code', $student->student_code) }}">
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 mt-3">
                                            <button type="submit" class="btn btn-primary px-4">تحديث</button>
                                            <a href="{{ route(Auth::getDefaultDriver() . '.students.index') }}"
                                                class="btn btn-secondary">إلغاء</a>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

 @section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('passwordInput');
        const passwordIcon = document.getElementById('passwordIcon');
        
        if (togglePasswordBtn && passwordInput && passwordIcon) {
            togglePasswordBtn.addEventListener('click', function() {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    passwordIcon.classList.remove("fa-eye");
                    passwordIcon.classList.add("fa-eye-slash");
                    this.setAttribute('aria-label', 'إخفاء كلمة المرور');
                } else {
                    passwordInput.type = "password";
                    passwordIcon.classList.remove("fa-eye-slash");
                    passwordIcon.classList.add("fa-eye");
                    this.setAttribute('aria-label', 'إظهار كلمة المرور');
                }
            });
        }
        
        // Keep the original function for backward compatibility
        window.togglePassword = function() {
            const passwordInput = document.getElementById('passwordInput');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput && passwordIcon) {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    passwordIcon.classList.remove("fa-eye");
                    passwordIcon.classList.add("fa-eye-slash");
                } else {
                    passwordInput.type = "password";
                    passwordIcon.classList.remove("fa-eye-slash");
                    passwordIcon.classList.add("fa-eye");
                }
            }
        }
    });
</script>
@endsection

