@extends('layoutmodule::layouts.home_layout')

@section('title', 'تسجيل دخول الطلاب')

@section('content')

    <div class="content-wrapper">

        <!-- Left Section -->
        <div class="left-section">
            <div class="logo-container">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('admin-assets/images/logo/logo.png') }}" alt="شعار مركز نون للتدريب" class="logo">
                </a>
            </div>

            <h1 class="main-title">بوابة الاختبارات الإلكترونية</h1>
            <p class="sub-title">مركز نون للتدريب</p>

            <p class="description">
                منصة متخصصة ومنفصلة للاختبارات الإلكترونية المصممة وفق أحدث المعايير العالمية.
                نوفر لك تجربة تقييم شاملة ودقيقة تتماشى مع متطلبات السوق المهنية.
            </p>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="exam-card">

                <div class="modern-login-header">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('admin-assets/images/logo/logo.png') }}" alt="مركز نون للتدريب" class="modern-login-logo">
                </a>
                    <h1 class="modern-login-title">تسجيل دخول الطلاب</h1>
                    <p class="modern-login-subtitle">مرحباً بك في نظام الاختبارات الإلكتروني</p>
                </div>

                @if ($errors->any() || session('error'))
                    <div class="modern-alert alert-danger">
                        @if (session('error'))
                            {{ session('error') }}
                        @else
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                @endif

                @isset($url)
                    <form method="POST" action='{{ url("$url/login") }}' id="form-login">
                    @else
                        <form method="POST" action="{{ route('student.loginpost') }}" aria-label="{{ __('Login') }}">
                        @endisset
                        @csrf

                        <div class="modern-form-group">
                            <label for="phone">رقم الجوال</label>
                            <div class="modern-input-wrapper">
                                <i class="modern-input-icon icon-phone3"></i>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="modern-form-control @error('phone') is-invalid @enderror"
                                    placeholder="أدخل رقم الهاتف" required maxlength="15" pattern="[0-9+]+"
                                    title="أدخل رقم هاتف صحيح (أرقام فقط مع +)">
                            </div>
                            @if ($errors->has('phone'))
                                <div class="modern-invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>

                        <div class="modern-form-group">
                            <label for="password">كلمة المرور</label>
                            <div class="modern-input-wrapper">
                                <i class="modern-input-icon icon-key3"></i>
                                <input type="password" id="password" name="password"
                                    class="modern-form-control @error('password') is-invalid @enderror"
                                    placeholder="أدخل كلمة المرور" required>
                            </div>
                            @if ($errors->has('password'))
                                <div class="modern-invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="modern-checkbox-wrapper">
                            &nbsp;
                        </div>

                        <button type="submit" class="modern-btn-primary">
                            <i class="icon-unlock2"></i>
                            دخول النظام
                        </button>

                        @if (Route::has('student.password.request'))
                            <a href="{{ route('student.password.request') }}" class="modern-forgot-link">نسيت كلمة
                                المرور؟</a>
                        @endif
                    </form>

            </div>

        </div>
    </div>
@endsection
