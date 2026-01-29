@extends('layoutmodule::admin.login')

@section('content')
    <div class="content-wrapper">
        <!-- Left Section -->
        <div class="left-section">
            <div class="logo-container">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="شعار مركز نون للتدريب" class="logo">
                </a>
            </div>

            <h1 class="main-title">بوابة الاختبارات الإلكترونية</h1>
            <p class="sub-title">مركز نون للتدريب المتخصص</p>

            <p class="description">
                منصة متخصصة ومنفصلة للاختبارات الإلكترونية المصممة وفق أحدث المعايير العالمية.
                نوفر لك تجربة تقييم شاملة ودقيقة تتماشى مع متطلبات السوق المهنية.
            </p>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="exam-card">

                <div class="exam-icon">📋</div>

                <h2 class="card-title">ابدأ اختبارك الآن</h2>
                <p class="card-subtitle">اختبارات مهنية معتمدة ومتخصصة</p>

                <div class="features-grid">
                    <div class="feature-item">✅ تقييم فوري</div>
                    <div class="feature-item">🎯 دقة عالية</div>
                    <div class="feature-item">🔒 أمان تام</div>
                    <div class="feature-item">📊 تقارير مفصلة</div>
                    <div class="feature-item">⚡ سرعة في النتائج</div>
                    <div class="feature-item">🏆 معايير مهنية</div>
                </div>

                <a href="{{ route('student.login') }}" class="start-btn">
                    دخول إلى منصة الاختبارات
                </a>

                <div class="info-note">
                    <strong>💡 تنويه مهم:</strong><br>
                    تأكد من استقرار الاتصال بالإنترنت واختر مكان هادئ للتركيز أثناء الاختبار
                </div>

            </div>
        </div>
    </div>
@endsection
