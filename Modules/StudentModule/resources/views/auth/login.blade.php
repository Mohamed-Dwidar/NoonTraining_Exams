@extends('layoutmodule::layouts.home_layout')

@section('title', 'تسجيل دخول الطلاب')

@section('content')

    <div class="content-wrapper">

        <!-- Left Section -->
        <div class="left-section">
            <div class="logo-container">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('admin-assets/images/logo/logo.png') }}" alt="شعار معهد نون للتدريب" class="logo">
                </a>
            </div>

            <h1 class="main-title">بوابة الاختبارات الإلكترونية</h1>
            <p class="sub-title">معهد نون للتدريب</p>

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
                    <img src="{{ asset('admin-assets/images/logo/logo.png') }}" alt="معهد نون للتدريب" class="modern-login-logo">
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
                            <label for="national_id">رقم الهوية</label>
                            <div class="modern-input-wrapper">
                                <i class="modern-input-icon icon-card"></i>
                                <input type="text" id="national_id" name="national_id" value="{{ old('national_id') }}"
                                    class="modern-form-control @error('national_id') is-invalid @enderror"
                                    placeholder="أدخل رقم الهوية" required autofocus pattern="\d{10,15}"
                                    title="يرجى إدخال رقم هوية صالح (10-15 رقم)">
                            </div>
                            @if ($errors->has('national_id'))
                                <div class="modern-invalid-feedback">{{ $errors->first('national_id') }}</div>
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
