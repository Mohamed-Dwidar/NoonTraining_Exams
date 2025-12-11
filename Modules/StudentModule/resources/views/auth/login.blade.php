@extends('layoutmodule::admin.login')

@section('content')

<div class="card border-grey border-lighten-3 m-0">
    <div class="card-header no-border">
        <div class="card-title text-xs-center">
            <div class="p-1">
                <a href="{{url('/')}}">
                    <img src="{{url('/assets/images/logo.png')}}" alt="main logo" style="width: 100px;">
                </a>
            </div>
        </div>
        <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2">
            <span>برنامج الاختبارات</span></h6>
            <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2">
                <span>تسجيل الدخول للطالب</span></h6>
    </div>
    <div class="card-body collapse in">
        <div class="card-block">
            @isset($url)
            <form method="POST" action='{{ url("$url/login") }}' id="form-login">
                @else
                <form method="POST" action="{{ route('student.loginpost') }}" aria-label="{{ __('Login') }}">
                    @endisset
                    @csrf

                    @if ($errors->any())
                    <div class="alert alert-warning mb-2" role="alert">
                        <strong>خطـأ!</strong>
                        {{$errors->first()}}
                    </div>
                    @endif
                    
                    <!-- Phone Number Field -->
                    <fieldset class="form-group position-relative has-icon-left mb-0">
                        <input type="text" 
                               class="form-control form-control-lg input-lg" 
                               name="phone" 
                               id="phone"
                               value="{{ old('phone') }}" 
                               placeholder="رقم الهاتف" 
                               required
                               maxlength="15"
                               pattern="[0-9+]+"
                               title="أدخل رقم هاتف صحيح (أرقام فقط مع +)">
                        <div class="form-control-position">
                            <i class="icon-phone"></i>
                        </div>
                        @if ($errors->has('phone'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                        @endif
                    </fieldset>
                    
                    <!-- Password Field -->
                    <fieldset class="form-group position-relative has-icon-left">
                        <input type="password" 
                               class="form-control form-control-lg input-lg" 
                               id="password"
                               name="password" 
                               placeholder="كلمة المرور" 
                               required>
                        <div class="form-control-position">
                            <i class="icon-key3"></i>
                        </div>
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </fieldset>
    
                    
                    <fieldset class="form-group row">
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                            <fieldset>
                                <input type="checkbox" 
                                       id="remember" 
                                       name="remember" 
                                       class="chk-remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">تذكرني</label>
                            </fieldset>
                        </div>
                        <div class="col-md-6 col-xs-12 text-xs-center text-md-right">
                            @if (Route::has('student.password.request'))
                            <a href="{{ route('student.password.request') }}" class="card-link">نسيت كلمة المرور؟</a>
                            @endif
                        </div>
                    </fieldset>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="icon-unlock2"></i>
                        {{ __('دخول الطالب') }}
                    </button>
                </form>
        </div>
    </div>
</div>

<!-- Optional: JavaScript for phone formatting -->
@section('scripts')
<script>
    // Format phone number input
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            value = '+' + value;
        }
        e.target.value = value;
    });
</script>
@endsection
@endsection